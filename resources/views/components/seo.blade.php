@props([
    'title' => 'Clinton Agburum | Founder & Technical Lead',
    'description' => 'Clinton Agburum is a founder and technical lead focused on SaaS products, systems design, and shipping reliable software.',
    'canonical' => url()->current(),
    'image' => asset('clinton_og.jpg'),
    'type' => 'website',
])

<title>{{ $title }}</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<meta name="description" content="{{ $description }}" />
<meta name="author" content="Clinton Agburum" />
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1" />
<meta name="theme-color" content="#fafaf9" />
<link rel="canonical" href="{{ $canonical }}" />

<meta property="og:type" content="{{ $type }}" />
<meta property="og:site_name" content="Clinton Agburum" />
<meta property="og:url" content="{{ $canonical }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:image" content="{{ $image }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:image" content="{{ $image }}" />

{{ $slot ?? '' }}
