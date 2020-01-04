<?php

namespace Tests\Unit\Controllers\Dashboard\UserController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\Dashboard\UserController;

class UserControllerTestCase extends TestCase
{
    protected $userController;

    protected $auth;

    protected $gate;

    protected $tenant;

    protected $user;

    public function setup()
    {
        parent::setup();

        $this->auth = Mockery::mock('Illuminate\Auth\AuthManager');
        $this->gate = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->tenant = Mockery::mock('Monica\Models\Tenant');
        $this->user = Mockery::mock('Monica\Models\User');

        $this->auth->shouldReceive('guard')
            ->andReturn(null);

        $this->userController = new UserController(
            $this->auth,
            $this->gate,
            $this->tenant,
            $this->user
        );
    }
}
