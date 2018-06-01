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
            'email' => 'user@aetech.com',
            'password' => 'secret'
        ]);
        $user->assign('tenant_admin');

        $tenantId = Tenant::orderBy('created_at', 'asc')->get()->last()->id;
        $user = User::create([
            'tenant_id' => $tenantId,
            'name' => 'user',
            'email' => 'user@cmotion.com',
            'password' => 'secret'
        ]);
        $user->assign('tenant_admin');
    }
}