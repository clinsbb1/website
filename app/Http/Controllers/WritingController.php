<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class WritingController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        $articles = Article::published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $categories = Article::published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('writing.index', compact('articles', 'categories', 'category'));
    }

    public function show(Article $article)
    {
        abort_unless($article->status === Article::STATUS_PUBLISHED && $article->published_at <= now(), 404);

        return view('writing.show', compact('article'));
    }
}
