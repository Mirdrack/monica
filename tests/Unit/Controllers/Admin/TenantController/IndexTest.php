<?php

namespace Tests\Unit\Controllers\Admin\TenantController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexTest extends TenantControllerTestCase
{
    public function testSuccessfulIndex()
    {
        $collection = Mockery::mock('\Illuminate\Database\Eloquent\Collection');
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenantService->shouldReceive('getAll')
            ->once()->andReturn($collection);

        $result = $this->tenantController->index();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.tenants.index', $result->getName());
        $this->assertArrayHasKey('tenants', $result->getData());
    }

    public function testForbiddenIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->tenantController->index();
    }
}
