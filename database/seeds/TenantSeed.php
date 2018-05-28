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
        $aetech = [
            'name' => 'AE Techonologies',
            'subdomain' => 'aetech',
        ];
        $cmotion = [
            'name' => 'C Motion',
            'subdomain' => 'cmotion',
        ];
        Tenant::create($aetech);
        Tenant::create($cmotion);
    }
}