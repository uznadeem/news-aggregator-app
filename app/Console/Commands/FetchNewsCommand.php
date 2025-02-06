<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsAggregatorService;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch news articles from external sources';
    
    public function __construct(private NewsAggregatorService $newsAggregatorService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->newsAggregatorService->fetchNews();
        $this->info('News articles fetched successfully.');
    }
}
