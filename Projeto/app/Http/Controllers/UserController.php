<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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



    public function blockUser($user) {

    }

    public function unblockUser($user) {

    }

    public function promoteUser($user) {

    }

    public function demoteUser($user) {
        
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
