<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateTest extends UserControllerTestCase
{
    public function testSuccessfulRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where', 'first')
            ->once()->andReturnSelf();
        $this->role->shouldReceive('get')->andReturnSelf();
        $this->role->shouldReceive('pluck')->andReturn(['Admin' => 'admin']);

        $result = $this->userController->create('test-tenant');

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('dashboard.users.create', $result->getName());
        $this->assertArrayHasKey('roles', $result->getData());
        $this->assertArrayHasKey('tenant', $result->getData());
    }

    public function testForbiddenRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->create('test');
    }

    public function testCreateWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where')
            ->once()->andReturnSelf();
        $this->tenant->shouldReceive('first')
            ->once()->andReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $result = $this->userController->create('test');
    }
}
