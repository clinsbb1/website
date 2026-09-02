<x-layouts.admin title="Products">
  <div class="flex items-center justify-between">
    <h1 class="text-xl font-semibold text-stone-900">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="rounded-md bg-stone-900 px-4 py-2 text-sm font-medium text-white hover:bg-stone-800">New product</a>
  </div>

  <div class="mt-6 overflow-x-auto rounded-xl border border-stone-200 bg-white">
    <table class="w-full text-left text-sm">
      <thead class="border-b border-stone-200 text-xs uppercase tracking-[0.06em] text-stone-500">
        <tr>
          <th class="px-4 py-3">Name</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Sort</th>
          <th class="px-4 py-3">Featured</th>
          <th class="px-4 py-3">Published</th>
          <th class="px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-stone-100">
        @forelse ($products as $product)
          <tr>
            <td class="px-4 py-3 font-medium text-stone-900">{{ $product->name }}</td>
            <td class="px-4 py-3 text-stone-600">{{ $product->status }}</td>
            <td class="px-4 py-3 text-stone-600">{{ $product->sort_order }}</td>
            <td class="px-4 py-3">{{ $product->featured ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">{{ $product->published ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="font-medium text-accent hover:text-teal-700">Edit</a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="font-medium text-red-600 hover:text-red-700">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-stone-500">No products yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-layouts.admin>
