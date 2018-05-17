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
Route::get('login','Auth\LoginController@showLoginForm')->name('login');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('/home', 'HomeController@index')->name('home');

//US5, US6
Route::get('/users', 'UserController@listAllUsersToAdmin')->middleware('admin')->name('list.of.all.users');

//US7
Route::get('/users/{user}/block', 'UserController@blockUser')->middleware('admin')->name('block.user');
Route::patch('/users/{user}/block', 'UserController@blockUser')->middleware('admin')->name('block.user');

Route::get('/users/{user}/unblock', 'UserController@unblockUser')->middleware('admin')->name('unblock.user');
Route::patch('/users/{user}/unblock', 'UserController@unblockUser')->middleware('admin')->name('unblock.user');

 // promove user para admin
Route::get('/users/{user}/promote', 'UserController@promoteUser')->middleware('admin')->name('promote.user');
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->middleware('admin')->name('promote.user');

  // tira admin do user
Route::get('/users/{user}/demote', 'UserController@demoteUser')->middleware('admin')->name('demote.user');
Route::patch('/users/{user}/demote', 'UserController@demoteUser')->middleware('admin')->name('demote.user');

//US9
Route::get('/me/password','UserController@showChangePasswordForm')->name('me.passwordForm');
Route::patch('/me/password', 'UserController@changePassword')->name('me.password');

//US10
Route::get('/me/profile','UserController@showEditMyProfileForm')->name('me.profileForm');
Route::put('/me/profile', 'UserController@updateMyProfile')->name('me.profile');

//US11
Route::get('/profiles', 'UserController@getProfile')->name('users.profiles');

//US12
Route::get('/me/associates', 'UserController@getAssociates')->name('users.associates');
//ACRESCENTADO
Route::get('/me/accounts',function(){
	return redirect()->route('accounts.opened',Auth::id());
})->name('my.accounts');
//US13
Route::get('/me/associate-of','UserController@getAssociateOfMe')->name('me.associateOf');
//US14
Route::get('/accounts/{user}','AccountController@showAccounts')->name('accounts');
Route::get('/accounts/{user}/opened','AccountController@showOpenAccounts')->name('accounts.opened');
Route::get('/accounts/{user}/closed','AccountController@showCloseAccounts')->name('accounts.closed');
//US15
Route::delete('/account/{account}', 'AccountController@destroy')->name('account.delete');
Route::get('/account/{account}/close','AccountController@updateClose')->name('account.close');
Route::patch('/account/{account}/close','AccountController@updateClose')->name('account.close');
//US16
Route::patch('/account/{account}/reopen','AccountController@updateReopen')->name('account.reopen');
//US17
Route::get('/account','AccountController@showAddAccountForm');
Route::post('/account','AccountController@store')->name('accounts.store');
//------------ACRESCENTADO--------------
Route::get('/account/{account}','AccountController@showAccForm')->name('accounts.editAccount');/*->middleware('can:edit-account,account_id');*/
//US18
Route::put('/account/{account}','AccountController@update')->name('accounts.updateAccount');

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
//US23
Route::get('/documents/{movement}','DocumentController@showUploadForm')->name('getdocument.movement');
Route::post('/documents/{movement}','DocumentController@update')->name('postdocument.movement');
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