<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditTest extends AdminControllerTestCase
{
    public function testSuccessfulRenderEdit()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('get')->andReturnSelf();
        $this->role->shouldReceive('pluck')->andReturn(['Admin' => 'admin']);
        $this->admin->shouldReceive('findOrFail')->andReturn($this->admin);

        $result = $this->adminController->edit('test-tenant', 1);

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.admins.edit', $result->getName());
        $this->assertArrayHasKey('admin', $result->getData());
        $this->assertArrayHasKey('roles', $result->getData());
    }

    public function testForbiddenRenderEdit()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->edit('test', 1);
    }
}
