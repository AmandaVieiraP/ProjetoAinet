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

    public function listAllUsersToAdmin() {
       $pagetitle = "List of Users";

        $type=UserController::validate_type($request);

        $status=UserController::validate_status($request);

        $name=$request->input('name');

        $users=UserController::filter($name, $type, $status);

        return view('users.listUsersToAdmin', compact('users', 'pagetitle'));   
    }

    private static function validate_type(Request $request){
        $type=$request->input('type');

        if($type=='normal')
            return '0';
        elseif($type!=null && $type=='admin')
            return '1';
    }

    private static function validate_status(Request $request){
        $status=$request->input('status');
        if($status!=null && $status=='blocked')
            return '1';
        elseif($status!=null && $status=='unblocked')
            return '0';
    }

    private static function filter($name, $type, $status){
        if($name==null  && $type==null && $status==null){
            return User::All();
        }

        //se tem só name
        if($name!=null && $type==null && $status==null){
           return User::where('name','like','%'.$name.'%')->get();
        }

        //se so tem type
        if($name==null && $type!=null && $status==null){
           return User::where('admin','=',$type)->get();
        }

        //se so tem status
        if($name==null && $type==null && $status!=null){
           return User::where('blocked','=',$status)->get();
        }

        //se tem name e type
        if($name!=null && $type!=null && $status==null){
           return User::where('name','like','%'.$name.'%')->where('admin','=',$type)->get();
        }
        //se tem name e status
        if($name!=null && $type==null && $status!=null){
           return User::where('name','like','%'.$name.'%')->where('blocked','=',$status)->get();
        }
        //se tem status e type
        if($name==null && $type!=null && $status!=null){
           return User::where('admin','=',$type)->where('blocked','=',$status)->get();
        }
        //se tem os tres
        if($name!=null && $type!=null && $status!=null){
           return User::where('name','like','%'.$name.'%')->where('admin','=',$type)->where('blocked','=',$status)->get();
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