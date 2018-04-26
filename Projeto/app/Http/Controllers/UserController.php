<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index() {
    	$users = User::All();
        $pagetitle = "List of Users";
        return view('users.list', compact('users', 'pagetitle'));
    }

    public function create() {
    	//$users = User::create();
    	//$pagetitle = "Add user";
        $pagetitle = "Add user";
        return view('users.create', compact('pagetitle'));
    	//return view('users.create', compact('users', 'pagetitle'));
        //return view('users.create', compact('pagetitle', 'users'));
    }

    public function store(Request $request) {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }
        $user = $request->validate([
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email', 
            'type' => 'required|integer|between:0,2',
            'password' => 'required|min:8|confirmed'
        ], [  // Custom Messages
            'name.regex' => 'Name must only contain letters and spaces.',
            'password.confirmed' => 'Password and password confirmation must be equal.',
        ]);


        $user['password'] = Hash::make('password');


        /*$user = fill([
            'password' => Hash::make($request->password)
        ]); */
         
        // if any error, automatic redirect to back (previous)
        // in this case, goes to "/users/create"
        // Error messages are automatically flashed to the session.
        
        User::create($user);
        return redirect()->action('UserController@index')->with('status', 'User add succesufully');
    }

    public function edit($id) {
    	$pagetitle = "Edit user";
        $user = User::findOrFail($id);
        return view('users.edit', compact('pagetitle', 'user'));
    }

    public function destroy($id) {
    	User::destroy($id);
        return redirect()->action('UserController@index');
    }

    public function update(Request $request, $id)
    {
        if ($request->has('cancel')) {
            return redirect()->action('UserController@index');
        }
        $user = $request->validate([
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email', 
            'type' => 'required|integer|between:0,2'
            /*'password' => 'required|min:8',
            'password_confirmation' => 'required'*/
        ], [  // Custom Messages
            'name.regex' => 'Name must only contain letters and spaces.',
        ]);
        $userModel = User::findOrFail($id);
        $userModel->fill($user);
        $userModel->save();
        return redirect()->action('UserController@index')->with('status', 'User edited succesufully');
    }


}
