<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateTest extends RoleControllerTestCase
{
    public function testSuccessfulRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->ability->shouldReceive('get')->andReturnSelf();
        $this->ability->shouldReceive('pluck')->andReturn(['Add' => 'add']);

        $result = $this->roleController->create();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.roles.create', $result->getName());
        $this->assertArrayHasKey('abilities', $result->getData());
    }

    public function testForbiddenRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->create();
    }
}
