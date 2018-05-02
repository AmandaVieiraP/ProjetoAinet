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

Route::get('profiles/{name}', 'UserController@getProfile')->name('users.profiles');
Route::get('me/associates', 'UserController@getAssociates')->name('users.associates');
Route::get('me/associate-of','UserController@getAssociateOfMe')->name('me.associateOf');
Route::get('accounts/{user}','AccountController@showAccount')->name('account');
Route::get('accounts/{user}/opened','AccountController@showOpenAccount')->name('openedAcounts');
Route::get('accounts/{user}/closed','AccountController@showCloseAccount')->name('account.closed');
Route::delete('account/{account}', 'AccountController@destroy')->name('account.delete');
Route::patch('account/{account}/close','AccountController@updateClose')->name('account.close');
Route::patch('account/{account}/reopen','AccountController@updateReopen')->name('account.reopen');
Route::post('account','AccountController@store')->name('accounts.store');
Route::put('account/{account}','AccountController@update')->name('accounts.editAccount');

