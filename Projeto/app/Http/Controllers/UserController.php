<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RedirectResponse;
use Illuminate\Support\Facades\Response;
use App\User;

class UserController extends Controller
{
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

    public function listAllUsersToAdmin(Request $request) {

        $pagetitle = "List of Users";
        $users=UserController::filter($request);

        return view('users.listUsersToAdmin', compact('users', 'pagetitle')); 
        
    }

    private static function filter(Request $request){
        //se nada estiver prenchido
        if(!$request->filled(['name','type','status'])){
           return User::all(); 
        }

        //se type ou status invalido
        if(($request->filled('type') && $request->query('type')!='admin' && $request->query('type')!='normal') || ($request->filled('status') && $request->query('status')!='blocked' && $request->query('status')!='unblocked')){

            return User::all();
        }

        //só type normal
        if(!$request->filled(['name','status'])&& $request->filled('type') && $request->query('type')=='normal'){
            return User::where('admin','=',false)->get();
        }
        //só type admin
        if(!$request->filled(['name','status'])&& $request->filled('type') && $request->query('type')=='admin'){

            return User::where('admin','=',true)->get();
        }

        //só status blocked
        if(!$request->filled(['name','type']) && $request->filled('status') && $request->query('status')=='blocked'){
            return User::where('blocked','=',true)->get();
        }

        //só status unblocked
        if(!$request->filled(['name','type']) && $request->filled('status') && $request->query('status')=='unblocked'){
            return User::where('blocked','=',false)->get();
        }

        //type+status
        if(!$request->filled('name')&& $request->filled(['type','status']) && $request->query('type')=='normal'){
            //'normal'+'blocked'
            if($request->query('status')=='blocked'){
                return User::where('admin','=',false)->where('blocked','=',true)->get();
            }
            //'normal'+'unblocked'
            else{
                return User::where('admin','=',false)->where('blocked','=',false)->get();
            }
        }

        if(!$request->filled('name')&& $request->filled(['type','status']) && $request->query('type')=='admin'){
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
        if($request->filled(['name','status']) && !$request->filled('type')){
            return User::where('name','like','%'.$request->query('name').'%')->get();
        }

        //nome+type
        if($request->filled(['name','type']) && !$request->filled('status')){
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
        if($request->filled(['name','status']) && !$request->filled('type')){
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
        if($request->filled(['name','type','status'])){
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



    public function blockUser(Request $request, $user) {
        $userToBlock = User::findOrFail($user);
        if (Auth::user()->id == $userToBlock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
            //abort(403, "unauthorized");
            //return redirect()->route('list.of.all.users')->with('msg', "You can't block yourself"); 
        }
        if ($userToBlock->blocked == 1) {
            $request->session()->flash('errorMsg', 'User is already blocked!');
            return $this->listAllUsersToAdmin($request);
        } 
        $userToBlock->blocked = 1;
        $userToBlock->save();

        $request->session()->flash('successMsg', 'User blocked succesufully!');
        return $this->listAllUsersToAdmin($request);
    }

    public function unblockUser(Request $request, $user) {

        $userToUnblock = User::findOrFail($user);
        
        if (Auth::user()->id == $userToUnblock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
            //abort(403, "unauthorized");
            //return redirect()->route('list.of.all.users')->with('msg', "You can't unblock yourself"); 
        }
        if ($userToUnblock->blocked == 0) {
            //return redirect()->route('list.of.all.users')->with('msg', "User is already not blocked");
            $request->session()->flash('errorMsg', 'User is already unblocked!');
            return $this->listAllUsersToAdmin($request);
        };
        $userToUnblock->blocked = 0;
        $userToUnblock->save();
        $request->session()->flash('successMsg', 'User unblocked succesufully!');
        return $this->listAllUsersToAdmin($request);
    }

    public function promoteUser(Request $request, $user) {
        $userToPromote = User::findOrFail($user);
        if (Auth::user()->id == $userToPromote->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
           // abort(403, "unauthorized");
        }
        if ($userToPromote->admin == 1) {
            $request->session()->flash('errorMsg', 'User is already admin!');
            return $this->listAllUsersToAdmin($request);
        };
        $userToPromote->admin = 1;
        $userToPromote->save();

        $request->session()->flash('successMsg', 'User promoted succesufully!');
        return $this->listAllUsersToAdmin($request);
    }

    public function demoteUser(Request $request, $user) {
        $userToDemote = User::findOrFail($user);
        if (Auth::user()->id == $userToDemote->id) {
           // abort(403, "unauthorized");
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToDemote->admin == 0) {
            $request->session()->flash('errorMsg', 'User is already not admin!');
            return $this->listAllUsersToAdmin($request);
        };
        $userToDemote->admin = 0;
        $userToDemote->save();

        $request->session()->flash('successMsg', 'User demoted succesufully!');
        return $this->listAllUsersToAdmin($request);
    }

    public function getProfile($name) {

    }

    public function getAssociates(){
        //As a user I want to view the list of users that belong to my group of associate members.
        
        //The list should show at least the name and email of the member;
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
