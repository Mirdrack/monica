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

$this->get('/', function () {
    return view('welcome');
})->name('site-route');

$this->group(['domain' => '{subdomain}.'.getenv('APP_DOMAIN')], function () {

    // Authentication Routes
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
    $this->post('login', 'Auth\LoginController@login')->name('user.login');
    $this->post('logout', 'Auth\LoginController@logout')->name('user.logout');

    $dashboardOptions = [
        'prefix' => 'dashboard',
        'middleware' => ['auth:web'],
        'as' => 'dashboard.'
    ];
    $this->group($dashboardOptions, function () {

        $this->get('home', 'HomeController@index')->name('home');

        // Users routes
        $this->resource('users', 'Dashboard\UserController');
        $this->post('users_mass_destroy', 'Dashboard\UserController@massDestroy')->name('users.mass_destroy');

        // Change Password Routes
        $this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('change_password');
        $this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('change_password');
    });

    // Password Reset Routes
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
    $this->get('password/reset/{email}/{token}', 'Auth\ResetPasswordController@showResetForm')
        ->name('auth.password.restore');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.email');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');
});




Route::group([/*'middleware' => ['auth.web'],*/ 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    // $this->resource('roles', 'Dashboard\RoleController');
    // $this->post('roles_mass_destroy', ['uses' => 'Admin\RoleController@massDestroy', 'as' => 'roles.mass_destroy']);
});
