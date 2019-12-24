<?php

return [

    'admin-management' => [
        'title' => 'Admin Management',
        'created_at' => 'Time',
        'fields' => [
        ],
    ],
    
    'user-management' => [
        'title' => 'User Management',
        'created_at' => 'Time',
        'fields' => [
        ],
    ],
    
    'abilities' => [
        'title' => 'Abilities',
        'created_at' => 'Time',
        'fields' => [
            'name' => 'Name',
        ],
    ],
    
    'roles' => [
        'title' => 'Roles',
        'created_at' => 'Time',
        'fields' => [
            'name' => 'Name',
            'abilities' => 'Abilities',
        ],
    ],

    'admins' => [
        'title' => 'Admins',
        'created_at' => 'Time',
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'roles' => 'Roles',
            'remember-token' => 'Remember token',
        ],
    ],

    'tenants' => [
        'title' => 'Tenants',
        'created_at' => 'Time',
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
        ],
    ],
    
    'users' => [
        'title' => 'Users',
        'created_at' => 'Time',
        'fields' => [
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'roles' => 'Roles',
            'remember-token' => 'Remember token',
        ],
    ],
    'app_create' => 'Create',
    'app_save' => 'Save',
    'app_edit' => 'Edit',
    'app_view' => 'View',
    'app_update' => 'Update',
    'app_list' => 'List',
    'app_no_entries_in_table' => 'No entries in table',
    'custom_controller_index' => 'Custom controller index.',
    'app_logout' => 'Logout',
    'app_add_new' => 'Add new',
    'app_are_you_sure' => 'Are you sure?',
    'app_back_to_list' => 'Back to list',
    'app_dashboard' => 'Dashboard',
    'app_delete' => 'Delete',
    'global_title' => 'App Dashboard',
    'admin_dashboard_title' => 'Admin Dashboard',
];
