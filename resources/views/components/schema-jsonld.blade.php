@props([
    'post' => null,
])

@php
if ($post) {
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => route('post.show', $post->slug),
        ],
        'headline' => $post->title,
        'description' => $post->meta_description,
        'image' => [
            $post->featured_image ?: asset('og-image.jpg'),
        ],
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'author' => [
            '@type' => 'Person',
            'name' => $post->author?->name ?: 'Randi Iskandar',
            'url' => route('about'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Randi Iskandar Workspace',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('favicon.ico'),
            ],
        ],
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Beranda',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $post->category?->name ?: 'Arsip',
                'item' => $post->category ? route('category.show', $post->category->slug) : route('archive.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $post->title,
                'item' => route('post.show', $post->slug),
            ],
        ],
    ];
} else {
    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'url' => route('home'),
        'name' => 'Randi Iskandar Workspace',
        'description' => 'Portofolio dan blog yang berisi tulisan bebas seputar opini, teknologi, tutorial, dan catatan pemrograman.',
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => [
                '@type' => 'EntryPoint',
                'urlTemplate' => route('search') . '?q={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Randi Iskandar Workspace',
        'url' => route('home'),
        'logo' => asset('favicon.ico'),
        'sameAs' => [
            'https://twitter.com/randiiskandar',
            'https://github.com/randiiskandar',
        ],
    ];
}
@endphp

@if ($post)
    <script type="application/ld+json">
        {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@else
    <script type="application/ld+json">
        {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endif
