<?php

namespace App\Console\Commands;

use App\Jobs\CheckPriceChangesJob;
use App\Models\Link;
use Illuminate\Console\Command;

class CheckPricesCommand extends Command
{
    protected $signature = 'prices:check';
    protected $description = 'Check prices for all links';

    public function handle(): void
    {
        $links = Link::all();

        foreach ($links as $link) {
            CheckPriceChangesJob::dispatch($link);
        }

        $this->info('Price checks dispatched.');
    }
}
