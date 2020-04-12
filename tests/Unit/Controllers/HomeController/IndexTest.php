<?php

namespace Tests\Unit\Controllers\HomeController;

use Mockery;
use Symfony\Component\HttpFoundation\Response;

class IndexTest extends HomeControllerTestCase
{
    public function testIndex()
    {
        $this->tenant->shouldReceive('where', 'first')
            ->andReturn($this->tenant);

        $result = $this->homeController->index('test');

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('home', $result->getName());
    }

    public function testIndexWithNonExistentTenant()
    {
        $this->tenant->shouldReceive('where')
            ->andReturn($this->tenant);
        $this->tenant->shouldReceive('first')
            ->andReturn(null);

        $result = $this->homeController->index('failing-test');

        $this->assertInstanceOf('Illuminate\Http\Response', $result);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $result->getStatusCode());
    }
}
