@props(['product'])

@php
    $host = $product->website_url ? preg_replace('/^www\./', '', parse_url($product->website_url, PHP_URL_HOST)) : null;
    $isLive = $product->status === \App\Models\Product::STATUS_LIVE;
@endphp

<article class="group rounded-xl border border-stone-200 bg-white p-6 transition duration-200 hover:-translate-y-0.5 hover:border-stone-300">
  <div class="flex items-center justify-between gap-3">
    <h3 class="text-lg font-semibold text-stone-900">{{ $product->name }}</h3>
    <span @class([
      'rounded-full border px-2.5 py-1 text-xs font-medium',
      'border-accent/20 bg-accent/10 text-accent' => $isLive,
      'border-stone-300 bg-stone-100 text-stone-600' => ! $isLive,
    ])>{{ $product->status }}</span>
  </div>
  <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $product->short_description }}</p>
  @if ($product->metrics)
    <p class="mt-2 text-xs font-medium uppercase tracking-[0.06em] text-stone-500">{{ $product->metrics }}</p>
  @endif
  <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm font-medium">
    @if ($product->website_url)
      <a href="{{ $product->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-accent transition-colors hover:text-teal-700">{{ $host }} →</a>
    @endif
    @if ($product->hasCaseStudy())
      <a href="{{ route('work.show', $product) }}" class="inline-flex items-center text-stone-600 transition-colors hover:text-accent">Read case study →</a>
    @endif
    @if (! $product->website_url && ! $product->hasCaseStudy())
      <span class="text-stone-500">URL coming soon</span>
    @endif
  </div>
</article>
