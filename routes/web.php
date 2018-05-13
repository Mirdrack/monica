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

Route::get('/', function () { return redirect('/dashboard/home'); });
Route::get('/admin', function () { return redirect('/admin/home'); });

// Admin Authentication Routes...
$this->get('admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
$this->post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
$this->post('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('user.login');
$this->post('login', 'Auth\LoginController@login')->name('user.login');
$this->post('logout', 'Auth\LoginController@logout')->name('user.logout');

// Admin Change Password Routes...
$this->get('admin/change_password', 'Auth\AdminChangePasswordController@showChangePasswordForm')->name('admin.change_password');
$this->patch('admin/change_password', 'Auth\AdminChangePasswordController@changePassword')->name('admin.change_password');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Admin Password Reset Routes...
$this->get('admin/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
$this->get('admin/password/reset/{email}/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.restore');
$this->post('admin/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
$this->post('admin/password/reset', 'Auth\AdminResetPasswordController@reset')->name('admin.password.reset');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'AdminHomeController@index');
    Route::resource('abilities', 'Admin\AbilitiesController');
    Route::post('abilities_mass_destroy', ['uses' => 'Admin\AbilitiesController@massDestroy', 'as' => 'abilities.mass_destroy']);
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('admins', 'Admin\AdminsController');
    Route::post('admins_mass_destroy', ['uses' => 'Admin\AdminsController@massDestroy', 'as' => 'admins.mass_destroy']);
});

Route::group([/*'middleware' => ['auth.web'],*/ 'prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/home', 'HomeController@index');
    // Route::resource('abilities', 'Admin\AbilitiesController');
    // Route::post('abilities_mass_destroy', ['uses' => 'Admin\AbilitiesController@massDestroy', 'as' => 'abilities.mass_destroy']);
    // Route::resource('roles', 'Admin\RolesController');
    // Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Dashboard\UsersController');
    Route::post('users_mass_destroy', ['uses' => 'Dashboard\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
});
