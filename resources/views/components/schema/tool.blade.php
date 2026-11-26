@props(['name', 'description', 'category' => 'BusinessApplication', 'url' => null, 'image' => null])
@php
    // Ensure brand name is explicitly associated with application entity for AI & Google Knowledge Graph
    $toolName = str_starts_with($name, 'Formnexa') ? $name : "Formnexa {$name}";
    // Ensure clean canonical URL without tracking or style query parameters
    $cleanUrl = $url ?? ($seo['canonical'] ?? url()->current());
    $rawImage = $image ?? ($seo['image'] ?? config('seo.default_image', 'images/og/default.jpg'));
    $toolImage = (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://'))
        ? $rawImage
        : asset($rawImage);
    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'WebApplication',
        'name' => $toolName,
        'url' => $cleanUrl,
        'description' => $description,
        'applicationCategory' => $category,
        'operatingSystem' => 'All (web browser)',
        'browserRequirements' => 'Requires JavaScript. Requires HTML5.',
        'image' => $toolImage,
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'USD',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => config('seo.site_name', 'Formnexa'),
            'url' => url('/'),
        ],
    ];
@endphp
<x-schema.jsonld :data="$schemaData" />
