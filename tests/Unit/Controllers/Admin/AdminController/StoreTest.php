<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreTest extends AdminControllerTestCase
{
    protected $storeAdminsRequest;

    public function setup()
    {
        parent::setup();
        $this->storeAdminsRequest = Mockery::mock('\Monica\Http\Requests\Admin\StoreAdminsRequest');
    }

    public function testSuccessfulStore()
    {
        $adminData = [
            'tenant_id' => 1,
            'name' => 'testuser',
            'email' => 'email@admin.com',
            'password' => 'supersecret',
        ];
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->storeAdminsRequest->shouldReceive('all')->andReturn($adminData);
        $this->admin->shouldReceive('create')->andReturnSelf();
        $this->storeAdminsRequest->shouldReceive('input')->andReturn($adminData);
        $this->admin->shouldReceive('assign')->andReturnSelf();

        $result = $this->adminController->store($this->storeAdminsRequest, 'test');
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenStore()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->adminController->store($this->storeAdminsRequest, 'test-domain');
    }
}
