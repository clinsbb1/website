@props(['title' => 'Admin'])
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex,nofollow" />
  <title>{{ $title }} | Admin — Clinton Agburum</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('scripts')
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
  <div class="mx-auto flex w-full max-w-6xl flex-col gap-8 px-6 py-8 lg:flex-row lg:px-8">
    <aside class="lg:w-52 lg:shrink-0">
      <div class="flex items-center justify-between lg:block">
        <a href="{{ route('admin.overview') }}" class="text-sm font-semibold tracking-wide text-stone-900">Clinton Agburum</a>
        <p class="mt-1 hidden text-xs uppercase tracking-[0.1em] text-stone-500 lg:block">Admin</p>
      </div>
      <nav class="mt-6 flex gap-2 overflow-x-auto lg:mt-8 lg:flex-col lg:gap-1 lg:overflow-visible" aria-label="Admin navigation">
        @php
            $current = match (true) {
                request()->routeIs('admin.overview') => 'overview',
                request()->routeIs('admin.products.*') => 'products',
                request()->routeIs('admin.articles.*') => 'articles',
                request()->routeIs('admin.categories.*') => 'categories',
                default => null,
            };
        @endphp
        <a href="{{ route('admin.overview') }}" @class(['whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium', 'bg-stone-900 text-white' => $current === 'overview', 'text-stone-600 hover:bg-stone-100' => $current !== 'overview'])>Overview</a>
        <a href="{{ route('admin.products.index') }}" @class(['whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium', 'bg-stone-900 text-white' => $current === 'products', 'text-stone-600 hover:bg-stone-100' => $current !== 'products'])>Products</a>
        <a href="{{ route('admin.articles.index') }}" @class(['whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium', 'bg-stone-900 text-white' => $current === 'articles', 'text-stone-600 hover:bg-stone-100' => $current !== 'articles'])>Articles</a>
        <a href="{{ route('admin.categories.index') }}" @class(['whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium', 'bg-stone-900 text-white' => $current === 'categories', 'text-stone-600 hover:bg-stone-100' => $current !== 'categories'])>Categories</a>
        <form method="POST" action="{{ route('admin.logout') }}" class="lg:mt-2">
          @csrf
          <button type="submit" class="w-full whitespace-nowrap rounded-md px-3 py-2 text-left text-sm font-medium text-stone-600 hover:bg-stone-100">Sign out</button>
        </form>
      </nav>
    </aside>

    <main class="min-w-0 flex-1">
      @if (session('status'))
        <div class="mb-6 rounded-md border border-accent/20 bg-accent/10 px-4 py-2 text-sm font-medium text-accent">{{ session('status') }}</div>
      @endif
      {{ $slot }}
    </main>
  </div>
</body>
</html>
