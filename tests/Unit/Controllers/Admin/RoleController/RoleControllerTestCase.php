<?php

namespace Tests\Unit\Controllers\Admin\RoleController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\Admin\RoleController;

class RoleControllerTestCase extends TestCase
{
    protected $roleController;

    protected $auth;

    protected $gate;

    protected $admin;

    protected $ability;

    public function setup()
    {
        parent::setup();

        $this->auth = Mockery::mock('Illuminate\Auth\AuthManager');
        $this->gate = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->role = Mockery::mock('Silber\Bouncer\Database\Role');
        $this->ability = Mockery::mock('Silber\Bouncer\Database\Ability');

        $this->auth->shouldReceive('shouldUse')
            ->andReturn(null);

        $this->roleController = new RoleController(
            $this->auth,
            $this->gate,
            $this->role,
            $this->ability
        );
    }
}
