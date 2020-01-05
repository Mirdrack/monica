<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IndexTest extends AbilityControllerTestCase
{
    public function testSuccessfulIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(true);
        $this->ability->shouldReceive('all')
            ->once()->andReturnSelf();

        $result = $this->abilityController->index();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin.abilities.index', $result->getName());
        $this->assertArrayHasKey('abilities', $result->getData());
    }

    public function testForbiddenIndex()
    {
        $this->gate->shouldReceive('allows')
            ->andReturn(false);

        $this->expectException(HttpException::class);

        $this->abilityController->index();
    }
}
