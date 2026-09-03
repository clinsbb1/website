<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $selectedCategory = $categorySlug ? Category::where('slug', $categorySlug)->first() : null;

        $articles = Article::with('category')
            ->published()
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::whereHas('articles', fn ($query) => $query->published())
            ->orderBy('name')
            ->get();

        return view('writing.index', compact('articles', 'categories', 'selectedCategory'));
    }

    public function show(Article $article)
    {
        abort_unless($article->status === Article::STATUS_PUBLISHED && $article->published_at <= now(), 404);

        $article->load('category');

        return view('writing.show', compact('article'));
    }
}
