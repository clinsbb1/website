<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleImageController extends Controller
{
    /**
     * Uploads an image inserted inline into the Tiptap editor. Kept separate
     * from the article's feature_image field entirely.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ]);

        $path = $request->file('image')->store('articles/inline', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }
}
