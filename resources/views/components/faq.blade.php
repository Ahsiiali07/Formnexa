@props(['items' => [], 'heading' => 'Frequently Asked Questions', 'eyebrow' => 'Good to know'])

@if(count($items) > 0)
<section aria-labelledby="faq-section-heading" class="no-print" x-data="{ openIdx: 0 }">
    <div class="mb-6">
        <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1.5">{{ $eyebrow }}</span>
        <h2 id="faq-section-heading" class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight">{{ $heading }}</h2>
    </div>

    <div class="border-t border-[#dfe4df]">
        @foreach($items as $idx => $item)
            <div class="border-b border-[#dfe4df]">
                <button type="button"
                        @click="openIdx = (openIdx === {{ $idx }} ? null : {{ $idx }})"
                        :aria-expanded="(openIdx === {{ $idx }}).toString()"
                        class="w-full py-5 flex items-center justify-between gap-4 text-left font-semibold text-base sm:text-[17px] text-[#17211b] hover:text-[#184f3a] transition cursor-pointer">
                    <span>{{ $item['q'] }}</span>
                    <span aria-hidden="true" class="text-xl leading-none text-[#184f3a] transition-transform duration-200 shrink-0" :class="openIdx === {{ $idx }} ? 'rotate-45' : ''">+</span>
                </button>
                <div x-show="openIdx === {{ $idx }}" x-transition class="pb-5 text-sm sm:text-[15px] text-[#536058] leading-relaxed">
                    {{ $item['a'] }}
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($items)->map(fn ($item) => [
        '@type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $item['a'],
        ],
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
</script>
@endpush
@endif
