<?php

namespace Tests\Unit\Controllers\Admin\AdminController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\Admin\AdminController;

class AdminControllerTestCase extends TestCase
{
    protected $adminController;

    protected $auth;

    protected $gate;

    protected $admin;

    public function setup()
    {
        parent::setup();

        $this->auth = Mockery::mock('Illuminate\Auth\AuthManager');
        $this->gate = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->admin = Mockery::mock('Monica\Models\Admin');
        $this->role = Mockery::mock('Silber\Bouncer\Database\Role');

        $this->auth->shouldReceive('shouldUse')
            ->andReturn(null);

        $this->adminController = new AdminController(
            $this->auth,
            $this->gate,
            $this->admin,
            $this->role
        );
    }
}
