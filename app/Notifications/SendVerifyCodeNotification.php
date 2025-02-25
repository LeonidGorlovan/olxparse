<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendVerifyCodeNotification extends Notification
{
    use Queueable;

    protected string $link;
    protected string $verifiedCode;

    public function __construct(string $link, string $verifiedCode)
    {
        $this->link = $link;
        $this->verifiedCode = $verifiedCode;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Подтверждение email.')
            ->line("Вы подписались на рассылку изменения цены, по ссылке $this->link.")
            ->line("Для получения уведомлений необходимо Вам подтвердить email адрес.")
            ->action('Подтвердить', config('mail.confirmation_link') . $this->verifiedCode);
    }
}
