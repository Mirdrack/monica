<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreTest extends RoleControllerTestCase
{
    protected $storeRolesRequest;

    public function setup()
    {
        parent::setup();
        $this->storeRolesRequest = Mockery::mock('\Monica\Http\Requests\Admin\StoreRolesRequest');
    }

    public function testSuccessfulStore()
    {
        $roleData = [
            'name' => 'test-role',
            'tile' => 'Test Role',
        ];
        $abilities = ['ability-1', 'ability-2'];
        $this->gate->shouldReceive('allows')->andReturn(true);
        $this->storeRolesRequest->shouldReceive('all')->andReturn($roleData);
        $this->role->shouldReceive('create')->andReturnSelf();
        $this->storeRolesRequest->shouldReceive('input')->andReturn($abilities);
        $this->role->shouldReceive('allow')->andReturn(true);

        $result = $this->roleController->store($this->storeRolesRequest);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenStore()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->store($this->storeRolesRequest);
    }
}
