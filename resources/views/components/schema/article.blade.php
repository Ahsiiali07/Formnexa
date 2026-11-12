@props(['post'])
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->meta_description ?: $post->excerpt,
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('blog.show', $post->slug),
    ],
    'image' => [asset($post->cover_image ?: config('seo.default_image', 'images/formnexa-brand-kit.png'))],
    'datePublished' => ($post->published_at ?? $post->created_at)?->toIso8601String(),
    'dateModified' => $post->updated_at?->toIso8601String(),
    'author' => [
        '@type' => 'Organization',
        'name' => $post->author?->name ?: config('seo.site_name', 'Formnexa Editorial'),
        'url' => url('/'),
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => config('seo.site_name', 'Formnexa'),
        'url' => url('/'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/formnexa-logo.png'),
        ],
    ],
    'articleSection' => $post->category_label,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
