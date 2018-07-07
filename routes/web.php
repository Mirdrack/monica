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

$this->get('/', function () { return view('welcome'); })->name('site-route');

$this->group(['domain' => '{subdomain}.'.getenv('APP_DOMAIN')], function () {

    // Authentication Routes
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
    $this->post('login', 'Auth\LoginController@login')->name('user.login');
    $this->post('logout', 'Auth\LoginController@logout')->name('user.logout');

    $this->group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {

        $this->get('home', 'HomeController@index')->name('home');

        // Users routes
        $this->resource('users', 'Dashboard\UsersController');
        $this->post('users_mass_destroy', ['uses' => 'Dashboard\UsersController@massDestroy', 'as' => 'users.mass_destroy']);

        // Change Password Routes
        $this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('change_password');
        $this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('change_password');
    });    
});


// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');


Route::group([/*'middleware' => ['auth.web'],*/ 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        // $this->resource('roles', 'Dashboard\RolesController');
    // Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
});
