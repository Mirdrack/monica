<?php

namespace Tests\Unit\Controllers\AuthController;

use Exception;
use Mockery;
use Tests\TestCase;
use Monica\Http\Controllers\AuthController;

class AuthControllerTestCase extends TestCase
{
    protected $authController;

    protected $jwt;

    public function setup()
    {
        parent::setUp();
        $this->jwt = Mockery::mock('\Tymon\JWTAuth\JWTAuth');
        $this->authController = new AuthController($this->jwt);
    }
}
