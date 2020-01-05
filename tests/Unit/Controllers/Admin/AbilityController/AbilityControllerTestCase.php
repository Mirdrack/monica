<?php

namespace Tests\Unit\Controllers\Admin\AbilityController;

use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\Admin\AbilityController;

class AbilityControllerTestCase extends TestCase
{
    protected $abilityController;

    protected $auth;

    protected $gate;

    protected $admin;

    protected $ability;

    public function setup()
    {
        parent::setup();

        $this->auth = Mockery::mock('Illuminate\Auth\AuthManager');
        $this->gate = Mockery::mock('Illuminate\Contracts\Auth\Access\Gate');
        $this->ability = Mockery::mock('Silber\Bouncer\Database\Ability');

        $this->auth->shouldReceive('shouldUse')
            ->andReturn(null);

        $this->abilityController = new AbilityController(
            $this->auth,
            $this->gate,
            $this->ability
        );
    }
}
