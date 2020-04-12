<?php

namespace Tests\Unit\Controllers\HomeController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\HomeController;

class HomeControllerTestCase extends TestCase
{
    protected $tenant;

    protected $homeController;

    public function setup()
    {
        parent::setUp();
        $this->tenant = Mockery::mock('Monica\Models\Tenant');
        $this->homeController = new HomeController($this->tenant);
    }
}
