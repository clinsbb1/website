<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $products = Product::published()->where('case_study_enabled', true)->get();
        $articles = Article::published()->get();

        return response()
            ->view('sitemap', compact('products', 'articles'))
            ->header('Content-Type', 'application/xml');
    }
}
