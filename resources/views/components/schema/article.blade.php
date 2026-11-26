@props(['post'])
@php
    $rawImage = $post->cover_image ?: config('seo.default_image', 'images/og/default.jpg');
    $schemaImage = (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://'))
        ? $rawImage
        : asset($rawImage);
    $publishedIso = ($post->published_at ?? $post->created_at)?->toIso8601String();
    $modifiedIso = ($post->updated_at ?? $post->published_at ?? $post->created_at)?->toIso8601String();
    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->meta_description ?: $post->excerpt,
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => route('blog.show', $post->slug),
        ],
        'image' => [$schemaImage],
        'datePublished' => $publishedIso,
        'dateModified' => $modifiedIso,
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
    ];
@endphp
<x-schema.jsonld :data="$schemaData" />
