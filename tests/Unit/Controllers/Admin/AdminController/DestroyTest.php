<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestroyTest extends AdminControllerTestCase
{
    public function testSuccessfulDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->admin->shouldReceive('findOrFail')->andReturnSelf();
        $this->admin->shouldReceive('delete')->andReturn(true);

        $result = $this->adminController->destroy('test-tenant', 1);

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->destroy('test', 1);
    }
}
