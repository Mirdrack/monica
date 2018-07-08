<?php

namespace Monica\Mail\Auth;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The email from admin
     * @var string
     */
    public $email;

    /**
     * The tenant where user belongs
     * @var Tenant
     */
    public $tenant;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $email, $tenant)
    {
        $this->token = $token;
        $this->email = $email;
        $this->tenant = $tenant;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $restoreUrl = $this->generateRestoreUrl();

        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url($restoreUrl))
            ->line('If you did not request a password reset, no further action is required.');
    }

    /**
     * Creates the restore url with subdomain, domain and the
     * user parameteres
     * @return string The url where the user is going to click
     */
    protected function generateRestoreUrl()
    {
        $appUrl = config('app.url');
        $urlPieces = explode('//', $appUrl);
        $protocol = $urlPieces[0];
        $rest = $urlPieces[1];

        $restoreUrl = $protocol . '//' . $this->tenant->subdomain . '.' . $rest;
        $restoreUrl .= route('auth.password.restore', [$this->tenant->subdomain, $this->email, $this->token], false);
        return $restoreUrl;
    }
}
