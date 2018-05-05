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

//US1
Route::get('/', 'Controller@initialPage')->name('initial.page');

//US2, US3, US4
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//US5, US6
Route::get('/users', 'UserController@listAllUsersToAdmin')->middleware('admin')->name('list.of.all.users');

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
//US - 12
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

//US.20
Route::get('/movements/{account}','MovementController@index')->name('movements.index');
//US.21
Route::get('/movements/{account}/create','MovementController@create')->name('movements.create');
//US.21
Route::post('/movements/{account}/create','MovementController@store')->name('movements.store');
//US.21
Route::get('/movements/{account}/{movement}','MovementController@edit')->name('movements.edit');
//US.21
Route::put('/movements/{account}/{movement}','MovementController@update')->name('movements.update');
//US.21
Route::delete('/movements/{account}/{movement}','MovementController@destroy')->name('movements.destroy');

//US.24
Route::delete('/documents/{movement}/{document}','DocumentController@destroy')->name('documents.destroy');
//US.25
Route::get('/documents/{movement}/{document}','DocumentController@getDoc')->name('documents.getdoc');
//US.26
Route::get('/me/dashboard','UserController@showSummary')->name('user.summary');
//US.29
Route::post('/me/associates','UserController@createAssociate')->name('users.associate.create');
//US.30
Route::delete('/me/associates/{user}','UserController@destroyAssociate')->name('users.associate.destroy');