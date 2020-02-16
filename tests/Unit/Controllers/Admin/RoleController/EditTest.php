<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EditTest extends RoleControllerTestCase
{
    public function testSuccessfulRenderEdit()
    {
        $this->gate->shouldReceive('allows')->andReturn(true);
        $this->role->shouldReceive('get')->andReturnSelf();
        $this->ability->shouldReceive('get')->andReturnSelf();
        $this->ability->shouldReceive('pluck')->andReturn(['Add' => 'add']);
        $this->role->shouldReceive('findOrFail')->andReturnSelf();

        $result = $this->roleController->edit(1);

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.roles.edit', $result->getName());
        $this->assertArrayHasKey('role', $result->getData());
        $this->assertArrayHasKey('abilities', $result->getData());
    }

    public function testForbiddenRenderEdit()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->edit(1);
    }
}
