<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateTest extends AbilityControllerTestCase
{
    public function testSuccessfulRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);

        $result = $this->abilityController->create();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.abilities.create', $result->getName());
    }

    public function testForbiddenRenderCreate()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->create();
    }
}
