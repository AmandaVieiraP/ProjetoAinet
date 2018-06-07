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
Route::get('/', 'Controller@initialPage')->name('initialPage');

//US2, US3, US4
Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::get('/home', 'HomeController@index')->name('home');

Route::group(
    ['prefix'=>'users',
      'middleware' => 'admin',
    ],
    function () {
        //US5, US6
        Route::get('/', 'UserController@listAllUsersToAdmin')->name('list.of.all.users');
        //US7
        Route::get('{user}/block', 'UserController@blockUser')->name('get.block.user');
        Route::patch('{user}/block', 'UserController@blockUser')->name('block.user');
        Route::get('{user}/unblock', 'UserController@unblockUser')->name('get.unblock.user');
        Route::patch('{user}/unblock', 'UserController@unblockUser')->name('unblock.user');
        Route::get('{user}/promote', 'UserController@promoteUser')->name('get.promote.user');
        Route::patch('{user}/promote', 'UserController@promoteUser')->name('promote.user');
        Route::get('{user}/demote', 'UserController@demoteUser')->name('get.demote.user');
        Route::patch('{user}/demote', 'UserController@demoteUser')->name('demote.user');
    }
);


Route::group(
    ['prefix'=>'me',
    ],
    function () {
        //US9
        Route::get('password', 'UserController@showChangePasswordForm')->name('me.passwordForm');
        Route::patch('password', 'UserController@changePassword')->name('me.password');
        //US10
        Route::get('profile', 'UserController@showEditMyProfileForm')->name('me.profileForm');
        Route::put('profile', 'UserController@updateMyProfile')->name('me.profile');
        //US12
        Route::get('associates', 'UserController@getAssociates')->name('users.associates');
        //US13
        Route::get('associate-of', 'UserController@getAssociateOfMe')->name('me.associateOf');

        Route::get('accounts', function () {
            return redirect()->route('accounts.opened', Auth::id());
        })->name('my.accounts');
        //US.29
        Route::get('newAssociate', 'UserController@getCreateAssociate')->name('get.createAssociate');
        Route::post('associates', 'UserController@createAssociate')->name('me.createAssociate');

        //US.30
        Route::get('associates/{user}', function () {
            return redirect()->route('users.associates');
        })->name('users.show.associates');
        Route::delete('associates/{user}', 'UserController@destroyAssociate')->name('users.associate.destroy');
    }
);



//US11
Route::get('/profiles', 'UserController@getProfiles')->name('users.profiles');


Route::group(
    ['prefix'=>'accounts',
    ],
    function () {
        Route::get('{user}', 'AccountController@showAccounts')->name('accounts');
        Route::get('{user}/opened', 'AccountController@showOpenAccounts')->name('accounts.opened');
        Route::get('{user}/closed', 'AccountController@showCloseAccounts')->name('accounts.closed');
    }
);

Route::group(
    ['prefix'=>'account',
    ],
    function () {
        //US.14
        Route::delete('{account}', 'AccountController@destroy')->name('account.delete');
        Route::get('{account}/close', 'AccountController@updateClose')->name('account.close');
        Route::patch('{account}/close', 'AccountController@updateClose')->name('account.close');
        Route::patch('{account}/reopen', 'AccountController@updateReopen')->name('account.reopen');
        //US17
        Route::get('/', 'AccountController@create')->name('account.create');
        Route::post('/', 'AccountController@store')->name('account.store');
        //US18
        Route::get('{account}', 'AccountController@edit')->name('account.edit');
        Route::put('{account}', 'AccountController@update')->name('account.update');
    }
);

Route::group(
    ['prefix'=>'movements',
    ],
    function () {
        //US.20
        Route::get('{account}', 'MovementController@index')->name('movement.index');
        //US.21
        Route::get('{account}/create', 'MovementController@create')->name('movement.create');
        //US.21
        Route::post('{account}/create', 'MovementController@store')->name('movement.store');
    }
);

Route::group(
    ['prefix'=>'movement',
    ],
    function () {
        //US.21
        Route::get('{movement}', 'MovementController@edit')->name('movement.edit');
        //US.21
        Route::put('{movement}', 'MovementController@update')->name('movement.update');
        //US.21
        Route::delete('{movement}', 'MovementController@destroy')->name('movement.destroy');
    }
);

//US23
Route::group(
    ['prefix'=>'documents',
    ],
    function () {
        //US23
        Route::get('{movement}', 'DocumentController@create')->name('documents.create');
        Route::post('{movement}', 'DocumentController@update')->name('documents.update');
    }
);

Route::group(
    ['prefix'=>'document',
    ],
    function () {
        //US.24
        Route::delete('{document}', 'DocumentController@destroy')->name('document.destroy');
        //US.25
        Route::get('{document}', 'DocumentController@show')->name('document.show');
    }
);

Route::group(
    ['prefix'=>'dashboard',
    ],
    function () {
        //US.26
        Route::get('{user}', 'UserController@show')->name('dashboard');
        //US27
        Route::get('{user}/expenses_revenues', 'ChartsController@showTotalExpensesAndRevenues')->name('user.totalExpensesRevenues');
        //US28
        Route::get('{user}/evolution', 'ChartsController@showMonthlyEvolution')->name('user.evolutionExpensesRevenues');
    }
);
