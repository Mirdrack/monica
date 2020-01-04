<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexTest extends RoleControllerTestCase
{
    public function testSuccessfulIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('all')
            ->once()->andReturnSelf();

        $result = $this->roleController->index();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.roles.index', $result->getName());
        $this->assertArrayHasKey('roles', $result->getData());
    }

    public function testForbiddenIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->index();
    }
}
