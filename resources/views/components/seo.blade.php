@props([
    'seo' => [],
    'title' => null,
    'description' => null,
    'canonical' => null,
    'image' => null,
    'robots' => null,
    'type' => null,
    'publishedTime' => null,
    'modifiedTime' => null,
])
@php
    $resolvedTitle = $title ?? ($seo['title'] ?? 'Formnexa — Invoices, Resumes & PDF Tools');
    $resolvedDesc = $description ?? ($seo['description'] ?? '');
    $resolvedCanonical = $canonical ?? ($seo['canonical'] ?? url()->current());
    $rawImage = $image ?? ($seo['image'] ?? config('seo.default_image', 'images/og/default.jpg'));
    $resolvedImage = (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://'))
        ? $rawImage
        : asset($rawImage);
    $resolvedRobots = $robots ?? ($seo['robots'] ?? 'index,follow,max-image-preview:large');
    $resolvedType = $type ?? ($seo['type'] ?? 'website');
    $resolvedPublished = $publishedTime ?? ($seo['published_time'] ?? null);
    $resolvedModified = $modifiedTime ?? ($seo['modified_time'] ?? null);
@endphp
<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $resolvedDesc }}">
<meta name="author" content="Formnexa">
<meta name="publisher" content="Formnexa">
<meta name="robots" content="{{ $resolvedRobots }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">
<meta name="theme-color" content="#ffffff">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ config('seo.site_name', 'Formnexa') }}">
<meta property="og:type" content="{{ $resolvedType }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $resolvedDesc }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:image" content="{{ $resolvedImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ config('seo.locale', 'en_US') }}">
@if($resolvedType === 'article' && !empty($resolvedPublished))
<meta property="article:published_time" content="{{ $resolvedPublished }}">
@endif
@if($resolvedType === 'article' && !empty($resolvedModified))
<meta property="article:modified_time" content="{{ $resolvedModified }}">
@endif

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $resolvedDesc }}">
<meta name="twitter:image" content="{{ $resolvedImage }}">
@if(config('seo.twitter'))
<meta name="twitter:site" content="{{ config('seo.twitter') }}">
@endif

{{-- Page-specific JSON-LD Schema pushed by views/components --}}
@stack('schema')
