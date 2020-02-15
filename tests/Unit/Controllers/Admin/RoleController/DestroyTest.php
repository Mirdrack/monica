<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DestroyTest extends RoleControllerTestCase
{
    public function testSuccessfulDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('findOrFail')->andReturnSelf();
        $this->role->shouldReceive('delete')->andReturn(true);

        $result = $this->roleController->destroy(1);

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->destroy(1);
    }
}
