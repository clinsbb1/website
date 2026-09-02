@php
    $editing = $product->exists;
@endphp

<x-layouts.admin :title="$editing ? 'Edit product' : 'New product'">
  <h1 class="text-xl font-semibold text-stone-900">{{ $editing ? 'Edit product' : 'New product' }}</h1>

  <form method="POST" action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="mt-6 max-w-3xl space-y-8">
    @csrf
    @if ($editing) @method('PUT') @endif

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">Details</h2>
      <div class="mt-4 grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-stone-700">Name</label>
          <input type="text" name="name" id="product-name" required value="{{ old('name', $product->name) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-stone-700">Slug</label>
          <input type="text" name="slug" id="product-slug" value="{{ old('slug', $product->slug) }}" placeholder="auto-generated from name if left blank" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm font-mono focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-stone-700">Short description</label>
          <input type="text" name="short_description" required value="{{ old('short_description', $product->short_description) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Status</label>
          <select name="status" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
            @foreach ([\App\Models\Product::STATUS_LIVE, \App\Models\Product::STATUS_IN_DEVELOPMENT, \App\Models\Product::STATUS_IN_PROGRESS, \App\Models\Product::STATUS_ARCHIVED] as $status)
              <option value="{{ $status }}" @selected(old('status', $product->status) === $status)>{{ $status }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Website URL</label>
          <input type="url" name="website_url" value="{{ old('website_url', $product->website_url) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
          @error('website_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Sort order</label>
          <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $product->sort_order) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">Metrics <span class="font-normal text-stone-400">(only if verified — e.g. "50+ events · 10,000+ check-ins")</span></label>
          <input type="text" name="metrics" value="{{ old('metrics', $product->metrics) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div class="sm:col-span-2">
          <label class="block text-sm font-medium text-stone-700">Technologies <span class="font-normal text-stone-400">(comma-separated)</span></label>
          <input type="text" name="technologies" value="{{ old('technologies', is_array($product->technologies) ? implode(', ', $product->technologies) : '') }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="featured" id="featured" value="1" @checked(old('featured', $product->featured)) class="rounded border-stone-300 text-accent focus:ring-accent">
          <label for="featured" class="text-sm text-stone-700">Featured on homepage</label>
        </div>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="published" id="published" value="1" @checked(old('published', $product->published)) class="rounded border-stone-300 text-accent focus:ring-accent">
          <label for="published" class="text-sm text-stone-700">Published</label>
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <div class="flex items-center gap-2">
        <input type="checkbox" name="case_study_enabled" id="case_study_enabled" value="1" @checked(old('case_study_enabled', $product->case_study_enabled)) class="rounded border-stone-300 text-accent focus:ring-accent">
        <label for="case_study_enabled" class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">Case study page</label>
      </div>
      <p class="mt-2 text-xs text-stone-500">Enable to publish /work/{slug}. Only sections with content are shown — nothing renders empty.</p>
      <div class="mt-4 space-y-4">
        @foreach (['overview' => 'Overview', 'problem' => 'Problem', 'role' => 'My Role', 'what_we_built' => 'What We Built', 'technical_approach' => 'Technical Approach', 'outcome' => 'Outcome'] as $field => $label)
          <div>
            <label class="block text-sm font-medium text-stone-700">{{ $label }}</label>
            <textarea name="{{ $field }}" rows="3" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">{{ old($field, $product->$field) }}</textarea>
          </div>
        @endforeach
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">Images</h2>
      <div class="mt-4 grid gap-6 sm:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-stone-700">Image</label>
          @if ($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="" class="mt-2 h-24 w-full rounded-md border border-stone-200 object-cover">
            <label class="mt-1 flex items-center gap-2 text-xs text-stone-500"><input type="checkbox" name="remove_image" value="1" class="rounded border-stone-300"> Remove</label>
          @endif
          <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="mt-2 block w-full text-sm">
          @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">OG image <span class="font-normal text-stone-400">(social preview)</span></label>
          @if ($product->og_image)
            <img src="{{ asset('storage/'.$product->og_image) }}" alt="" class="mt-2 h-24 w-full rounded-md border border-stone-200 object-cover">
            <label class="mt-1 flex items-center gap-2 text-xs text-stone-500"><input type="checkbox" name="remove_og_image" value="1" class="rounded border-stone-300"> Remove</label>
          @endif
          <input type="file" name="og_image" accept="image/png,image/jpeg,image/webp" class="mt-2 block w-full text-sm">
          @error('og_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>
    </section>

    <section class="rounded-xl border border-stone-200 bg-white p-6">
      <h2 class="text-sm font-semibold uppercase tracking-[0.08em] text-stone-500">SEO</h2>
      <div class="mt-4 space-y-4">
        <div>
          <label class="block text-sm font-medium text-stone-700">SEO title</label>
          <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700">SEO description</label>
          <textarea name="seo_description" rows="2" class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">{{ old('seo_description', $product->seo_description) }}</textarea>
        </div>
      </div>
    </section>

    <div class="flex items-center gap-3">
      <button type="submit" class="rounded-md bg-stone-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-stone-800">{{ $editing ? 'Save changes' : 'Create product' }}</button>
      <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-stone-600 hover:text-stone-900">Cancel</a>
    </div>
  </form>

  <script>
    (function () {
      const nameEl = document.getElementById('product-name');
      const slugEl = document.getElementById('product-slug');
      let slugTouched = {{ $editing ? 'true' : 'false' }};
      slugEl.addEventListener('input', () => { slugTouched = true; });
      nameEl.addEventListener('input', () => {
        if (slugTouched) return;
        slugEl.value = nameEl.value.toLowerCase().trim()
          .replace(/[^a-z0-9]+/g, '-')
          .replace(/^-+|-+$/g, '');
      });
    })();
  </script>
</x-layouts.admin>
