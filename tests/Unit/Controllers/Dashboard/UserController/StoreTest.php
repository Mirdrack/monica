<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreTest extends UserControllerTestCase
{
    protected $storeUsersRequest;

    public function setup()
    {
        parent::setup();
        $this->storeUsersRequest = Mockery::mock('\Monica\Http\Requests\User\StoreUsersRequest');
    }

    public function testSuccessfulStore()
    {
        $userData = [
            'tenant_id' => 1,
            'name' => 'testuser',
            'email' => 'email@user.com',
            'password' => 'supersecret',
        ];
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where', 'first')
            ->once()->andReturnSelf();
        $this->storeUsersRequest->shouldReceive('all')->andReturn($this->user);
        $this->user->shouldReceive('create')->andReturnSelf();
        $this->storeUsersRequest->shouldReceive('input')->andReturn($userData);
        $this->user->shouldReceive('assign')->andReturnSelf();

        $result = $this->userController->store($this->storeUsersRequest, 'test');
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenStore()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->store($this->storeUsersRequest, 'test-domain');
    }

    public function testStoreWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->tenant->shouldReceive('where')
            ->once()->andReturnSelf();
        $this->tenant->shouldReceive('first')
            ->once()->andReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $result = $this->userController->store($this->storeUsersRequest, 'no-domain');
    }
}
