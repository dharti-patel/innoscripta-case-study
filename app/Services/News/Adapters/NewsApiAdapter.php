<?php

namespace App\Services\News\Adapters;

use App\Services\News\SourceAdapterInterface;
use Illuminate\Support\Facades\Http;

class NewsApiAdapter implements SourceAdapterInterface
{
    protected $apiKey;
    protected $base = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetch(array $options = []): array
    {
        $params = array_merge([
            'language' => 'en',
            'pageSize' => 100,
            'q' => 'latest',
        ], $options);

        $params['apiKey'] = $this->apiKey;

        $response = Http::get("{$this->base}/top-headlines", $params);

        if ($response->failed()) {
            \Log::error('NewsAPI failed: ' . $response->body());
            return [];
        }

        return $response->json('articles') ?? [];
    }

    public function normalize(array $item): array
    {
        return [
            'source' => 'newsapi',
            'source_id' => $item['source']['id'] ?? md5($item['url'] ?? uniqid()),
            'title' => $item['title'] ?? 'Untitled',
            'author' => $item['author'] ?? ($item['source']['name'] ?? null),
            'description' => $item['description'] ?? null,
            'content' => $item['content'] ?? null,
            'url' => $item['url'] ?? 'NoURL',
            'url_to_image' => $item['urlToImage'] ?? null,
            'published_at' => isset($item['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['publishedAt'])) : null,
            'category' => $item['category'] ?? null,
            'language' => $item['language'] ?? 'en',
            'raw' => $item,
        ];
    }
}
