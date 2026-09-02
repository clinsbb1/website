<?php

namespace App\Http\Controllers;

use App\Models\Product;

class WorkController extends Controller
{
    public function index()
    {
        $products = Product::published()->ordered()->get();

        return view('work.index', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->published && $product->hasCaseStudy(), 404);

        return view('work.show', compact('product'));
    }
}
