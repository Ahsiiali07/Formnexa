@props([
    'tools' => [],
    'heading' => 'Related Tools & Guides',
    'eyebrow' => 'Next Steps',
])

@if(count($tools) > 0)
<section class="no-print py-8">
    <div class="mb-5">
        <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-[#184f3a] block mb-1">{{ $eyebrow }}</span>
        <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">{{ $heading }}</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tools as $tool)
            <a href="{{ $tool['url'] }}"
               class="group rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] hover:bg-white p-5 transition flex flex-col justify-between gap-3">
                <div>
                    @if(!empty($tool['badge']))
                        <span class="inline-block px-2 py-0.5 rounded-full bg-[#e8f7ed] text-[#184f3a] text-[10px] font-mono font-bold uppercase tracking-wider mb-2">
                            {{ $tool['badge'] }}
                        </span>
                    @endif
                    <h3 class="font-display text-base font-extrabold text-[#17211b] group-hover:text-[#184f3a] transition">
                        {{ $tool['title'] }}
                    </h3>
                    @if(!empty($tool['description']))
                        <p class="mt-1.5 text-xs text-[#536058] leading-relaxed">{{ $tool['description'] }}</p>
                    @endif
                </div>
                <span class="text-xs font-bold text-[#184f3a] inline-flex items-center gap-1">
                    <span>{{ $tool['cta'] ?? 'Open tool' }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </span>
            </a>
        @endforeach
    </div>
</section>
@endif
