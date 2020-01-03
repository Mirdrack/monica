<?php

namespace Tests\Unit\Mail\Auth;

use Mockery;
use Tests\TestCase;
use Monica\Mail\Auth\ResetPassword;

class ResetPasswordTest extends TestCase
{
    protected $resetPassword;

    protected $tenant;

    public function setup()
    {
        parent::setup();
        $token = 'superRandomtoken';
        $email = 'super@email.com';
        $this->tenant = Mockery::mock('Monica\Models\Tenant');
        $this->resetPassword = new ResetPassword($token, $email, $this->tenant);
    }

    public function testSuccessfulToMail()
    {
        $this->tenant
            ->shouldReceive('getAttribute', 'getRouteKey')
            ->andReturn($this->tenant);

        $result = $this->resetPassword->toMail('token');
        $this->assertInstanceOf(\Illuminate\Notifications\Messages\MailMessage::class, $result);
    }
}
