@php
    $seoTitle = 'Writing | Clinton Agburum';
    $seoDescription = "Notes on software, products, technical leadership, entrepreneurship and the things I'm learning along the way.";
@endphp

<x-layouts.app :seo-title="$seoTitle" :seo-description="$seoDescription">
    <section class="mx-auto w-full max-w-4xl px-6 py-20 sm:py-24 lg:px-8 lg:pt-32">
      <div class="max-w-2xl">
        <h1 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Writing</h1>
        <p class="mt-4 text-lg leading-relaxed text-stone-700">Notes on software, products, technical leadership, entrepreneurship and the things I'm learning along the way.</p>
      </div>

      @if ($categories->isNotEmpty())
        <div class="mt-8 flex flex-wrap gap-2 text-sm">
          <a href="{{ route('writing.index') }}" @class(['rounded-full border px-3 py-1', 'border-stone-900 bg-stone-900 text-white' => ! $category, 'border-stone-200 text-stone-600 hover:border-stone-400' => $category])>All</a>
          @foreach ($categories as $cat)
            <a href="{{ route('writing.index', ['category' => $cat]) }}" @class(['rounded-full border px-3 py-1', 'border-stone-900 bg-stone-900 text-white' => $category === $cat, 'border-stone-200 text-stone-600 hover:border-stone-400' => $category !== $cat])>{{ $cat }}</a>
          @endforeach
        </div>
      @endif

      @if ($articles->isNotEmpty())
        <div class="mt-12 space-y-4">
          @foreach ($articles as $article)
            <x-article-card :article="$article" />
          @endforeach
        </div>
        <div class="mt-10">
          {{ $articles->links() }}
        </div>
      @else
        <p class="mt-12 text-sm text-stone-500">New writing is on its way — check back soon.</p>
      @endif
    </section>
</x-layouts.app>
