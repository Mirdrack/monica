<?php

namespace Tests\Unit\Mail\Auth;

use Mockery;
use Tests\TestCase;
use Monica\Mail\Auth\AdminResetPassword;

class AdminResetPasswordTest extends TestCase
{
    protected $adminResetPassword;

    public function setup()
    {
        parent::setup();
        $token = 'superRandomtoken';
        $email = 'super@email.com';
        $this->adminResetPassword = new AdminResetPassword($token, $email);
    }

    public function testSuccessfulToMail()
    {
        $result = $this->adminResetPassword->toMail('token');
        $this->assertInstanceOf(\Illuminate\Notifications\Messages\MailMessage::class, $result);
    }
}
