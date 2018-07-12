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
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'System Admin',
            'type' => 'system',
        ]);

        $tenantAdmin = Bouncer::role()->firstOrCreate([
            'name' => 'tenant_admin',
            'title' => 'Tenant Admin',
            'type' => 'tenant',
        ]);

        Bouncer::allow($admin)->to('admins_manage');
        Bouncer::allow($tenantAdmin)->to('users_manage');
    }
}
