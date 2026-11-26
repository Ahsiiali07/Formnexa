@props(['data' => []])

@if(!empty($data))
@push('schema')
<script type="application/ld+json">
{!! json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
@endif
