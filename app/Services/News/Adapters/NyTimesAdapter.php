<?php

namespace App\Services\News\Adapters;

use App\Services\News\SourceAdapterInterface;
use Illuminate\Support\Facades\Http;

class NyTimesAdapter implements SourceAdapterInterface
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.key');
    }

    public function fetch(array $options = []): array
    {
        $params = array_merge([
            'api-key' => $this->apiKey,
            'sort' => 'newest',
            'page' => 0,
            'q' => 'news',
        ], $options);

        $response = Http::get($this->baseUrl, $params);

        if ($response->failed()) {
            \Log::error('NYTimes API failed: ' . $response->body());
            return [];
        }

        $json = $response->json();

        return $json['response']['docs'] ?? [];
    }

    public function normalize(array $item): array
    {
        $multimedia = $item['multimedia'][0]['url'] ?? null;
        $imageUrl = $multimedia
            ? 'https://www.nytimes.com/' . ltrim($multimedia, '/')
            : null;

        return [
            'source' => 'nytimes',
            'source_id' => $item['_id'] ?? null,
            'author' => $item['byline']['original'] ?? null,
            'title' => $item['headline']['main'] ?? 'Untitled',
            'description' => $item['abstract'] ?? null,
            'content' => $item['lead_paragraph'] ?? null,
            'url' => $item['web_url'] ?? 'NoURL',
            'url_to_image' => $imageUrl,
            'published_at' => $item['pub_date'] ?? null,
            'category' => $item['section_name'] ?? null,
            'language' => 'en',
            'raw' => $item,
        ];
    }
}
