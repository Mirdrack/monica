<?php

namespace Tests\Unit\Services\Admin\TenantService;

use Mockery;
use Tests\TestCase;
use Monica\Services\Admin\TenantService;

class TenantServiceTestCase extends TestCase
{
    protected $tenantService;

    protected $dbManager;

    protected $tenant;

    protected $user;

    public function setup()
    {
        $this->dbManager = Mockery::mock('Illuminate\Database\DatabaseManager');
        $this->tenant = Mockery::mock('Monica\Models\Tenant');
        $this->user = Mockery::mock('Monica\Models\User');
        $this->tenantService = new TenantService($this->dbManager, $this->tenant, $this->user);
    }
}
