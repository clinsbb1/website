@props(['article'])

<article class="rounded-xl border border-stone-200 bg-white p-6 transition duration-200 hover:border-stone-300">
  <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs font-medium uppercase tracking-[0.08em] text-stone-500">
    @if ($article->category)
      <span>{{ $article->category->name }}</span>
      <span aria-hidden="true">·</span>
    @endif
    <span>{{ $article->published_at?->format('M j, Y') }}</span>
    <span aria-hidden="true">·</span>
    <span>{{ $article->reading_time }} min read</span>
  </div>
  <h3 class="mt-3 text-base font-semibold text-stone-900">{{ $article->title }}</h3>
  @if ($article->excerpt)
    <p class="mt-3 text-sm leading-relaxed text-stone-600">{{ $article->excerpt }}</p>
  @endif
  <a href="{{ route('writing.show', $article) }}" class="mt-5 inline-flex items-center text-sm font-medium text-accent hover:text-teal-700">Read →</a>
</article>
