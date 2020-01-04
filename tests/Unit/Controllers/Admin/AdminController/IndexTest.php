<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexTest extends AdminControllerTestCase
{
    public function testSuccessfulIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->admin->shouldReceive('with', 'get')
            ->once()->andReturnSelf();

        $result = $this->adminController->index();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.admins.index', $result->getName());
        $this->assertArrayHasKey('admins', $result->getData());
    }

    public function testForbiddenIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->index();
    }
}
