<?php
$this->group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    $this->get('/', function () {
        return redirect('/admin/home');
    });

    // Admin Authentication Routes
    $this->get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\AdminLoginController@login')->name('login');
    $this->post('logout', 'Auth\AdminLoginController@logout')->name('logout');

    $dashboardOptions = [
        'middleware' => ['auth:admin'],
    ];
    $this->group($dashboardOptions, function () {

        $this->get('home', 'AdminHomeController@index');

        // Tenants Routes
        $this->resource('tenants', 'Admin\TenantController');

        // Admins Routes
        $this->resource('admins', 'Admin\AdminsController');
        $this->post('admins_mass_destroy', 'Admin\AdminsController@massDestroy')->name('admins.mass_destroy');

        // Roles Routes
        $this->resource('roles', 'Admin\RolesController');
        $this->post('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');

        // Abilities Routes
        $this->resource('abilities', 'Admin\AbilitiesController');
        $this->post('abilities_mass_destroy', 'Admin\AbilitiesController@massDestroy')->name('abilities.mass_destroy');

        // Admin Change Password Routes
        $this->get('change_password', 'Auth\AdminChangePasswordController@showChangePasswordForm')
            ->name('change_password');
        $this->patch('change_password', 'Auth\AdminChangePasswordController@changePassword')->name('change_password');
    });

    // Admin Password Reset Routes
    $this->get('password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.reset');
    $this->get('password/reset/{email}/{token}', 'Auth\AdminResetPasswordController@showResetForm')
        ->name('password.restore');
    $this->post('password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->post('password/reset', 'Auth\AdminResetPasswordController@reset')->name('password.reset');
});
