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

        // Relative on purpose: the URL is saved inside the article's content, so
        // it must not bake in whichever host the upload happened to be made on.
        return response()->json([
            'url' => '/storage/'.$path,
        ]);
    }
}
