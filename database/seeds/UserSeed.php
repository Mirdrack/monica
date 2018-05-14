<?php

use Illuminate\Database\Seeder;
use Monica\Models\User;
use Monica\Models\Tenant;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tenantId = Tenant::first()->id;
        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => 'secret'
        ]);
        $user->assign('tenant_admin');
    }
}