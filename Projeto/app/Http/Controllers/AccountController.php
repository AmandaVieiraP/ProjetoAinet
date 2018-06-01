<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AccountType;
use App\Account;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Movement;
use \App\Http\Requests\StoreAccount;

class AccountController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pagetitle="Add New Account";
        $types = AccountType::all();

        return view('accounts.createAccount', compact('pagetitle','types')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->has('cancel'))
            return redirect()->route('accounts',Auth::user()->id);

        $accounts=Auth::user()->allAccounts;

        $types=[];
        $codes=[];
        
        foreach($accounts as $a){
            $types[]=$a->account_type_id;
            $codes[]=$a->code;
        }

        $validatedData=$request->validate([
           'account_type_id' =>'required|exists:account_types,id',
           'code'=>'required',
           'date'=>'date',
           'start_balance'=>'required',
           'description'=>'nullable',
        ], [ 
            'account_type_id.required' => 'The account type can not be empty',
            'account_type_id.exists' => 'The type choosen is not valid',
            'code.required' => 'The code can not be empty',
            'date.date' => 'The date is invalid',
            'start_balance.required'=> 'The start balance value can not be empty',

        ]);

        if(!is_numeric($request->input('start_balance'))){
            return redirect()->route('account.store')->withErrors(['start_balance' => 'The start_balance must be a numeric value in format <0.00>']);
        }

        if(in_array($request->input('account_type_id'),$types)){
            return redirect()->route('account.store')->withErrors(['account_type_id' => 'You already have an account with this type']);
        }

        if(in_array($request->input('code'),$codes)){
            return redirect()->route('account.store')->withErrors(['code' => 'The code is already in use']);
        }

        if(!$request->filled('date')){
            $validatedData['date']=Carbon::now();
        }

        $validatedData['current_balance']=$request->input('start_balance');
        $validatedData['owner_id']=Auth::user()->id;
        $account=Account::create($validatedData);

        return redirect()->route('accounts',Auth::user()->id)->with('successMsg', 'Account created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $types= AccountType::all();
        $account = Account::findOrFail($id);
        $pagetitle = "Update Account";

        if (Auth::user()->can('edit-account', $id)) {
            return view('accounts.editAccount', compact('types','account','pagetitle'));    
        }
        else{
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAccount $request, $id)
    {
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }
        $account = Account::findOrFail($id);
        if (!Auth::user()->can('edit-account', $id)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
          $validatedData=  $request->validated();;
  
        $old_bal = $account->start_balance;
        $account->fill($validatedData);

      if($old_bal != $account->start_balance){
          $movements=Movement::where('account_id', '=', $account->id)->orderBy('date')->get();
          if(count($movements)==0){
            $account->current_balance = $validatedData['start_balance'];  
          }
          else{ 
            $ultimo=$account->start_balance;
            foreach ($movements as $mov) {
              $mov->start_balance = $ultimo;
              if(strcmp($mov->type,"expense") ==0){
                $mov->end_balance= round($mov->start_balance - $mov->value,2);
              }else{
                $mov->end_balance= round($mov->start_balance + $mov->value,2);  
              }
              $ultimo = round($mov->end_balance,2);
              $mov->update();
            }
              //$movements->update();
              $account->current_balance = round($ultimo,2);
          }

        }  

        $account->update();
        return redirect()->route('home')->with('success', 'Your account has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $movements = $account->movements;
        $user = $account->user;

        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        } 

        if (is_null($account->last_movement_date) && $movements->isEmpty()) {
            $account->forceDelete();
            return redirect()->route('accounts', $user->id)->with('successMsg', "Account was deleted succesfully");  
        } 
    
        //soft delete
        $account->delete();
        return redirect()->route('accounts', $user->id)->with('warningMsg', "Account couldn't be deleted succesfully because it at movements so it was closed");  
    }


    public function showAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }
        $accounts = $user->allAccounts;
        $accounts_type = AccountType::all();

        $pagetitle = "User's accounts";
        
        return view('accounts.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 
    }

    public function showOpenAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }
        $allAccounts = $user->allAccounts;
        $accounts_type = AccountType::all();

          $accounts = array();
       foreach ($allAccounts as $a) {
            if(!$a->trashed()) {
                $accounts[] = $a;
            } 
        }  

        $pagetitle = "User's open accounts";

        return view('accounts.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 

    }
    public function showCloseAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }

        $accounts_type = AccountType::all();

        $accounts = $user->closedAccounts;

        $pagetitle = "User's closed accounts";

        return view('accounts.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 
    }

    public function updateClose($account){
        $account = Account::findOrFail($account);
        $user = $account->user;
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }
        //soft delete
        $account->delete();
        return redirect()->route('accounts', $user->id)->with('successMsg', "Account was closed succesfully");
    }

    public function updateReopen($account){
        
        $account = Account::withTrashed()->findOrFail($account);
        
        $user = $account->user;

        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }

        if($account->trashed()){
            $account->restore();
        }

        return redirect()->route('accounts', $user->id)->with('successMsg', "Account was open succesfully");

    }
}
