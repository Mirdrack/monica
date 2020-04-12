<?php

namespace Tests\Unit\Controllers\AdminHomeController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\AdminHomeController;

class AdminHomeControllerTestCase extends TestCase
{
    protected $adminHomeController;

    public function setup()
    {
        parent::setUp();
        $this->adminHomeController = new AdminHomeController();
    }
}
