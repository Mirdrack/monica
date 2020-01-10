<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTest extends UserControllerTestCase
{
    protected $updateUsersRequest;

    public function setup()
    {
        parent::setup();
        $this->updateUsersRequest = Mockery::mock('\Monica\Http\Requests\User\UpdateUsersRequest');
    }

    public function testSuccessfulUpdate()
    {
        $userData = [
            'tenant_id' => 1,
            'name' => 'testuser',
            'email' => 'email@user.com',
            'password' => 'supersecret',
        ];
        $roleIds = [1, 2, 3];
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->user->shouldReceive('findOrFail', 'retract', 'assign')->andReturnSelf();
        $this->updateUsersRequest->shouldReceive('all')->andReturn($userData);
        $this->user->shouldReceive('update', 'getAttribute')->andReturnSelf();
        $this->updateUsersRequest->shouldReceive('input')->andReturn($roleIds);

        $result = $this->userController->update($this->updateUsersRequest, 'test', 1);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenUpdate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->userController->update($this->updateUsersRequest, 'test', 1);
    }

    public function testUpdateWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->user->shouldReceive('findOrFail')
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(ModelNotFoundException::class);

        $result = $this->userController->update($this->updateUsersRequest, 'no-domain', 1);
    }
}
