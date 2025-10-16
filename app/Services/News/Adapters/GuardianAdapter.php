<?php

namespace App\Services\News\Adapters;

use App\Services\News\SourceAdapterInterface;
use Illuminate\Support\Facades\Http;

class GuardianAdapter implements SourceAdapterInterface
{
    protected string $apiKey;
    protected string $baseUrl = 'https://content.guardianapis.com/search';

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetch(array $options = []): array
    {
        $params = array_merge([
            'api-key' => $this->apiKey,
            'show-fields' => 'headline,byline,trailText,bodyText,thumbnail',
            'page-size' => 50,
            'q' => 'latest',
        ], $options);

        $response = Http::get($this->baseUrl, $params);

        if ($response->failed()) {
            \Log::error('Guardian API failed: ' . $response->body());
            return [];
        }

        $json = $response->json();

        return $json['response']['results'] ?? [];
    }

    public function normalize(array $item): array
    {
        $fields = $item['fields'] ?? [];
        $title = $fields['headline'] ?? ($item['webTitle'] ?? 'Untitled');

        return [
            'source' => 'theguardian',
            'source_id' => $item['id'] ?? md5($item['webUrl'] ?? uniqid()),
            'title' => $fields['headline'] ?? ($item['webTitle'] ?? 'Untitled'),
            'author' => $fields['byline'] ?? null,
            'description' => $fields['trailText'] ?? null,
            'content' => $fields['bodyText'] ?? null,
            'url' => $item['webUrl'] ?? 'NoURL',
            'url_to_image' => $fields['thumbnail'] ?? null,
            'published_at' => isset($item['webPublicationDate']) ? date('Y-m-d H:i:s', strtotime($item['webPublicationDate'])) : null,
            'category' => $item['sectionName'] ?? null,
            'language' => 'en',
            'raw' => $item,
        ];
    }
}
