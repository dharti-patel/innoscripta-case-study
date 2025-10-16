<?php

namespace App\Services\News;

interface SourceAdapterInterface
{
    /**
     * Fetch latest articles from provider. 
     * Returns array of provider-defined article data.
     *
     * @param array $options
     * @return array
     */
    public function fetch(array $options = []): array;

    /**
     * Normalize a single provider item into the unified array format:
     * [
     *   'source' => 'newsapi', 'source_id' => '...', 'author' => '...',
     *   'title' => '...', 'description' => '...', 'content' => '...',
     *   'url' => '...', 'url_to_image' => '...', 'published_at' => 'Y-m-d H:i:s',
     *   'category' => 'business', 'language' => 'en', 'raw' => [...]
     * ]
     *
     * @param array $item
     * @return array
     */
    public function normalize(array $item): array;
}
