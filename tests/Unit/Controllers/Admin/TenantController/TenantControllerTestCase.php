<?php

namespace Tests\Unit\Controllers\Admin\TenantController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\Admin\TenantController;

class TenantControllerTestCase extends TestCase
{
    protected $tenantController;

    protected $auth;

    protected $gate;

    protected $tenantService;

    public function setup()
    {
        parent::setup();

        $this->auth = Mockery::mock('Illuminate\Auth\AuthManager');
        $this->gate = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->tenantService = Mockery::mock('Monica\Services\Admin\TenantService');

        $this->auth->shouldReceive('shouldUse')
            ->andReturn(null);

        $this->tenantController = new TenantController(
            $this->auth,
            $this->gate,
            $this->tenantService
        );
    }
}
