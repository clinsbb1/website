<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('work.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('writing.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($articles as $article)
    <url>
        <loc>{{ route('writing.show', $article) }}</loc>
        <lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
