<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'author' => $this->author,
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'url_to_image' => $this->url_to_image,
            'published_at' => $this->published_at,
            'category' => $this->category,
        ];
    }
}
