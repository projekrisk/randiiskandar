@props([
    'title' => 'Randi Iskandar - Catatan Digital',
    'description' => 'Blog personal oleh Randi Iskandar berisi tulisan seputar opini, teknologi, tutorial, dan catatan digital.',
    'image' => null,
    'canonical' => url()->current(),
    'type' => 'website',
    'publishedTime' => null,
    'author' => 'Randi Iskandar',
])

@php
    $defaultOgImage = asset('og-image.jpg');
    $ogImage = $image ?: $defaultOgImage;

    if ($ogImage && !str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
        $ogImage = url($ogImage);
    }
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="author" content="{{ $author }}">
<link rel="canonical" href="{{ $canonical }}">

<meta name="application-name" content="Randi Iskandar">
<meta name="apple-mobile-web-app-title" content="Randi Iskandar">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:site_name" content="Randi Iskandar">
<meta property="og:locale" content="id_ID">
@if ($publishedTime)
<meta property="article:published_time" content="{{ $publishedTime }}">
<meta property="article:author" content="{{ $author }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonical }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:creator" content="@randiiskandar">
