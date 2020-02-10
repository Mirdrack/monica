<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTest extends RoleControllerTestCase
{
    protected $updateRolesRequest;

    public function setup()
    {
        parent::setup();
        $this->updateRolesRequest = Mockery::mock('\Monica\Http\Requests\Admin\UpdateRolesRequest');
    }

    public function testSuccessfulUpdate()
    {
        $roleData = [
            'title' => 'Test Title',
        ];
        $collection = Mockery::mock('Illuminate\Database\Eloquent\Collection');
        $arrayIterator = Mockery::mock('ArrayIterator');
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('findOrFail')->andReturnSelf();
        $this->updateRolesRequest->shouldReceive('all')->andReturn($roleData);
        $this->role->shouldReceive('update')->andReturnSelf();
        $this->role->shouldReceive('getAbilities')
            ->andReturn($collection);
        $collection->shouldReceive('getIterator')
            ->andReturn($arrayIterator);
        $arrayIterator->shouldReceive('rewind')
            ->andReturn(null);
        $arrayIterator->shouldReceive('valid')
            ->andReturn(true, true, false); // This represents a role with two abilities
        $arrayIterator->shouldReceive('current')
            ->andReturn($this->ability);
        $this->ability->shouldReceive('getAttribute')
            ->andReturnSelf();
        $this->role->shouldReceive('disallow', 'allow')
            ->andReturn(true);
        $arrayIterator->shouldReceive('next')
            ->andReturn(null);
        $this->updateRolesRequest->shouldReceive('input')
            ->andReturn('test-test');

        $result = $this->roleController->update($this->updateRolesRequest, 'test', 1);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenUpdate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->roleController->update($this->updateRolesRequest, 1);
    }

    public function testUpdateWithNonExistentRole()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->role->shouldReceive('findOrFail')
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(ModelNotFoundException::class);

        $result = $this->roleController->update($this->updateRolesRequest, 10);
    }
}
