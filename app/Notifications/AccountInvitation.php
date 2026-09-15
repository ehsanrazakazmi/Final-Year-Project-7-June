<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent when an admin creates an account. The recipient follows the link and
 * chooses their own password - admins never set or see it.
 */
class AccountInvitation extends Notification
{
    use Queueable;

    public function __construct(
        protected string $token,
        protected string $roleName
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.set', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expires = config('auth.passwords.invitations.expire', 10080);

        return (new MailMessage)
            ->subject('You have been invited to '.config('app.name'))
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('An account has been created for you on '.config('app.name').' as a **'.$this->roleName.'**.')
            ->line('Choose a password to activate your account.')
            ->action('Set your password', $url)
            ->line('This invitation link expires in '.round($expires / 1440).' days.')
            ->line('If you were not expecting this invitation, you can ignore this email.');
    }
}
