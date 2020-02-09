<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTest extends AdminControllerTestCase
{
    protected $updateAdminsRequest;

    public function setup()
    {
        parent::setup();
        $this->updateAdminsRequest = Mockery::mock('\Monica\Http\Requests\Admin\UpdateAdminsRequest');
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
        $this->admin->shouldReceive('findOrFail', 'retract', 'assign', 'fill')->andReturnSelf();
        $this->updateAdminsRequest->shouldReceive('all')->andReturn($userData);
        $this->admin->shouldReceive('save', 'getAttribute')->andReturnSelf();
        $this->updateAdminsRequest->shouldReceive('input')->andReturn($roleIds);

        $result = $this->adminController->update($this->updateAdminsRequest, 'test', 1);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenUpdate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->update($this->updateAdminsRequest, 'test', 1);
    }

    public function testUpdateWithNonExistentTenant()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->admin->shouldReceive('findOrFail')
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(ModelNotFoundException::class);

        $result = $this->adminController->update($this->updateAdminsRequest, 'no-domain', 1);
    }
}
