<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MassDestroyTest extends AdminControllerTestCase
{
    public function testSuccessfulMassDestroy()
    {
        $expectedDeletedIds = 2;
        $request = Mockery::mock('Illuminate\Http\Request');
        $collection = Mockery::mock('Illuminate\Database\Eloquent\Collection');
        $arrayIterator = Mockery::mock('ArrayIterator');

        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $request->shouldReceive('input')->with('ids')
            ->andReturn(1, 2); // This numbers express the ids to delete

        $this->admin->shouldReceive('whereIn')
            ->once()->andReturnSelf();
        $this->admin->shouldReceive('get')
            ->andReturn($collection);
        $collection->shouldReceive('getIterator')
            ->andReturn($arrayIterator);
        $arrayIterator->shouldReceive('rewind')
            ->andReturn(null);
        $arrayIterator->shouldReceive('valid')
            ->andReturn(true, true, false); // This should match with the number of ids to delete
        $collection->shouldReceive('count')
            ->andReturn($expectedDeletedIds);
        $arrayIterator->shouldReceive('current')
            ->andReturn($this->admin);
        $arrayIterator->shouldReceive('next')
            ->andReturn(null);

        $this->admin->shouldReceive('delete')
            ->andReturn(true);

        $result = $this->adminController->massDestroy($request);

        $this->assertEquals($expectedDeletedIds, $result);
    }

    public function testMassDestroyZeroIdsInTheRequest()
    {
        $expectedDeletedIds = 0;
        $request = Mockery::mock('Illuminate\Http\Request');
        $collection = Mockery::mock('Illuminate\Database\Eloquent\Collection');
        $arrayIterator = Mockery::mock('ArrayIterator');

        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $request->shouldReceive('input')->with('ids')
            ->andReturn(null); // This numbers express the ids to delete

        $result = $this->adminController->massDestroy($request);

        $this->assertEquals($expectedDeletedIds, $result);
    }

    public function testMassDestroyWithUknownIds()
    {
        $expectedDeletedIds = 0;
        $request = Mockery::mock('Illuminate\Http\Request');
        $collection = Mockery::mock('Illuminate\Database\Eloquent\Collection');
        $arrayIterator = Mockery::mock('ArrayIterator');

        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $request->shouldReceive('input')->with('ids')
            ->andReturn(59, 83); // This numbers express the ids to delete

        $this->admin->shouldReceive('whereIn')
            ->once()->andReturnSelf();
        $this->admin->shouldReceive('get')
            ->andReturn($collection);
        $collection->shouldReceive('getIterator')
            ->andReturn($arrayIterator);
        $arrayIterator->shouldReceive('rewind')
            ->andReturn(null);
        $arrayIterator->shouldReceive('valid')
            ->andReturn(false); // This should match with the number of ids to delete
        $collection->shouldReceive('count')
            ->andReturn($expectedDeletedIds);
        $arrayIterator->shouldReceive('current')
            ->andReturn($this->admin);
        $arrayIterator->shouldReceive('next')
            ->andReturn(null);

        $this->admin->shouldReceive('delete')
            ->andReturn(true);

        $result = $this->adminController->massDestroy($request);

        $this->assertEquals($expectedDeletedIds, $result);
    }

    public function testForbiddenMassDestroy()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);
        $request = Mockery::mock('Illuminate\Http\Request');
        $request->shouldReceive('input')->with('ids')
            ->andReturn(1, 2);

        $this->expectException(HttpException::class);

        $this->adminController->massDestroy($request);
    }
}
