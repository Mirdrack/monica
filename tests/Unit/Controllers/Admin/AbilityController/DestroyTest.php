<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DestroyTest extends AbilityControllerTestCase
{
    public function testSuccessfulDestroy()
    {
        $this->gate->shouldReceive('allows')->andReturn(true);
        $this->ability->shouldReceive('findOrFail')->andReturnSelf();
        $this->ability->shouldReceive('delete')->andReturn(true);

        $result = $this->abilityController->destroy(1);

        $this->assertInstanceOf('Illuminate\Http\RedirectResponse', $result);
    }

    public function testForbiddenDestroy()
    {
        $this->gate->shouldReceive('allows')->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->destroy(1);
    }
}
