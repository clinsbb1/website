<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <x-seo
    :title="$seoTitle ?? 'Clinton Agburum | Founder & Technical Lead'"
    :description="$seoDescription ?? 'Clinton Agburum is a founder and technical lead focused on SaaS products, systems design, and shipping reliable software.'"
    :canonical="$seoCanonical ?? url()->current()"
    :image="$seoImage ?? asset('clinton_og.jpg')"
    :type="$seoType ?? 'website'"
  >
    @stack('structured-data')
  </x-seo>

  <script type="application/ld+json">{!! json_encode([
      '@context' => 'https://schema.org',
      '@graph' => [
          [
              '@type' => 'Person',
              'name' => 'Clinton Agburum',
              'jobTitle' => 'Founder and Technical Lead',
              'description' => 'Founder and technical lead focused on SaaS products, systems design, and technical leadership.',
              'sameAs' => [
                  'https://www.linkedin.com/in/clinton-agburum/',
                  'https://github.com/clinsbb1',
                  'https://thefoxylabs.com',
              ],
              'knowsAbout' => ['SaaS', 'Product Development', 'Engineering Leadership', 'Systems Design'],
          ],
          [
              '@type' => 'WebSite',
              'name' => 'Clinton Agburum',
              'description' => 'Personal website of Clinton Agburum, founder and technical lead.',
          ],
      ],
  ], JSON_UNESCAPED_SLASHES) !!}</script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('scripts')
</head>
<body class="bg-stone-50 text-stone-900 antialiased selection:bg-accent/10">
  <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-md focus:bg-white focus:px-3 focus:py-2 focus:text-sm focus:shadow">Skip to content</a>

  <header class="sticky top-0 z-40 border-b border-stone-200/70 bg-stone-50/95 backdrop-blur supports-[backdrop-filter]:bg-stone-50/80">
    <nav class="mx-auto w-full max-w-6xl px-6 py-4 lg:px-8" aria-label="Main navigation">
      <div class="flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="text-sm font-semibold tracking-wide text-stone-900">Clinton Agburum</a>
        <button
          id="menu-toggle"
          type="button"
          class="inline-flex items-center rounded-md border border-stone-300 bg-white px-3 py-2 text-sm font-medium text-stone-700 transition hover:border-stone-400 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent md:hidden"
          aria-expanded="false"
          aria-controls="mobile-menu"
          aria-label="Toggle menu"
        >
          Menu
        </button>
        <ul class="hidden items-center gap-7 text-sm text-stone-600 md:flex">
          <li><a href="{{ route('home') }}#about" class="transition-colors duration-200 hover:text-accent focus:outline-none focus:text-accent">About</a></li>
          <li><a href="{{ route('work.index') }}" class="transition-colors duration-200 hover:text-accent focus:outline-none focus:text-accent">Work</a></li>
          <li><a href="{{ route('writing.index') }}" class="transition-colors duration-200 hover:text-accent focus:outline-none focus:text-accent">Writing</a></li>
          <li><a href="{{ route('home') }}#contact" class="transition-colors duration-200 hover:text-accent focus:outline-none focus:text-accent">Contact</a></li>
        </ul>
      </div>
      <div id="mobile-menu" class="hidden border-t border-stone-200/80 pt-4 md:hidden">
        <ul class="space-y-3 text-sm text-stone-700">
          <li><a href="{{ route('home') }}#about" class="block rounded-md px-2 py-1.5 transition-colors hover:text-accent">About</a></li>
          <li><a href="{{ route('work.index') }}" class="block rounded-md px-2 py-1.5 transition-colors hover:text-accent">Work</a></li>
          <li><a href="{{ route('writing.index') }}" class="block rounded-md px-2 py-1.5 transition-colors hover:text-accent">Writing</a></li>
          <li><a href="{{ route('home') }}#contact" class="block rounded-md px-2 py-1.5 transition-colors hover:text-accent">Contact</a></li>
        </ul>
      </div>
    </nav>
  </header>

  <main id="main-content">
    {{ $slot }}
  </main>

  <footer class="border-t border-stone-200/80 py-8">
    <div class="mx-auto flex w-full max-w-6xl flex-col gap-2 px-6 text-sm text-stone-500 sm:flex-row sm:items-center sm:justify-between lg:px-8">
      <p>&copy; {{ now()->year }} Clinton Agburum</p>
      <p>Built thoughtfully, over time.</p>
    </div>
  </footer>
</body>
</html>
