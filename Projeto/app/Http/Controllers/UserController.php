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

        //se nada estiver prenchido
        if(!$request->filled('name') && !$request->filled('type') && !$request->filled('status')){
           $users=User::all();
           return view('users.listUsersToAdmin', compact('users', 'pagetitle'));  
        }
        //se type ou status invalido
        if(($request->filled('type') && $request->query('type')!='admin' && $request->query('type')!='normal') || ($request->filled('status') && $request->query('status')!='blocked' && $request->query('status')!='unblocked')){

            $users=User::all();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }

        //suporta type normal só type admin
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && !$request->filled('status')){

            $users=User::where('admin','=',true)->get();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }

        //suporta status blocked
        if(!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='blocked'){
            $users=User::where('blocked','=',true)->get();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }

        //suporta status unblocked
        if(!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='unblocked'){
            $users=User::where('blocked','=',false)->get();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }

        //suporta type normal só type normal
        if(!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && !$request->filled('status')){
            $users=User::where('admin','=',false)->get();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }

        //ver se suporta nome total ou parcial - apenas
        if($request->filled('name') && !$request->filled('type') && !$request->filled('status')){
            $users=User::where('name','like','%'.$request->query('name').'%')->get();
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }
        //ver se suporta nome total ou parcial + type
        if($request->filled('name') && $request->filled('type') && !$request->filled('status')){
            if($request->query('type')=='normal'){
                $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->get();
            }
            else{
                $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->get();
            }
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }
        //ver se suporta nome total ou parcial + status
        if($request->filled('name') && !$request->filled('type') && $request->filled('status')){
            if($request->query('status')=='blocked'){
                $users=User::where('name','like','%'.$request->query('name').'%')->where('blocked','=',true)->get();
            }
            else{
                $users=User::where('name','like','%'.$request->query('name').'%')->where('blocked','=',false)->get();
            }
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
        }
         //ver se suporta nome total ou parcial + type + status
        if($request->filled('name') && $request->filled('type') && $request->filled('status')){
            if($request->query('type')=='normal'){
                if($request->query('status')=='blocked'){
                    $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->where('blocked','=',true)->get();
                }
                else{
                    $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',false)->where('blocked','=',false)->get();
                }
            }
            else{
                if($request->query('status')=='blocked'){
                    var_dump(5.111);
                    $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->where('blocked','=',true)->get();
                }
                else{
                    $users=User::where('name','like','%'.$request->query('name').'%')->where('admin','=',true)->where('blocked','=',false)->get();
                }
            }
            return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
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
