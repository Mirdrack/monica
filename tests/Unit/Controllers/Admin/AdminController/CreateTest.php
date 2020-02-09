<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateTest extends AdminControllerTestCase
{
    public function testSuccessfulRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('get')->andReturnSelf();
        $this->role->shouldReceive('pluck')->andReturn(['Admin' => 'admin']);

        $result = $this->adminController->create('test-tenant');

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.admins.create', $result->getName());
        $this->assertArrayHasKey('roles', $result->getData());
    }

    public function testForbiddenRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->create('test');
    }
}
