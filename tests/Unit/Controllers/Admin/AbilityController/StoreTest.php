<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreTest extends AbilityControllerTestCase
{
    protected $storeAbilitiesRequest;

    public function setup()
    {
        parent::setup();
        $this->storeAbilitiesRequest = Mockery::mock('\Monica\Http\Requests\Admin\StoreAbilitiesRequest');
    }

    public function testSuccessfulStore()
    {
        $this->gate->shouldReceive('allows')->andReturn(true);
        $this->storeAbilitiesRequest->shouldReceive('all')->andReturn($this->ability);
        $this->ability->shouldReceive('create')->andReturnSelf();

        $result = $this->abilityController->store($this->storeAbilitiesRequest);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenStore()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->store($this->storeAbilitiesRequest);
    }
}
