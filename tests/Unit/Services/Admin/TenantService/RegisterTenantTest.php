<?php

namespace Tests\Unit\Services\Admin\TenantService;

use Tests\TestCase;
use Exception;

class RegisterTenantTest extends TenantServiceTestCase
{
    public function testSuccessfulRegister()
    {
        $data = [
            'name' => 'tenantName',
            'subdomain' => 'tenantSubdomain',
            'email' => 'admin@tenant.com',
        ];

        $this->dbManager->shouldReceive('beginTransaction', 'commit')
            ->andReturn(null);

        $this->tenant->shouldReceive('create', 'getAttribute')
            ->andReturn($this->tenant);

        $this->user->shouldReceive('create', 'getAttribute', 'assign')
            ->andReturn($this->user);

        $result = $this->tenantService->registerTenant($data);
        $this->assertTrue($result);
    }

    public function testFailedTransaction()
    {
        $data = [
            'name' => 'tenantName',
            'subdomain' => 'tenantSubdomain',
            'email' => 'admin@tenant.com',
        ];

        $this->dbManager->shouldReceive('rollback')
            ->andReturn(null);

        $this->expectException(Exception::class);
        $result = $this->tenantService->registerTenant($data);
    }
}
