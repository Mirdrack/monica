<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Symfony\Component\HttpKernel\Exception\HttpException;

class EditTest extends AbilityControllerTestCase
{
    public function testSuccessfulRenderEdit()
    {
        $this->gate->shouldReceive('allows')->andReturn(true);
        $this->ability->shouldReceive('findOrFail')->andReturnSelf();

        $result = $this->abilityController->edit(1);

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.abilities.edit', $result->getName());
        $this->assertArrayHasKey('ability', $result->getData());
    }

    public function testForbiddenRenderEdit()
    {
        $this->gate->shouldReceive('allows')->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->edit(1);
    }
}
