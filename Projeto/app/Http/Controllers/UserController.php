<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Validator;

class UserController extends Controller
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

     //US5 e US6--------------------------------------------------------
    public function listAllUsersToAdmin(Request $request) {

        $pagetitle = "List of Users";
        $users=UserController::filter($request);

        return view('users.listUsersToAdmin', compact('users', 'pagetitle')); 
        
    }

    private static function filter(Request $request){
        //se nada estiver prenchido
        if(!$request->filled('name') && !$request->filled('type') && !$request->filled('status')){
           return User::all(); 
        }

        //se type ou status invalido
        if(($request->filled('type') && $request->query('type')!='admin' && $request->query('type')!='normal') || ($request->filled('status') && $request->query('status')!='blocked' && $request->query('status')!='unblocked')){

            return User::all();
        }

        //só type normal
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && !$request->filled('status')){
            return User::where('admin','=',false)->get();
        }
        //só type admin
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && !$request->filled('status')){

            return User::where('admin','=',true)->get();
        }

        //só status blocked
        if(!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='blocked'){
            return User::where('blocked','=',true)->get();
        }

        //só status unblocked
        if(!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='unblocked'){
            return User::where('blocked','=',false)->get();
        }

        //type+status
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && $request->filled('status')){
            //'normal'+'blocked'
            if($request->query('status')=='blocked'){
                return User::where('admin','=',false)->where('blocked','=',true)->get();
            }
            //'normal'+'unblocked'
            else{
                return User::where('admin','=',false)->where('blocked','=',false)->get();
            }
        }

        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && $request->filled('status')){
            //'admin'+'blocked'
            if($request->query('status')=='blocked'){
                return User::where('admin','=',true)->where('blocked','=',true)->get();
            }
            //'admin'+'unblocked'
            else{
                return User::where('admin','=',true)->where('blocked','=',false)->get();
            }
        }

        //só nome
        if($request->filled('name') && !$request->filled('type') && !$request->filled('status')){
            return User::where('name','like','%'.$request->query('name').'%')->get();
        }

        //nome+type
        if($request->filled('name') && $request->filled('type') && !$request->filled('status')){
            //'normal'
            if($request->query('type')=='normal'){
                return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->get();
            }
            else{
                //'admin'
                return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->get();
            }
        }
        //nome+status
        if($request->filled('name') && !$request->filled('type') && $request->filled('status')){
            //blocked
            if($request->query('status')=='blocked'){
                return User::where('name','like','%'.$request->query('name').'%')->where('blocked','=',true)->get();
            }
            else{
                //unblocked
                return User::where('name','like','%'.$request->query('name').'%')->where('blocked','=',false)->get();
            }
        }
         //nome+type+status
        if($request->filled('name') && $request->filled('type') && $request->filled('status')){
            //'nome'+'normal'+'blocked'
            if($request->query('type')=='normal'){
                if($request->query('status')=='blocked'){
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->where('blocked','=',true)->get();
                }
                 //'nome'+'normal'+'unblocked'
                else{
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->where('blocked','=',false)->get();
                }
            }
            //'nome'+'admin'+'blocked'
            else{
                if($request->query('status')=='blocked'){
                    var_dump(5.111);
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->where('blocked','=',true)->get();
                }
            //'nome'+'admin'+'unblocked'
                else{
                    return User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->where('blocked','=',false)->get();
                }
            }
        }
    }
    //------------------------------------------------------------------------


    public function blockUser(Request $request, $user) {
        $userToBlock = User::findOrFail($user);
        if (Auth::user()->id == $userToBlock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToBlock->blocked == 1) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already blocked"); 
        } 
        $userToBlock->blocked = 1;
        $userToBlock->save();

        return redirect()->route('list.of.all.users')->with('successMsg', "User was blocked succesfully"); 
    }

    public function unblockUser(Request $request, $user) {

        $userToUnblock = User::findOrFail($user);
        
        if (Auth::user()->id == $userToUnblock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToUnblock->blocked == 0) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already not blocked");
        };
        $userToUnblock->blocked = 0;
        $userToUnblock->save();


        return redirect()->route('list.of.all.users')->with('successMsg', "User was unblocked succesfully"); 
    }

    public function promoteUser(Request $request, $user) {
        $userToPromote = User::findOrFail($user);
        if (Auth::user()->id == $userToPromote->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToPromote->admin == 1) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already admin");
        };
        $userToPromote->admin = 1;
        $userToPromote->save();

        return redirect()->route('list.of.all.users')->with('successMsg', "User was promoted succesfully"); 
    }

    public function demoteUser(Request $request, $user) {
        $userToDemote = User::findOrFail($user);
        if (Auth::user()->id == $userToDemote->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToDemote->admin == 0) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already not admin");
        };
        $userToDemote->admin = 0;
        $userToDemote->save();
        return redirect()->route('list.of.all.users')->with('successMsg', "User was demoted succesfully"); 
    }

     public function showChangePasswordForm(){
        $pagetitle = "Change My Password";
        return view('users.changePassword', compact('pagetitle'));
    }

    public function changePassword(Request $request){
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        //validação:
        //require-> campo tem de estar preenchido
        //confirmed-> existe um campo que há pelo menos outro campo igual no questionario e que é diferente de old_passwod
         
         // A password change fails with invalid old password
         // 
        $validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ], [ 
        'old_password.required' => 'You must enter your current password',
        'password.required' => 'You must enter a new password',
        'password.different' => 'The new password must be different from the current password',
        'password.min' => 'The new password must have at least 3 characters',
        'password_confirmation.required' => 'You must enter the confirmation password',
        'password_confirmation.same' => 'The confirmation password must be equal to new password',
        ]);

        //false->old diferente da pass entao envia erro
        if (!(Hash::check($request->input('old_password'), Auth::user()->password))) {
            return redirect()->route('me.password')->withErrors(['old_password' => 'Please enter the correct current password']);
        }
            
        $user_id=Auth::user()->id;
        $user=User::findOrFail($user_id);
        $user->password=Hash::make($request->input('password'));
        $user->save();


        return redirect()->route('home')->with('success', 'Your password has been updated');
        
    }

    public function showEditMyProfileForm(){
        $pagetitle = "Update My Profile";
        return view('users.editMyProfile', compact('pagetitle'));
    }

    public function updateMyProfile(Request $request){
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        $validatedData=$request->validate([
            'name'=>'required|regex:/(^[A-Za-z ]+$)+/',
            'phone'=>'nullable|regex:/(^[0-9\+ ]+$)+/',
            'email' => 'email|required|'.Rule::unique('users')->ignore(Auth::User()->id),
            'profile_photo'=>'nullable|image|mimes:png,jpeg,jpg',
        ], [ 
        'name.required' => 'Name must not be empty',
        'name.regex' => 'Name must only have letters and spaces',
        'email.required' => 'The email must not be empty',
        'email.unique' => 'The email has already in use',
        'email.email' => 'Please introduce an email in correct format',
        'phone.regex' => 'Please enter only numbers in phone, spaces and symbol + on the phone',
        'profile_photo.mimes' =>'The profile photo must be a file png, jpg, jpeg.',
        ]);

        $user = $request->user();


        $user->fill($validatedData);

        if(!$request->has('phone')){
            $user->phone=null;
        }


        if ($request->hasFile('profile_photo')) {
            $path= Storage::putFile('public/profiles/', $request->file('profile_photo'));
            $user->profile_photo=basename($path);
        }

        $user->update();

        return redirect()->route('home')->with('success', 'Your update has been updated');
    }

    public function getProfile(Request $request) {
        $pagetitle = "List of Users";

        $users = $this->filterProfilesByName($request);
        $associateds = Auth::user()->associateds;
        $associated_of = Auth::user()->associated_of;

        return view('users.profiles', compact('users', 'associateds', 'associated_of', 'pagetitle')); 
    }

    public function filterProfilesByName(Request $request) {
        $name = $request->query('name');
        if (empty($name)) {
            return User::all();
        } else {
            return User::where('name', 'like', '%'.$name.'%')->get();
        }

    }

    public function getAssociates(){
        $pagetitle = "List of Associated users";
        $users = Auth::user()->associateds; 
        return view('users.listofAssociateMembers', compact('users', 'pagetitle'));
    }

    public function getAssociateOfMe(){
        

    }
    public function createAssociate(){

    }
    public function destroyAssociate($id){

    }
    public function showSummary(){
        
    }
}
