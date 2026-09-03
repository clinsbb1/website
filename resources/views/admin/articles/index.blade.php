<x-layouts.admin title="Articles">
  <div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold text-stone-900">Articles</h1>
    <a href="{{ route('admin.articles.create') }}" class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-800">New article</a>
  </div>

  <div class="mt-6 overflow-x-auto rounded-xl border border-stone-200 bg-white">
    <table class="w-full text-left text-sm">
      <thead class="border-b border-stone-200 text-xs uppercase tracking-[0.06em] text-stone-500">
        <tr>
          <th class="px-4 py-3">Title</th>
          <th class="px-4 py-3">Category</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Published</th>
          <th class="px-4 py-3">Updated</th>
          <th class="px-4 py-3">Featured</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($articles as $article)
          <tr>
            <td class="px-4 py-3 font-medium text-stone-900">{{ $article->title }}</td>
            <td class="px-4 py-3 text-stone-600">{{ $article->category?->name ?? '—' }}</td>
            <td class="px-4 py-3 text-stone-600">{{ ucfirst($article->status) }}</td>
            <td class="px-4 py-3 text-stone-600">{{ $article->published_at?->format('M j, Y') ?? '—' }}</td>
            <td class="px-4 py-3 text-stone-600">{{ $article->updated_at->format('M j, Y') }}</td>
            <td class="px-4 py-3">{{ $article->featured ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.articles.edit', $article) }}" class="font-medium text-accent hover:text-teal-700">Edit</a>
                <a href="{{ route('admin.articles.preview', $article) }}" target="_blank" class="font-medium text-stone-600 hover:text-stone-900">Preview</a>
                <form method="POST" action="{{ route('admin.articles.toggle-publish', $article) }}">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="font-medium text-stone-600 hover:text-stone-900">{{ $article->status === \App\Models\Article::STATUS_PUBLISHED ? 'Unpublish' : 'Publish' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Delete “{{ $article->title }}”? This cannot be undone.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="font-medium text-red-600 hover:text-red-700">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-4 py-6 text-center text-stone-500">No articles yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">{{ $articles->links() }}</div>
</x-layouts.admin>
