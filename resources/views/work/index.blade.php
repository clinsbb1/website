@php
    $seoTitle = 'Work | Clinton Agburum';
    $seoDescription = "Products and systems Clinton Agburum has built around real operational problems.";
@endphp

<x-layouts.app :seo-title="$seoTitle" :seo-description="$seoDescription">
    <section class="mx-auto w-full max-w-6xl px-6 py-20 sm:py-24 lg:px-8 lg:pt-32">
      <div class="max-w-3xl">
        <h1 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Work</h1>
        <p class="mt-4 text-lg leading-relaxed text-stone-700">Products and systems I've built around real operational problems.</p>
      </div>

      @if ($products->isNotEmpty())
        <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:gap-5">
          @foreach ($products as $product)
            <x-product-card :product="$product" />
          @endforeach
        </div>
      @else
        <p class="mt-12 text-sm text-stone-500">Nothing published here yet — check back soon.</p>
      @endif
    </section>
</x-layouts.app>
