<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

 // mostra lista de todos os users (mesmo users bloqueados e administradores) mostra nome, email, type e status (bloqueado ou não)
Route::get('/users', 'UserController@listAllUsersToAdmin')->name('list.of.all.users');

Route::patch('/users/{user}/block', 'UserController@blockUser')->name('block.user');

Route::patch('/users/{user}/unblock', 'UserController@unblockUser')->name('unblock.user');

 // promove user para admin
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->name('promote.user');

  // tira admin do user
Route::patch('/users/{user}/demote', 'UserController@demoteUser')->name('demote.user');

Route::patch('/me/password', 'UserController@changePassword')->name('me.password');

 // mudar perfil 
Route::patch('/me/profile', 'UserController@meProfile')->name('me.profile');



