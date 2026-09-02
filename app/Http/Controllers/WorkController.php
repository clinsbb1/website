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
}
