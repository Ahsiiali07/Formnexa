@props(['name', 'description', 'category' => 'BusinessApplication'])
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => $name,
    'url' => url()->current(),
    'description' => $description,
    'applicationCategory' => $category,
    'operatingSystem' => 'Any (web browser)',
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
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
