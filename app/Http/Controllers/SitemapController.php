<?php

namespace App\Http\Controllers;

use App\Models\Article;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $articles = Article::published()->get();

        return response()
            ->view('sitemap', compact('articles'))
            ->header('Content-Type', 'application/xml');
    }
}
