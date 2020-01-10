<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyTest extends UserControllerTestCase
{
    public function testSuccessfulDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->user->shouldReceive('findOrFail')->andReturnSelf();
        $this->user->shouldReceive('delete')->andReturn(true);

        $result = $this->userController->destroy('test-tenant', 1);

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->destroy('test', 1);
    }

    // public function testDestroyWithNonExistentTenant()
    // {
    //     $this->gate->shouldReceive('allows')
    //         ->andReturn(true);
    //     $this->tenant->shouldReceive('where')
    //         ->once()->andReturnSelf();
    //     $this->tenant->shouldReceive('first')
    //         ->once()->andReturn(null);

    //     $this->expectException(NotFoundHttpException::class);

    //     $result = $this->userController->edit('test', 1);
    // }
}
