<?php
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('administrator')->to('admins_manage');
        Bouncer::allow('tenant_admin')->to('users_manage');
    }
}