<?php

use Illuminate\Database\Seeder;
use Monica\Models\Tenant;

class TenantSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::create([
            'name' => 'AE Techonologies',
        ]);
    }
}