<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
    <channel>
        <title>Clinton Agburum — Writing</title>
        <link>{{ route('writing.index') }}</link>
        <description>Notes on software, products, technical leadership and entrepreneurship.</description>
        <language>en-us</language>
        @foreach ($articles as $article)
        <item>
            <title>{{ $article->title }}</title>
            <link>{{ $article->resolved_canonical_url }}</link>
            <guid>{{ $article->resolved_canonical_url }}</guid>
            <pubDate>{{ $article->published_at->toRfc2822String() }}</pubDate>
            @if ($article->category)
            <category>{{ $article->category }}</category>
            @endif
            <description>{!! e($article->excerpt) !!}</description>
        </item>
        @endforeach
    </channel>
</rss>
