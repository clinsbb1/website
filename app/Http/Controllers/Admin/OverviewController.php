<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;

class OverviewController extends Controller
{
    public function __invoke()
    {
        $productCounts = [
            'published' => Product::published()->count(),
            'total' => Product::count(),
        ];

        $articleCounts = [
            'published' => Article::where('status', Article::STATUS_PUBLISHED)->count(),
            'draft' => Article::where('status', Article::STATUS_DRAFT)->count(),
        ];

        return view('admin.overview', compact('productCounts', 'articleCounts'));
    }
}
