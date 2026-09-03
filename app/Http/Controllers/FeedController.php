<?php

namespace App\Http\Controllers;

use App\Models\Article;

class FeedController extends Controller
{
    public function __invoke()
    {
        $articles = Article::with('category')->published()->latest('published_at')->limit(20)->get();

        return response()
            ->view('feed', compact('articles'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
