<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\News\NewsFetchService;

class FetchNewsCommand extends Command
{
    protected $signature = 'news:fetch {--query=}';
    protected $description = 'Fetch latest news and store in DB';

    public function handle(NewsFetchService $service)
    {
        $query = $this->option('query') ?? 'latest';
        $this->info("Fetching articles for query: {$query}");

        $service->fetchAndStore(['query' => $query]);

        $this->info('Articles fetched and saved successfully.');
    }
}
