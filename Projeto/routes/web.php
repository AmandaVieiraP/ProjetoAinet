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


Route::group(
    ['middleware'=>'admin',
     'prefix'=>'users',
    ],
    function(){
        //US5, US6
        Route::get('/','UserController@listAllUsersToAdmin')->name('admin.users');
        //US7
        Route::patch('{user}/block', 'UserController@blockUser')->name('admin.users.block');
        Route::patch('{user}/unblock', 'UserController@unblockUser')->name('admin.users.unblock');
        Route::patch('{user}/promote', 'UserController@promoteUser')->name('admin.users.promote');
        Route::patch('{user}/demote', 'UserController@demoteUser')->name('admin.users.demote');
    }
);

Route::group(
    ['prefix'=>'me',
    ],
    function(){
        //US9
        Route::get('password','UserController@showChangePasswordForm')->name('me.passwordForm');
        Route::patch('password', 'UserController@changePassword')->name('me.password');
        //US10
        Route::get('profile','UserController@showEditMyProfileForm')->name('me.profileForm');
        Route::put('profile', 'UserController@updateMyProfile')->name('me.profile');
        //US12
        Route::get('associates', 'UserController@getAssociates')->name('me.associates');

        Route::get('accounts',function(){
            return redirect()->route('accounts.opened',Auth::id());
        })->name('me.accounts');

        //US13
        Route::get('associate-of','UserController@getAssociateOfMe')->name('me.associateOf');
    }
);


//US11
Route::get('/profiles', 'UserController@getProfile')->name('profiles');

Route::group(
    ['prefix'=>'me',
    ],
    function(){
        //US9
        Route::get('password','UserController@showChangePasswordForm')->name('me.passwordForm');
        Route::patch('password', 'UserController@changePassword')->name('me.password');
        //US10
        Route::get('profile','UserController@showEditMyProfileForm')->name('me.profileForm');
        Route::put('profile', 'UserController@updateMyProfile')->name('me.profile');

        Route::group(
            ['prefix'=>'associates',],
            function(){
                //US12
                Route::get('/', 'UserController@getAssociates')->name('me.associates');
                //US.29
                Route::post('/','UserController@create')->name('me.associates.create');
                //US.30
                Route::delete('/{user}','UserController@destroy')->name('me.associate.destroy');
            }
        );

        //US.26
        Route::get('dashboard','UserController@show')->name('me.dashboard');

        Route::get('accounts',function(){
            return redirect()->route('accounts.opened',Auth::id());
        })->name('me.accounts');

        //US13
        Route::get('associate-of','UserController@getAssociateOfMe')->name('me.associateOf');
    }
);

Route::group(
    ['prefix'=>'accounts',
    ],
    function(){
        //US14
        Route::get('{user}','AccountController@show')->name('accounts');
        Route::get('{user}/opened','AccountController@showOpenAccounts')->name('accounts.opened');
        Route::get('{user}/closed','AccountController@showCloseAccounts')->name('accounts.closed');
    }
);

Route::group(
    ['prefix'=>'account',
    ],
    function(){
        //US15
        Route::delete('{account}', 'AccountController@destroy')->name('account.delete');
        Route::get('{account}/close','AccountController@updateClose')->name('account.getClose');
        Route::patch('{account}/close','AccountController@updateClose')->name('account.close');
        //US16
        Route::patch('{account}/reopen','AccountController@updateReopen')->name('account.reopen');
        //US17
        Route::get('/','AccountController@create')->name('account.create');
        Route::post('/','AccountController@store')->name('account.store');
        //US18
        Route::get('{account}','AccountController@edit')->name('account.edit');/*->middleware('can:edit-account,account_id');*/
        Route::put('{account}','AccountController@update')->name('account.update');
    }
);

Route::group(
    ['prefix'=>'movements',
    ],
    function(){
        //US.20
        Route::get('{account}','MovementController@index')->name('movements.index');
        //US.21
        Route::get('{account}/create','MovementController@create')->name('movements.create');
        //US.21
        Route::post('{account}/create','MovementController@store')->name('movements.store');
        //US.21
        Route::get('{account}/{movement}','MovementController@edit')->name('movements.edit');
        //US.21
        Route::put('{account}/{movement}','MovementController@update')->name('movements.update');
        //US.21
        Route::delete('{account}/{movement}','MovementController@destroy')->name('movements.destroy');
    }
);

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