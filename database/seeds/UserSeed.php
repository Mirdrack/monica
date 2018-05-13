<?php

use Illuminate\Database\Seeder;
use Monica\Models\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'secret'
        ]);
        $user->assign('tenant_admin');
    }
}