<?php

namespace App\Jobs;

use App\Models\Link;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Symfony\Component\DomCrawler\Crawler;

class CheckPriceChangesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Link $link;

    public function __construct(Link $linkUrl)
    {
        $this->link = $linkUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $linkUrl = $this->link->link;

        $client = new Client();
        $response = $client->get($linkUrl);
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $selector = 'div.css-e2ir3r > h3';
        $priceText = $crawler->filter($selector)->text('');

        preg_match('/([\d\s,.]+)\s*([^\d\s,.]+)/', trim($priceText), $matches);
        $price = isset($matches[1]) ? (float) str_replace([' ', ','], ['', '.'], $matches[1]) : null;
        $currency = isset($matches[2]) ? trim($matches[2]) : null;

        if ($price === null || $currency === null) {
            \Log::warning("Failed to recognize the price or currency for the link: {$linkUrl}");
            return;
        }

        if ($this->link->price !== null && $this->link->currency === $currency && $this->link->price != $price) {
            $this->link->subscribers()->verified()->chunk(1000, function ($subscribers) use ($linkUrl, $price, $currency) {
                $jobs = $subscribers->map(function ($subscriber) use ($linkUrl, $price, $currency) {
                    return new SendPriceChangeJob(
                        $subscriber,
                        $linkUrl,
                        $this->link->price,
                        $price,
                        $currency
                    );
                })->all();

                Bus::batch($jobs)
                    ->then(function () use ($linkUrl, $price) {
                        \Log::info("All notifications for the link $linkUrl shipped (new price: $price)");
                    })
                    ->catch(function (Batch $batch, \Throwable $e) use ($linkUrl) {
                        \Log::error("Error in the notification packet for $linkUrl: " . $e->getMessage());
                    })
                    ->dispatch();
            });
        }

        $this->link->update([
            'price' => $price,
            'currency' => $currency,
        ]);
    }
}
