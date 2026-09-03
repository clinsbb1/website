@php
    $editing = $article->exists;
@endphp

<x-layouts.admin :title="$editing ? 'Edit article' : 'New article'">
  @push('scripts')
    @vite(['resources/js/admin-editor.js'])
  @endpush

  <div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold text-stone-900">{{ $editing ? 'Edit article' : 'New article' }}</h1>
    @if ($editing)
      <p class="text-xs text-stone-500">
        Last saved: {{ $article->updated_at->format('j F Y, H:i') }}
        @if ($article->published_at)
          · Published: {{ $article->published_at->format('j F Y') }}
        @endif
      </p>
    @endif
  </div>

  <form method="POST" action="{{ $editing ? route('admin.articles.update', $article) : route('admin.articles.store') }}" enctype="multipart/form-data" class="mt-6 max-w-3xl space-y-8" id="article-form">
    @csrf
    @if ($editing) @method('PUT') @endif
    <input type="hidden" name="action" id="form-action" value="save">

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-stone-700">Title</label>
          <input type="text" name="title" id="article-title" required value="{{ old('title', $article->title) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Slug</label>
          <input type="text" name="slug" id="article-slug" value="{{ old('slug', $article->slug) }}" placeholder="auto-generated from title if left blank" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm font-mono focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Excerpt</label>
          <textarea name="excerpt" rows="2" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">{{ old('excerpt', $article->excerpt) }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label class="block text-sm font-medium text-stone-700">Category</label>
            <select name="category_id" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
              <option value="">— None —</option>
              @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected((int) old('category_id', $article->category_id) === $cat->id)>{{ $cat->name }}</option>
              @endforeach
            </select>
            <p class="mt-1 text-xs text-stone-500"><a href="{{ route('admin.categories.index') }}" class="text-accent hover:text-teal-700">Manage categories →</a></p>
            @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700">Feature image</label>
            <div class="mt-2 rounded-md border border-dashed border-stone-300 bg-stone-50 p-3">
              @if ($article->feature_image)
                <img src="{{ asset('storage/'.$article->feature_image) }}" alt="" class="h-16 w-full rounded-md border border-stone-200 object-cover">
                <label class="mt-2 flex items-center gap-2 text-xs text-stone-500"><input type="checkbox" name="remove_feature_image" value="1" class="rounded border-stone-300"> Remove</label>
              @endif
              <input type="file" name="feature_image" accept="image/png,image/jpeg,image/webp" class="mt-2 block w-full text-sm text-stone-600 file:mr-3 file:rounded-md file:border file:border-stone-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-stone-700 hover:file:border-stone-400">
            </div>
            @error('feature_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6" data-tiptap-root data-image-upload-url="{{ route('admin.articles.images.store') }}">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">Article Content</h2>

      <div data-tiptap-toolbar class="tiptap-toolbar mt-4 flex flex-wrap gap-1 border-b border-stone-200 pb-3">
        <button type="button" data-command="paragraph" aria-label="Paragraph" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">¶</button>
        <button type="button" data-command="h2" aria-label="Heading 2" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">H2</button>
        <button type="button" data-command="h3" aria-label="Heading 3" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">H3</button>
        <button type="button" data-command="bold" aria-label="Bold" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-semibold text-stone-600 hover:border-stone-400">B</button>
        <button type="button" data-command="italic" aria-label="Italic" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs italic text-stone-600 hover:border-stone-400">I</button>
        <button type="button" data-command="link" aria-label="Link" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">Link</button>
        <button type="button" data-command="blockquote" aria-label="Blockquote" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">“ ”</button>
        <button type="button" data-command="bulletList" aria-label="Bullet list" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">• List</button>
        <button type="button" data-command="orderedList" aria-label="Numbered list" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">1. List</button>
        <button type="button" data-command="code" aria-label="Inline code" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-mono text-stone-600 hover:border-stone-400">Code</button>
        <button type="button" data-command="codeBlock" aria-label="Code block" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-mono text-stone-600 hover:border-stone-400">{ }</button>
        <button type="button" data-command="image" aria-label="Insert image" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">Image</button>
        <button type="button" data-command="horizontalRule" aria-label="Horizontal rule" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">―</button>
        <button type="button" data-command="undo" aria-label="Undo" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">Undo</button>
        <button type="button" data-command="redo" aria-label="Redo" class="rounded-md border border-stone-200 px-2.5 py-1.5 text-xs font-medium text-stone-600 hover:border-stone-400">Redo</button>
      </div>

      <div data-tiptap-editor class="tiptap-editor prose prose-stone mt-4 max-w-none"></div>
      <input type="file" data-tiptap-image-input accept="image/png,image/jpeg,image/webp" class="hidden">
      <input type="hidden" name="content_json" data-tiptap-content-input>
      <script type="application/json" data-tiptap-initial-content>{!! json_encode($article->content_json ?? ['type' => 'doc', 'content' => []]) !!}</script>
      @error('content_json') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">Publishing</h2>
      <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-stone-700">Status</label>
          <select name="status" id="article-status" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            @foreach ([\App\Models\Article::STATUS_DRAFT, \App\Models\Article::STATUS_PUBLISHED, \App\Models\Article::STATUS_ARCHIVED] as $status)
              <option value="{{ $status }}" @selected(old('status', $article->status) === $status)>{{ ucfirst($status) }}</option>
            @endforeach
          </select>
          @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Published at</label>
          <input type="datetime-local" name="published_at" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d\TH:i')) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          <p class="mt-1 text-xs text-stone-500">Left blank, publishing sets this to now. A future date keeps it hidden until then.</p>
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="featured" id="featured" value="1" @checked(old('featured', $article->featured)) class="rounded border-stone-300 text-accent focus:ring-accent">
          <label for="featured" class="text-sm text-stone-700">Featured on homepage</label>
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">SEO</h2>
      <div class="mt-4 space-y-4">
        <div>
          <label class="block text-sm font-medium text-stone-700">SEO title</label>
          <input type="text" name="seo_title" value="{{ old('seo_title', $article->seo_title) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">SEO description</label>
          <textarea name="seo_description" rows="2" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">{{ old('seo_description', $article->seo_description) }}</textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Canonical URL <span class="font-normal text-stone-400">(defaults to this article's own page if left blank)</span></label>
          <input type="url" name="canonical_url" value="{{ old('canonical_url', $article->canonical_url) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">External Publication</h2>
      <p class="mt-1 text-xs text-stone-500">If this article was also published elsewhere, link to that one place.</p>
      <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-stone-700">Platform</label>
          <select name="external_platform" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            <option value="">— None —</option>
            @foreach (\App\Models\Article::externalPlatformLabels() as $value => $label)
              <option value="{{ $value }}" @selected(old('external_platform', $article->external_platform) === $value)>{{ $label }}</option>
            @endforeach
          </select>
          @error('external_platform') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">URL</label>
          <input type="url" name="external_url" value="{{ old('external_url', $article->external_url) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('external_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <div class="flex flex-wrap items-center gap-3">
      <button type="submit" data-status="draft" class="article-submit rounded-md border border-stone-300 bg-white px-5 py-2.5 text-sm font-medium text-stone-700 hover:border-stone-400">Save Draft</button>
      <button type="submit" data-action="preview" class="article-submit rounded-md border border-stone-300 bg-white px-5 py-2.5 text-sm font-medium text-stone-700 hover:border-stone-400">Preview</button>
      <button type="submit" data-status="published" class="article-submit rounded-md bg-stone-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-stone-800">Publish</button>
      <a href="{{ route('admin.articles.index') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Cancel</a>
    </div>
  </form>

  <script>
    (function () {
      const titleEl = document.getElementById('article-title');
      const slugEl = document.getElementById('article-slug');
      let slugTouched = {{ $editing ? 'true' : 'false' }};
      slugEl.addEventListener('input', () => { slugTouched = true; });
      titleEl.addEventListener('input', () => {
        if (slugTouched) return;
        slugEl.value = titleEl.value.toLowerCase().trim()
          .replace(/[^a-z0-9]+/g, '-')
          .replace(/^-+|-+$/g, '');
      });

      const statusEl = document.getElementById('article-status');
      const actionEl = document.getElementById('form-action');
      document.querySelectorAll('.article-submit').forEach((btn) => {
        btn.addEventListener('click', () => {
          if (btn.dataset.status) statusEl.value = btn.dataset.status;
          actionEl.value = btn.dataset.action || 'save';
        });
      });
    })();
  </script>
</x-layouts.admin>
