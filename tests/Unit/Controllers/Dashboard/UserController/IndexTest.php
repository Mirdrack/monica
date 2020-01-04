<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexTest extends UserControllerTestCase
{
    public function testSuccessfulIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where', 'first', 'getAttribute')
            ->once()->andReturnSelf();
        $this->user->shouldReceive('with', 'where', 'get')
            ->once()->andReturnSelf();

        $result = $this->userController->index('test');

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('dashboard.users.index', $result->getName());
        $this->assertArrayHasKey('users', $result->getData());
        $this->assertArrayHasKey('tenant', $result->getData());
    }

    public function testForbiddenIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->index('test');
    }

    public function testIndexWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where')
            ->once()->andReturnSelf();
        $this->tenant->shouldReceive('first')
            ->once()->andReturn(null);

        $this->expectException(HttpException::class);

        $result = $this->userController->index('test');
    }
}
