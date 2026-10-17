@props([
    'title' => 'Randi Iskandar Workspace - Esai Desain & Arsitektur Web',
    'description' => 'Blog personal bertema editorial minimalist seputar tipografi, pengalaman pengguna, dan arsitektur web modern oleh Randi Iskandar.',
    'image' => null,
    'canonical' => url()->current(),
    'type' => 'website',
    'publishedTime' => null,
    'author' => 'Randi Iskandar',
])

@php
    // Gunakan og-images / og-image jika gambar utama tidak tersedia
    $defaultOgImage = asset('og-image.jpg');
    $ogImage = $image ?: $defaultOgImage;

    // Pastikan URL selalu absolut untuk crawler Open Graph (Facebook, X, WhatsApp, Telegram, LinkedIn)
    if ($ogImage && !str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
        $ogImage = url($ogImage);
    }
@endphp

<!-- Standard Meta Tags -->
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="author" content="{{ $author }}">
<link rel="canonical" href="{{ $canonical }}">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:site_name" content="Randi Iskandar Workspace">
<meta property="og:locale" content="id_ID">
@if ($publishedTime)
<meta property="article:published_time" content="{{ $publishedTime }}">
<meta property="article:author" content="{{ $author }}">
@endif

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $canonical }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $title }}">
<meta name="twitter:creator" content="@randiiskandar">
