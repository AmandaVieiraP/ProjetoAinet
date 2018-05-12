<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\AccountType;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function showAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }
        $accounts = $user->accounts;
        $accounts_type = AccountType::all();

        $pagetitle = "User's accounts";
        
        return view('users.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 
    }

    public function showOpenAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }
        $allAccounts = $user->accounts;
        $accounts_type = AccountType::all();

        $accounts = array();
        foreach ($allAccounts as $a) {
            if(is_null($a->deleted_at)) {
                $accounts[] = $a;
            }
        }

        $pagetitle = "User's open accounts";

        return view('users.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 

    }
    public function showCloseAccounts($user){
        $user = User::findOrFail($user);
        if(Auth::user()->id != $user->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403); 
        }

        $allAccounts = $user->accounts;
        $accounts_type = AccountType::all();

        $accounts = array();
        foreach ($allAccounts as $a) {
            if(!is_null($a->deleted_at)) {
                $accounts[] = $a;
            }
        }

        $pagetitle = "User's closed accounts";

        return view('users.listUserAccounts', compact('accounts', 'accounts_type', 'user', 'pagetitle')); 
    }

    public function updateClose($account){

    }

    public function updateReopen($account){

    }
}
