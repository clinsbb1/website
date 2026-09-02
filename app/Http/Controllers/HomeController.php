<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;

class HomeController extends Controller
{
    public function __invoke()
    {
        $products = Product::published()->featured()->ordered()->limit(4)->get();

        $articles = Article::published()->featured()->latest('published_at')->limit(3)->get();
        if ($articles->isEmpty()) {
            $articles = Article::published()->latest('published_at')->limit(3)->get();
        }

        return view('home', compact('products', 'articles'));
    }
}
