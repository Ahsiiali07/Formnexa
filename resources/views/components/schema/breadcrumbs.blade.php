@props(['items' => [], 'renderVisual' => true])

@if($renderVisual && count($items) > 0)
<nav aria-label="Breadcrumb" class="no-print flex flex-wrap items-center gap-1.5 text-xs font-medium text-[#68736d]">
    @foreach($items as $idx => $crumb)
        @if($idx > 0)
            <span aria-hidden="true" class="text-[#c6d1ca]">/</span>
        @endif
        @if($idx === count($items) - 1)
            <span class="font-semibold text-[#17211b]" aria-current="page">{{ $crumb[0] }}</span>
        @else
            <a href="{{ $crumb[1] }}" class="hover:text-[#184f3a] transition-colors">{{ $crumb[0] }}</a>
        @endif
    @endforeach
</nav>
@endif

@if(count($items) > 0)
@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => collect($items)->values()->map(fn ($item, $index) => [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $item[2] ?? $item[0],
        'item' => $item[1],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
@endif
