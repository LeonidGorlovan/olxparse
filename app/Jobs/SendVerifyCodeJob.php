<?php

namespace App\Jobs;

use App\Models\Subscribers;
use App\Notifications\PriceChangedNotification;
use App\Notifications\SendVerifyCodeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerifyCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Subscribers $subscriber;
    protected string $link;
    protected string $verifiedCode;

    public function __construct(Subscribers $subscriber, string $link, string $verifiedCode)
    {
        $this->subscriber = $subscriber;
        $this->link = $link;
        $this->verifiedCode = $verifiedCode;
    }

    public function handle(): void
    {
        \Log::info("Sending a notification to {$this->subscriber->email}");

        $this->subscriber->notify(new SendVerifyCodeNotification(
            $this->link,
            $this->verifiedCode,
        ));
    }
}
