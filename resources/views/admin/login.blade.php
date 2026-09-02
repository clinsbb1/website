<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex,nofollow" />
  <title>Sign in | Admin — Clinton Agburum</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-stone-50 px-6 text-stone-900 antialiased">
  <div class="w-full max-w-sm">
    <p class="mb-8 text-center text-sm font-semibold tracking-wide text-stone-900">Clinton Agburum</p>
    <div class="rounded-2xl border border-stone-200 bg-white p-8 shadow-sm shadow-stone-100/70">
      <h1 class="text-lg font-semibold text-stone-900">Admin sign in</h1>

      @if ($errors->any())
        <div class="mt-4 rounded-md border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
        @csrf
        <div>
          <label for="email" class="block text-sm font-medium text-stone-700">Email</label>
          <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}"
            class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
          <input type="password" name="password" id="password" required
            class="mt-1.5 block w-full rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
        </div>
        <label class="flex items-center gap-2 text-sm text-stone-600">
          <input type="checkbox" name="remember" class="rounded border-stone-300 text-accent focus:ring-accent">
          Remember me
        </label>
        <button type="submit" class="w-full rounded-md bg-stone-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-stone-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2">
          Sign in
        </button>
      </form>
    </div>
  </div>
</body>
</html>
