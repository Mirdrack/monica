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

Route::get('/', function () { return view('welcome'); });

Route::group(['domain' => '{tenant}.'.getenv('APP_DOMAIN')], function () {
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
    Route::post('login', 'Auth\LoginController@login')->name('user.login');
    Route::post('logout', 'Auth\LoginController@logout')->name('user.logout');    
});

// Authentication Routes...
// $this->get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
// $this->post('login', 'Auth\LoginController@login')->name('user.login');
// $this->post('logout', 'Auth\LoginController@logout')->name('user.logout');



// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('dashboard.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('dashboard.change_password');



// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');


Route::group([/*'middleware' => ['auth.web'],*/ 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/home', 'HomeController@index');
    // Route::resource('roles', 'Dashboard\RolesController');
    // Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Dashboard\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Dashboard\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
});
