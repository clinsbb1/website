<x-layouts.admin title="Categories">
  <h1 class="text-xl font-semibold text-stone-900">Categories</h1>
  <p class="mt-2 max-w-xl text-sm text-stone-500">Used to organise Writing. Deleting a category doesn't delete its articles — they just become uncategorised.</p>

  <form method="POST" action="{{ route('admin.categories.store') }}" class="mt-6 flex max-w-md items-start gap-3">
    @csrf
    <div class="flex-1">
      <input type="text" name="name" placeholder="New category name" value="{{ old('name') }}" class="block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
      @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-800">Add</button>
  </form>

  <div class="mt-6 max-w-2xl overflow-x-auto rounded-xl border border-stone-200 bg-white">
    <table class="w-full text-left text-sm">
      <thead class="border-b border-stone-200 text-xs uppercase tracking-[0.06em] text-stone-500">
        <tr>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Articles</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($categories as $category)
          <tr>
            <td class="px-4 py-3">
              <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="flex items-center gap-2">
                @csrf
                @method('PUT')
                <input type="text" name="name" value="{{ $category->name }}" class="w-48 rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
                <button type="submit" class="font-medium text-accent hover:text-teal-700">Save</button>
              </form>
            </td>
            <td class="px-4 py-3 text-stone-600">{{ $category->articles_count }}</td>
            <td class="px-4 py-3">
              <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete “{{ $category->name }}”? Its articles will become uncategorised.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="font-medium text-red-600 hover:text-red-700">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="px-4 py-6 text-center text-stone-500">No categories yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-layouts.admin>
