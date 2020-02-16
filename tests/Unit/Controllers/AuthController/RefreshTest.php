<?php

namespace Tests\Unit\Http\Controllers\AuthController;

use Mockery;

class RefreshTest extends AuthControllerTestCase
{
    public function testRefresh()
    {
        $this->jwt->shouldReceive('parseToken')->andReturnSelf();
        $this->jwt->shouldReceive('authenticate')->andThrows('Tymon\JWTAuth\Exceptions\TokenExpiredException');
        $this->jwt->shouldReceive('getToken')->andReturn('old-token');
        $this->jwt->shouldReceive('refresh')->andReturn('new-token');

        $result = $this->authController->refresh();
        $jsonResult = json_decode($result->getContent());

        $this->assertInstanceOf('\Illuminate\Http\JsonResponse', $result);
        $this->assertObjectHasAttribute('access_token', $jsonResult);
        $this->assertEquals($jsonResult->access_token, 'new-token');
    }

    public function testRefreshFailed()
    {
        $this->jwt->shouldReceive('parseToken')->andReturnSelf();
        $this->jwt->shouldReceive('authenticate')->andReturnNull();

        $result = $this->authController->refresh();
        $statusCode = $result->getStatusCode();
        $jsonResult = json_decode($result->getContent());

        $this->assertInstanceOf('\Illuminate\Http\JsonResponse', $result);
        $this->assertEquals($result->getStatusCode(), 404);
        $this->assertObjectHasAttribute('error', $jsonResult);
        $this->assertEquals($jsonResult->error, 'User not found');
    }

    public function testRefreshValidToken()
    {
        $user = Mockery::mock('Monica\Models\User');
        $this->jwt->shouldReceive('parseToken')->andReturnSelf();
        $this->jwt->shouldReceive('authenticate')->andReturn($user);
        $this->jwt->shouldReceive('getToken')->andReturn('old-token');
        $this->jwt->shouldReceive('refresh')->andReturn('new-token');

        $result = $this->authController->refresh();
        $jsonResult = json_decode($result->getContent());

        $this->assertInstanceOf('\Illuminate\Http\JsonResponse', $result);
        $this->assertObjectHasAttribute('access_token', $jsonResult);
        $this->assertEquals($jsonResult->access_token, 'new-token');
    }
}
