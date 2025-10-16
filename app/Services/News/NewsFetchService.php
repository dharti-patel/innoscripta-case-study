<?php

namespace App\Services\News;

use App\Models\Article;

class NewsFetchService
{
    protected array $adapters;

    public function __construct(array $adapters = [])
    {
        $this->adapters = $adapters;
    }

    /**
     * Fetch articles from all adapters and store them in DB
     *
     * @param array $options
     */
    public function fetchAndStore(array $options = []): void
    {
        foreach ($this->adapters as $adapter) {
            $articles = $adapter->fetch($options);

            foreach ($articles as $a) {
                if (method_exists($adapter, 'normalize')) {
                    $a = $adapter->normalize($a);
                }

                Article::updateOrCreate(
                    ['source' => $a['source'], 'source_id' => $a['source_id']],
                    [
                        'title'        => $a['title'] ?? 'Untitled',
                        'source_id'    => $a['source_id'] ?? null,
                        'author'       => $a['author'] ?? null,
                        'description'  => $a['description'] ?? null,
                        'content'      => $a['content'] ?? null,
                        'url'          => $a['url'] ?? 'NoURL',
                        'url_to_image' => $a['url_to_image'] ?? null,
                        'published_at' => isset($a['published_at']) ? date('Y-m-d H:i:s', strtotime($a['published_at'])) : null,
                        'category'     => $a['category'] ?? null,
                        'language'     => $a['language'] ?? 'en',
                        'raw'          => $a['raw'] ?? null,
                    ]
                );
            }
        }
    }

}
