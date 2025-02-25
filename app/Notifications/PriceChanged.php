<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PriceChanged extends Notification
{
    use Queueable;

    protected string $link;
    protected float $oldPrice;
    protected float $newPrice;
    protected string $currency;

    public function __construct(string $link, float $oldPrice, float $newPrice, string $currency)
    {
        $this->link = $link;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->currency = $currency;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Изменение цены')
            ->line("Цена по ссылке {$this->link} изменилась!")
            ->line("Старая цена: {$this->oldPrice} {$this->currency}")
            ->line("Новая цена: {$this->newPrice} {$this->currency}")
            ->action('Посмотреть', $this->link);
    }
}
