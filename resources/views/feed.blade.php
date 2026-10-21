{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<rss version="2.0" 
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ config('app.name', 'Randi Iskandar') }}</title>
        <link>{{ url('/') }}</link>
        <description>Blog personal berisi tulisan seputar opini, teknologi, tutorial, dan catatan oleh Randi Iskandar.</description>
        <language>id</language>
        <lastBuildDate>{{ $lastBuildDate->toRfc2822String() }}</lastBuildDate>
        <atom:link href="{{ url('/feed') }}" rel="self" type="application/rss+xml" />

        @foreach($posts as $post)
            <item>
                <title>{{ htmlspecialchars($post->title, ENT_XML1, 'UTF-8') }}</title>
                <link>{{ url('/' . $post->slug) }}</link>
                <guid isPermaLink="true">{{ url('/' . $post->slug) }}</guid>
                <pubDate>{{ $post->published_at ? $post->published_at->toRfc2822String() : $post->created_at->toRfc2822String() }}</pubDate>
                <dc:creator>{{ htmlspecialchars($post->author?->name ?: 'Randi Iskandar', ENT_XML1, 'UTF-8') }}</dc:creator>
                @php
                    $feedCats = $post->categories->isNotEmpty() ? $post->categories : ($post->category ? collect([$post->category]) : collect());
                @endphp
                @foreach($feedCats as $fCat)
                    <category>{{ htmlspecialchars($fCat->name, ENT_XML1, 'UTF-8') }}</category>
                @endforeach
                <description><![CDATA[{{ $post->excerpt ?: $post->meta_description }}]]></description>
                @if(!empty($post->featured_image))
                    <enclosure url="{{ str_starts_with($post->featured_image, 'http') ? $post->featured_image : url($post->featured_image) }}" type="image/jpeg" length="0" />
                @endif
            </item>
        @endforeach
    </channel>
</rss>
