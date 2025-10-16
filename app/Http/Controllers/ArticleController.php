<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $q = Article::query();

        // search query
        if ($search = $request->query('q')) {
            $q->where(function ($q2) use ($search) {
                $q2->where('title', 'LIKE', "%{$search}%")
                   ->orWhere('description', 'LIKE', "%{$search}%")
                   ->orWhere('content', 'LIKE', "%{$search}%")
                   ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }

        // filters
        if ($from = $request->query('from')) {
            $q->where('published_at', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $q->where('published_at', '<=', $to);
        }
        if ($source = $request->query('source')) {
            $q->whereIn('source', explode(',', $source));
        }
        if ($category = $request->query('category')) {
            $q->where('category', $category);
        }

        if ($prefs = $request->query('prefs')) {
            $preferences = $this->parsePreferences($prefs);

            if (!empty($preferences['sources'])) {
                $q->whereIn('source', $preferences['sources']);
            }
            if (!empty($preferences['categories'])) {
                $q->whereIn('category', $preferences['categories']);
            }
            if (!empty($preferences['authors'])) {
                $q->whereIn('author', $preferences['authors']);
            }
        }

        // sorting
        $q->orderBy('published_at', 'desc');

        $perPage = min(100, max(10, intval($request->query('per_page', 20))));
        $articles = $q->paginate($perPage)->appends($request->query());

        return ArticleResource::collection($articles);
    }

    /**
     * Parse prefs like:
     * - prefs=sources:newsapi|theguardian;categories:business,tech
     * - or prefs={"sources":["newsapi","guardian"],"categories":["tech"]}
     */
    protected function parsePreferences(string $prefs): array
    {
        $result = [];

        // Try JSON first
        if ($this->isJson($prefs)) {
            return json_decode($prefs, true);
        }

        // Otherwise parse manually (semicolon syntax)
        $parts = explode(';', $prefs);
        foreach ($parts as $part) {
            [$key, $value] = array_pad(explode(':', $part, 2), 2, null);
            if ($key && $value) {
                $values = preg_split('/[|,]/', $value);
                $result[$key] = array_map('trim', $values);
            }
        }

        return $result;
    }

    protected function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return new ArticleResource($article);
    }

    public function sources()
    {
        // return configured sources
        return response()->json([
            'sources' => ['newsapi', 'theguardian', 'nytimes']
        ]);
    }
}
