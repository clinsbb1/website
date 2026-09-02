<x-layouts.admin title="Overview">
  <h1 class="text-xl font-semibold text-stone-900">Overview</h1>

  <div class="mt-6 grid gap-4 sm:grid-cols-2">
    <div class="rounded-xl border border-stone-200 bg-white p-6">
      <p class="text-xs font-semibold uppercase tracking-[0.1em] text-stone-500">Products</p>
      <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $productCounts['published'] }} <span class="text-base font-normal text-stone-500">published / {{ $productCounts['total'] }} total</span></p>
      <a href="{{ route('admin.products.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-accent hover:text-teal-700">Manage products →</a>
    </div>
    <div class="rounded-xl border border-stone-200 bg-white p-6">
      <p class="text-xs font-semibold uppercase tracking-[0.1em] text-stone-500">Articles</p>
      <p class="mt-2 text-2xl font-semibold text-stone-900">{{ $articleCounts['published'] }} <span class="text-base font-normal text-stone-500">published / {{ $articleCounts['draft'] }} draft</span></p>
      <a href="{{ route('admin.articles.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-accent hover:text-teal-700">Manage articles →</a>
    </div>
  </div>
</x-layouts.admin>
