<?php
use Illuminate\Database\Seeder;
use Monica\Models\Admin;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'user',
            'email' => 'admin@admin.com',
            'password' => 'secret'
        ]);
        $admin->assign('administrator');
    }
}
