<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Mockery;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateTest extends AbilityControllerTestCase
{
    protected $updateAbilitiesRequest;

    public function setup()
    {
        parent::setup();
        $this->updateAbilitiesRequest = Mockery::mock('\Monica\Http\Requests\Admin\UpdateAbilitiesRequest');
    }

    public function testSuccessfulUpdate()
    {
        $abilityData = [
            'title' => 'Test Title',
        ];
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->ability->shouldReceive('findOrFail')->andReturnSelf();
        $this->updateAbilitiesRequest->shouldReceive('all')->andReturn($abilityData);
        $this->ability->shouldReceive('update')->andReturnSelf();

        $result = $this->abilityController->update($this->updateAbilitiesRequest, 1);
        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenUpdate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->update($this->updateAbilitiesRequest, 1);
    }

    public function testUpdateWithNonExistentAbility()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->ability->shouldReceive('findOrFail')
            ->andThrow(ModelNotFoundException::class);

        $this->expectException(ModelNotFoundException::class);

        $result = $this->abilityController->update($this->updateAbilitiesRequest, 10);
    }
}
