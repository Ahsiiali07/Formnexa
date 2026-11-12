<title>{{ $seo['title'] ?? 'Formnexa — Invoices, Resumes & PDF Tools' }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
<meta name="robots" content="{{ $seo['robots'] ?? 'index,follow,max-image-preview:large' }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta name="theme-color" content="#ffffff">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ config('seo.site_name', 'Formnexa') }}">
<meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
<meta property="og:title" content="{{ $seo['title'] ?? 'Formnexa' }}">
<meta property="og:description" content="{{ $seo['description'] ?? '' }}">
<meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:image" content="{{ $seo['image'] ?? asset(config('seo.default_image', 'images/og/default.jpg')) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ config('seo.locale', 'en_US') }}">
@if(($seo['type'] ?? 'website') === 'article' && !empty($seo['published_time']))
<meta property="article:published_time" content="{{ $seo['published_time'] }}">
@endif
@if(($seo['type'] ?? 'website') === 'article' && !empty($seo['modified_time']))
<meta property="article:modified_time" content="{{ $seo['modified_time'] }}">
@endif

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seo['title'] ?? 'Formnexa' }}">
<meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
<meta name="twitter:image" content="{{ $seo['image'] ?? asset(config('seo.default_image', 'images/og/default.jpg')) }}">
@if(config('seo.twitter'))
<meta name="twitter:site" content="{{ config('seo.twitter') }}">
@endif

{{-- Page-specific JSON-LD Schema pushed by views/components --}}
@stack('schema')
