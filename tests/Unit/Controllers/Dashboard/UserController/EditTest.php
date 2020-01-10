<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditTest extends UserControllerTestCase
{
    public function testSuccessfulRenderEdit()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where', 'first')
            ->once()->andReturnSelf();
        $this->role->shouldReceive('get')->andReturnSelf();
        $this->role->shouldReceive('pluck')->andReturn(['Admin' => 'admin']);
        $this->user->shouldReceive('findOrFail')->andReturn($this->user);

        $result = $this->userController->edit('test-tenant', 1);

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('dashboard.users.edit', $result->getName());
        $this->assertArrayHasKey('user', $result->getData());
        $this->assertArrayHasKey('roles', $result->getData());
        $this->assertArrayHasKey('tenant', $result->getData());
    }

    public function testForbiddenRenderEdit()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->edit('test', 1);
    }

    public function testEditWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where')
            ->once()->andReturnSelf();
        $this->tenant->shouldReceive('first')
            ->once()->andReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $result = $this->userController->edit('test', 1);
    }
}
