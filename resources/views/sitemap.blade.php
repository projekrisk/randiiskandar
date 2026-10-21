{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ $latestPostUpdate?->toAtomString() ?: now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('archive.index') }}</loc>
        <lastmod>{{ $latestPostUpdate?->toAtomString() ?: now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('about') }}</loc>
        @if($aboutPage?->updated_at)
            <lastmod>{{ $aboutPage->updated_at->toAtomString() }}</lastmod>
        @endif
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <url>
        <loc>{{ route('contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

    @foreach($posts as $post)
        <url>
            <loc>{{ url('/' . $post->slug) }}</loc>
            <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.9</priority>
            @if(!empty($post->featured_image))
                <image:image>
                    <image:loc>{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : url($post->featured_image) }}</image:loc>
                    <image:title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</image:title>
                    @if(!empty($post->featured_image_caption))
                        <image:caption>{{ htmlspecialchars($post->featured_image_caption, ENT_XML1, 'UTF-8') }}</image:caption>
                    @endif
                </image:image>
            @endif
        </url>
    @endforeach

    @foreach($categories as $category)
        <url>
            <loc>{{ route('category.show', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @php
        $renderedPageUrls = [];
    @endphp
    @foreach($pages as $page)
        @if(!in_array($page->slug, ['about', 'tentang']))
            @php
                $pageUrl = in_array($page->slug, ['privacy', 'privasi']) 
                    ? route('privacy') 
                    : (in_array($page->slug, ['terms', 'term']) 
                        ? route('terms') 
                        : ($page->slug === 'disclaimer' ? route('disclaimer') : url('/' . $page->slug)));
            @endphp
            @if(!in_array($pageUrl, $renderedPageUrls))
                @php
                    $renderedPageUrls[] = $pageUrl;
                @endphp
                <url>
                    <loc>{{ $pageUrl }}</loc>
                    <lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>0.6</priority>
                    @if(!empty($page->featured_image))
                        <image:image>
                            <image:loc>{{ str_starts_with($page->featured_image, 'http') ? $page->featured_image : url($page->featured_image) }}</image:loc>
                            <image:title>{{ htmlspecialchars($page->title, ENT_XML1, 'UTF-8') }}</image:title>
                        </image:image>
                    @endif
                </url>
            @endif
        @endif
    @endforeach

</urlset>
