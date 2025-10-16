<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'source', 'source_id', 'author', 'title', 'description',
        'content', 'url', 'url_to_image', 'published_at', 'category',
        'language', 'raw'
    ];

    protected $casts = [
        'raw' => 'array',
        'published_at' => 'datetime',
    ];
}
