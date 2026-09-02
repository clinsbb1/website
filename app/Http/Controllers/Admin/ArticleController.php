<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Support\TiptapRenderer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest('updated_at')->paginate(20);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        // Defaults the "published at" field to now for a new article — the
        // admin can still change it before saving; once saved, editing
        // never touches it again unless explicitly changed.
        $article = new Article(['content_json' => ['type' => 'doc', 'content' => []], 'published_at' => now()]);

        return view('admin.articles.form', compact('article'));
    }

    public function store(ArticleRequest $request)
    {
        $data = $this->prepare($request);
        $data['slug'] = $data['slug'] ?: Str::slug($request->string('title'));

        if ($request->hasFile('feature_image')) {
            $data['feature_image'] = $request->file('feature_image')->store('articles', 'public');
        }

        $article = Article::create($data);

        return $this->redirectAfterSave($request, $article);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $data = $this->prepare($request);
        // A published article's slug never changes just because the title did.
        $data['slug'] = $data['slug'] ?: $article->slug;

        if ($request->boolean('remove_feature_image')) {
            $this->deleteFile($article->feature_image);
            $data['feature_image'] = null;
        } elseif ($request->hasFile('feature_image')) {
            $this->deleteFile($article->feature_image);
            $data['feature_image'] = $request->file('feature_image')->store('articles', 'public');
        }

        $article->update($data);

        return $this->redirectAfterSave($request, $article);
    }

    public function destroy(Article $article)
    {
        $this->deleteFile($article->feature_image);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('status', 'Article deleted.');
    }

    /**
     * Authenticated preview — renders the exact public layout regardless of
     * status, so drafts/future posts are never publicly reachable.
     */
    public function preview(Article $article)
    {
        return view('writing.show', ['article' => $article, 'preview' => true]);
    }

    public function togglePublish(Article $article)
    {
        if ($article->status === Article::STATUS_PUBLISHED) {
            $article->update(['status' => Article::STATUS_DRAFT]);

            return back()->with('status', 'Article unpublished.');
        }

        if (! TiptapRenderer::hasContent($article->content_json)) {
            return back()->with('status', 'Add some content before publishing.');
        }

        $article->update([
            'status' => Article::STATUS_PUBLISHED,
            'published_at' => $article->published_at ?? now(),
        ]);

        return back()->with('status', 'Article published.');
    }

    private function prepare(ArticleRequest $request): array
    {
        $data = $request->safe()->except(['feature_image', 'remove_feature_image']);
        $data['featured'] = $request->boolean('featured');
        $data['content_json'] = json_decode($request->input('content_json'), true);

        if ($data['status'] === Article::STATUS_PUBLISHED && ! $request->filled('published_at')) {
            $data['published_at'] = now();
        }

        return $data;
    }

    private function redirectAfterSave(ArticleRequest $request, Article $article)
    {
        if ($request->input('action') === 'preview') {
            return redirect()->route('admin.articles.preview', $article);
        }

        return redirect()->route('admin.articles.index')->with('status', 'Article saved.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
