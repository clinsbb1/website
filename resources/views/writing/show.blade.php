@php
    $preview = $preview ?? false;
    $seoTitle = ($article->seo_title ?: $article->title).' | Clinton Agburum';
    $seoDescription = $article->seo_description ?: $article->excerpt;
    $seoCanonical = $article->resolved_canonical_url;
    $seoImage = $article->og_image_url;

    $externalLinks = collect([
        'Medium' => $article->medium_url,
        'DEV.to' => $article->devto_url,
        'Paragraph' => $article->paragraph_url,
    ])->filter();
@endphp

<x-layouts.app :seo-title="$seoTitle" :seo-description="$seoDescription" :seo-canonical="$seoCanonical" :seo-image="$seoImage" seo-type="article">
    @push('structured-data')
      <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article->title,
            'description' => $article->excerpt,
            'author' => ['@type' => 'Person', 'name' => 'Clinton Agburum'],
            'datePublished' => optional($article->published_at)->toIso8601String(),
            'mainEntityOfPage' => $seoCanonical,
        ], JSON_UNESCAPED_SLASHES) !!}
      </script>
    @endpush

    <article class="mx-auto w-full max-w-2xl px-6 py-20 sm:py-24 lg:pt-32">
      @if ($preview)
        <div class="mb-8 rounded-md border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-800">Preview — this article is not publicly visible yet.</div>
      @endif

      <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium uppercase tracking-[0.08em] text-stone-500">
        @if ($article->category)
          <span>{{ $article->category }}</span>
          <span aria-hidden="true">·</span>
        @endif
        <span>{{ $article->published_at?->format('F j, Y') ?? 'Unpublished' }}</span>
        <span aria-hidden="true">·</span>
        <span>{{ $article->reading_time }} min read</span>
      </div>

      <h1 class="mt-4 font-display text-3xl font-semibold leading-tight text-stone-900 sm:text-4xl">{{ $article->title }}</h1>

      @if ($article->feature_image)
        <img src="{{ asset('storage/'.$article->feature_image) }}" alt="{{ $article->title }}" class="mt-8 h-64 w-full rounded-xl border border-stone-200 object-cover sm:h-80" loading="lazy" />
      @endif

      <div class="mt-10">
        <x-article-content :html="$article->rendered_content" />
      </div>

      @if ($externalLinks->isNotEmpty())
        <div class="mt-12 border-t border-stone-200 pt-6 text-sm text-stone-500">
          <p class="font-medium text-stone-600">Also published on</p>
          <ul class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
            @foreach ($externalLinks as $label => $url)
              <li><a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-accent hover:text-teal-700">{{ $label }} →</a></li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="mt-12 border-t border-stone-200 pt-8">
        <a href="{{ route('writing.index') }}" class="inline-flex items-center text-sm font-medium text-stone-600 transition-colors hover:text-accent">← Back to writing</a>
      </div>
    </article>
</x-layouts.app>
