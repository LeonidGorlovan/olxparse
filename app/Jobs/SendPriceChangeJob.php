<?php

namespace App\Jobs;

use App\Models\Subscribers;
use App\Notifications\PriceChangedNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPriceChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Subscribers $subscriber;
    protected string $link;
    protected float $oldPrice;
    protected float $newPrice;
    protected string $currency;

    public function __construct(Subscribers $subscriber, string $link, float $oldPrice, float $newPrice, string $currency)
    {
        $this->subscriber = $subscriber;
        $this->link = $link;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
        $this->currency = $currency;
    }

    public function handle(): void
    {
        \Log::info("Sending a notification to {$this->subscriber->email}");

        $this->subscriber->notify(new PriceChangedNotification(
            $this->link,
            $this->oldPrice,
            $this->newPrice,
            $this->currency
        ));
    }
}
