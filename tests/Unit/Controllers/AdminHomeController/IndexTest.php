<?php

namespace Tests\Unit\Http\Controllers\AdminHomeController;

use Mockery;
use Symfony\Component\HttpFoundation\Response;

class IndexTest extends AdminHomeControllerTestCase
{
    public function testIndex()
    {
        $result = $this->adminHomeController->index();

        $this->assertInstanceOf('Illuminate\View\View', $result);
        $this->assertEquals('admin-home', $result->getName());
    }
}
