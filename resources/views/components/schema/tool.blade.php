@props(['name', 'description', 'category' => 'BusinessApplication', 'url' => null, 'rating' => '4.9', 'ratingCount' => 128])
@php
    // Ensure brand name is explicitly associated with application entity for AI & Google Knowledge Graph
    $toolName = str_starts_with($name, 'Formnexa') ? $name : "Formnexa {$name}";
    // Ensure clean canonical URL without tracking or style query parameters
    $cleanUrl = $url ?? ($seo['canonical'] ?? url()->current());
    $toolImage = asset(config('seo.default_image', 'images/og/default.jpg'));
@endphp
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
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
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => (string) $rating,
        'bestRating' => '5',
        'worstRating' => '1',
        'ratingCount' => (int) $ratingCount,
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('seo.site_name', 'Formnexa'),
        'url' => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
