@php
    $seoTitle = ($product->seo_title ?: $product->name).' | Clinton Agburum';
    $seoDescription = $product->seo_description ?: $product->short_description;
    $seoImage = $product->og_image ? asset('storage/'.$product->og_image) : ($product->image ? asset('storage/'.$product->image) : asset('clinton_og.jpg'));

    $sections = [
        'Overview' => $product->overview,
        'Problem' => $product->problem,
        'My Role' => $product->role,
        'What We Built' => $product->what_we_built,
        'Technical Approach' => $product->technical_approach,
        'Outcome' => $product->outcome,
    ];
@endphp

<x-layouts.app :seo-title="$seoTitle" :seo-description="$seoDescription" :seo-image="$seoImage">
    <article class="mx-auto w-full max-w-3xl px-6 py-20 sm:py-24 lg:pt-32">
      <p class="text-sm font-medium uppercase tracking-[0.12em] text-stone-500">Work</p>
      <h1 class="mt-4 text-3xl font-semibold leading-tight text-stone-900 sm:text-4xl">{{ $product->name }}</h1>
      <p class="mt-4 text-lg leading-relaxed text-stone-600">{{ $product->short_description }}</p>

      @if ($product->website_url)
        <a href="{{ $product->website_url }}" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex items-center text-sm font-medium text-accent hover:text-teal-700">Visit {{ $product->name }} →</a>
      @endif

      <div class="mt-12 space-y-10 border-t border-stone-200 pt-10">
        @foreach ($sections as $heading => $body)
          @continue(blank($body))
          <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.1em] text-stone-500">{{ $heading }}</h2>
            <div class="mt-3 max-w-none space-y-4 text-base leading-relaxed text-stone-700">
              {!! nl2br(e($body)) !!}
            </div>
          </div>
        @endforeach

        @if (! empty($product->technologies))
          <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.1em] text-stone-500">Stack</h2>
            <ul class="mt-3 flex flex-wrap gap-2">
              @foreach ($product->technologies as $tech)
                <li class="rounded-full border border-stone-200 bg-stone-50 px-3 py-1 text-xs font-medium text-stone-600">{{ $tech }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      <div class="mt-14 border-t border-stone-200 pt-8">
        <a href="{{ route('work.index') }}" class="inline-flex items-center text-sm font-medium text-stone-600 transition-colors hover:text-accent">← Back to work</a>
      </div>
    </article>
</x-layouts.app>
