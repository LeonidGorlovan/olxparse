<?php

namespace App\Console\Commands;

use App\Jobs\CheckPriceChanges;
use App\Models\Link;
use Illuminate\Console\Command;

class CheckPrices extends Command
{
    protected $signature = 'prices:check';
    protected $description = 'Check prices for all links';

    public function handle(): void
    {
        $links = Link::all();

        foreach ($links as $link) {
            CheckPriceChanges::dispatch($link);
        }

        $this->info('Price checks dispatched.');
    }
}
