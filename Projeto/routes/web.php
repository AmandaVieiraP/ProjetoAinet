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
Route::get('/users/{user}/promote', 'UserController@promoteUser')->middleware('admin')->name('promote.user');
Route::patch('/users/{user}/promote', 'UserController@promoteUser')->middleware('admin')->name('promote.user');
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

//US13
Route::get('/me/associate-of','UserController@getAssociateOfMe')->name('me.associateOf');
//ACRESCENTADO
Route::get('/me/accounts',function(){
	return redirect()->route('accounts.opened',Auth::id());
})->name('my.accounts');


//US14
Route::get('/accounts/{user}','AccountController@showAccounts')->name('accounts');
Route::get('/accounts/{user}/opened','AccountController@showOpenAccounts')->name('accounts.opened');
Route::get('/accounts/{user}/closed','AccountController@showCloseAccounts')->name('accounts.closed');
Route::delete('/account/{account}', 'AccountController@destroy')->name('account.delete');
Route::get('/account/{account}/close','AccountController@updateClose')->name('account.close');
Route::patch('/account/{account}/close','AccountController@updateClose')->name('account.close');
Route::patch('/account/{account}/reopen','AccountController@updateReopen')->name('account.reopen');


//US17
Route::get('/account','AccountController@create')->name('account.create');
Route::post('/account','AccountController@store')->name('account.store');

//US18
Route::get('/account/{account}','AccountController@edit')->name('account.edit');
Route::put('/account/{account}','AccountController@update')->name('account.update');

//US.20
Route::get('/movements/{account}','MovementController@index')->name('movement.index');
//US.21
Route::get('/movements/{account}/create','MovementController@create')->name('movement.create');
//US.21
Route::post('/movements/{account}/create','MovementController@store')->name('movement.store');
//US.21
Route::get('/movement/{movement}','MovementController@edit')->name('movement.edit');
//US.21
Route::put('/movement/{movement}','MovementController@update')->name('movement.update');
//US.21
Route::delete('/movement/{movement}','MovementController@destroy')->name('movement.destroy');


//US23
Route::group(
    ['prefix'=>'documents',
    ],
    function(){
        //US23
        Route::get('{movement}','DocumentController@create')->name('documents.create');
        Route::post('{movement}','DocumentController@update')->name('documents.update');
    }
);

Route::group(
    ['prefix'=>'document',
    ],
    function(){
        //US.24
        Route::delete('{document}','DocumentController@destroy')->name('document.destroy');
        //US.25
        Route::get('{document}','DocumentController@show')->name('document.show');
    }
);

//US.26
Route::get('/dashboard/{user}','UserController@show')->name('dashboard');
//US.29
Route::post('/me/associates','UserController@createAssociate')->name('users.associate.create');
//US.30 ACRESCENTADO
Route::get('/me/associates/{user}', function(){
    return redirect()->route('users.associates');
})->name('users.show.associates');
//US.30
Route::delete('/me/associates/{user}','UserController@destroyAssociate')->name('users.associate.destroy');
