<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\News\NewsFetchService;
use App\Services\News\Adapters\NewsApiAdapter;
use App\Services\News\Adapters\GuardianAdapter;
use App\Services\News\Adapters\NyTimesAdapter;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsFetchService::class, function ($app) {
            return new NewsFetchService([
                $app->make(NewsApiAdapter::class),
                $app->make(GuardianAdapter::class),
                $app->make(NyTimesAdapter::class),
            ]);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
