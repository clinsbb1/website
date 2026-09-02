@php
    $seoTitle = 'Clinton Agburum | Founder & Technical Lead';
    $seoDescription = 'Clinton Agburum is a founder and technical lead focused on SaaS products, systems design, and shipping reliable software.';
@endphp

<x-layouts.app :seo-title="$seoTitle" :seo-description="$seoDescription">
    <section class="mx-auto w-full max-w-6xl px-6 pb-20 pt-20 sm:pb-24 sm:pt-28 lg:px-8 lg:pt-32">
      <div class="max-w-5xl">
        <p class="mb-5 text-sm font-medium uppercase tracking-[0.14em] text-stone-500">Founder · Technical Lead</p>
        <h1 class="text-balance text-4xl font-semibold leading-tight text-stone-900 sm:text-5xl sm:leading-tight lg:text-6xl lg:leading-[1.08]">
          Designing systems that power real-world businesses.
        </h1>
        <p class="mt-8 max-w-3xl text-lg leading-relaxed text-stone-600 sm:text-xl">
          I'm a founder and technical lead focused on designing and shipping scalable platforms. My work spans product development, engineering leadership, and systems that help businesses operate more effectively.
        </p>
        <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
          <a href="{{ route('work.index') }}" class="inline-flex items-center justify-center rounded-md bg-stone-900 px-5 py-3 text-sm font-medium text-white transition duration-200 hover:bg-stone-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-stone-50">
            View My Work
          </a>
          <a href="{{ route('writing.index') }}" class="inline-flex items-center justify-center rounded-md border border-stone-300 bg-white px-5 py-3 text-sm font-medium text-stone-700 transition duration-200 hover:border-stone-400 hover:text-stone-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 focus-visible:ring-offset-stone-50">
            Read My Writing
          </a>
        </div>
        <p class="mt-8 text-sm text-stone-500">Currently building at <a href="https://thefoxylabs.com" target="_blank" rel="noopener noreferrer" class="font-medium text-stone-700 transition-colors hover:text-accent">Foxy Labs Technology</a>.</p>
      </div>
    </section>

    <section id="about" class="scroll-mt-24 border-t border-stone-200/80 sm:scroll-mt-28">
      <div class="mx-auto grid w-full max-w-6xl gap-12 px-6 py-20 sm:py-24 lg:grid-cols-12 lg:gap-10 lg:px-8">
        <div class="space-y-3 lg:col-span-3">
          <h2 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">About</h2>
          <p class="max-w-xs text-sm leading-relaxed text-stone-500">I build products with a long-term mindset, focusing on clear systems, strong foundations, and teams that can ship consistently.</p>
        </div>
        <div class="lg:col-span-9">
          <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm shadow-stone-100/70 sm:p-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-start">
              <div>
                <p class="max-w-3xl text-lg leading-relaxed text-stone-700">
                  I started out building websites and software and gradually became more interested in the systems behind them — how products scale, how teams ship reliably, and how technology fits into the way a business actually operates.
                </p>
                <p class="mt-4 max-w-3xl text-lg leading-relaxed text-stone-700">
                  Today, I lead Foxy Labs Technology and spend most of my time designing products, making technical decisions, and helping teams turn complicated operational problems into software that works.
                </p>
                <p class="mt-4 max-w-3xl text-lg leading-relaxed text-stone-700">
                  I care about long-term thinking, practical execution, strong technical foundations, and building products that solve real operational problems.
                </p>
                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                  <div class="rounded-lg border border-stone-200 bg-stone-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.1em] text-stone-500">Core Focus</p>
                    <p class="mt-1 text-sm text-stone-700">Product engineering, systems design, architecture and technical leadership.</p>
                  </div>
                  <div class="rounded-lg border border-stone-200 bg-stone-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.1em] text-stone-500">Working Style</p>
                    <p class="mt-1 text-sm text-stone-700">Calm execution, thoughtful systems design and clear priorities.</p>
                  </div>
                </div>
              </div>
              <figure class="mx-auto w-full max-w-sm lg:mx-0 lg:max-w-none">
                <img
                  src="{{ asset('clinton.JPG') }}"
                  alt="Portrait of Clinton Agburum"
                  class="h-72 w-full rounded-xl border border-stone-200 object-cover sm:h-80 lg:h-96"
                  loading="lazy"
                />
                <figcaption class="mt-3 text-xs uppercase tracking-[0.08em] text-stone-500">Clinton Agburum</figcaption>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="scroll-mt-24 border-t border-stone-200/80 sm:scroll-mt-28">
      <div class="mx-auto w-full max-w-6xl px-6 py-20 sm:py-24 lg:px-8">
        <div class="mb-10 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
          <div class="max-w-3xl">
            <h2 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Selected Work</h2>
            <p class="mt-4 text-lg leading-relaxed text-stone-700">A focused portfolio of products built around operational clarity, speed, and reliability.</p>
          </div>
        </div>

        @if ($products->isNotEmpty())
          <div class="grid gap-4 sm:grid-cols-2 lg:gap-5">
            @foreach ($products as $product)
              <x-product-card :product="$product" />
            @endforeach
          </div>
        @endif

        <div class="mt-10">
          <a href="{{ route('work.index') }}" class="inline-flex items-center text-sm font-medium text-accent transition-colors hover:text-teal-700">View all work →</a>
        </div>
      </div>
    </section>

    <section class="scroll-mt-24 border-t border-stone-200/80 sm:scroll-mt-28">
      <div class="mx-auto w-full max-w-6xl px-6 py-20 sm:py-24 lg:px-8">
        <div class="mb-10 max-w-2xl">
          <h2 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Writing</h2>
          <p class="mt-4 text-lg leading-relaxed text-stone-700">Notes on building products, leading technical work, and figuring things out along the way.</p>
        </div>

        @if ($articles->isNotEmpty())
          <div class="grid gap-4 sm:grid-cols-3 lg:gap-5">
            @foreach ($articles as $article)
              <x-article-card :article="$article" />
            @endforeach
          </div>
        @else
          <p class="text-sm text-stone-500">New writing is on its way — check back soon.</p>
        @endif

        <div class="mt-10">
          <a href="{{ route('writing.index') }}" class="inline-flex items-center text-sm font-medium text-accent transition-colors hover:text-teal-700">View all writing →</a>
        </div>
      </div>
    </section>

    <section id="contact" class="scroll-mt-24 border-t border-stone-200/80 sm:scroll-mt-28">
      <div class="mx-auto w-full max-w-6xl px-6 py-20 sm:py-24 lg:px-8">
        <div class="max-w-3xl">
          <h2 class="text-sm font-semibold uppercase tracking-[0.12em] text-stone-500">Contact</h2>
          <p class="mt-4 text-lg leading-relaxed text-stone-700">Have something interesting in mind? I'm open to thoughtful conversations with founders, product teams, collaborators and investors.</p>
        </div>
        <ul class="mt-8 space-y-4 text-sm sm:text-base">
          <li>
            <a href="mailto:clintonagburum@gmail.com" class="text-stone-700 transition-colors hover:text-accent">Email: clintonagburum@gmail.com</a>
          </li>
          <li>
            <a href="https://www.linkedin.com/in/clinton-agburum/" target="_blank" rel="noopener noreferrer" class="text-stone-700 transition-colors hover:text-accent">LinkedIn: linkedin.com/in/clinton-agburum</a>
          </li>
          <li>
            <a href="https://github.com/clinsbb1" target="_blank" rel="noopener noreferrer" class="text-stone-700 transition-colors hover:text-accent">GitHub: github.com/clinsbb1</a>
          </li>
          <li>
            <a href="https://x.com/clintonagburum" target="_blank" rel="noopener noreferrer" class="text-stone-700 transition-colors hover:text-accent">X/Twitter: x.com/clintonagburum</a>
          </li>
        </ul>
      </div>
    </section>
</x-layouts.app>
