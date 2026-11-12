@extends('layouts.app')

@section('content')
<x-schema.article :post="$post" />

<div class="bg-white min-h-screen">
    {{-- Article Header Banner --}}
    <header class="bg-[#fbfbf7] border-b border-[#dfe4df] pt-8 sm:pt-11 pb-9 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1160px] mx-auto space-y-5">
            <x-schema.breadcrumbs :items="[
                ['Home', url('/')],
                ['Blog', route('blog.index')],
                [$post->category_label, route('blog.category', $post->category)],
                [\Illuminate\Support\Str::limit($post->title, 48), route('blog.show', $post->slug)],
            ]" />

            <div class="max-w-3xl space-y-4">
                <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                    <a href="{{ route('blog.category', $post->category) }}"
                       class="px-3 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] border border-[#c8f3dc] hover:bg-[#d4eedc] transition">
                        {{ $post->category_label }}
                    </a>
                    <span class="text-[#68736d]">{{ $post->reading_time_minutes }} min read</span>
                    <span class="text-[#68736d]">&bull; Updated {{ ($post->updated_at ?? $post->published_at ?? $post->created_at)?->format('M j, Y') }}</span>
                    @if(!$post->is_published)
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">Draft Preview</span>
                    @endif
                </div>

                <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-[#17211b] tracking-[-0.04em] leading-[1.08]">
                    {{ $post->title }}
                </h1>

                <p class="text-base sm:text-lg text-[#536058] leading-relaxed">
                    {{ $post->excerpt }}
                </p>
            </div>
        </div>
    </header>

    {{-- Main Reading Grid + Sticky Sidebar --}}
    <div class="max-w-[1160px] mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            {{-- Article Prose Body --}}
            <article class="lg:col-span-8 min-w-0">
                <div class="max-w-none">
                    {!! $renderedHtml !!}
                </div>

                {{-- Mid/End Tool Callout Box --}}
                @if($post->resolveToolUrl())
                    <div class="mt-10 rounded-[24px] bg-[#e8f7ed] border border-[#c8f3dc] p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Try It Free Now</span>
                            <h2 class="font-display text-xl sm:text-2xl font-extrabold text-[#17211b] tracking-tight">
                                {{ $post->featured_tool_label ?: 'Open the Matching Formnexa Tool' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-[#46534b]">Free PDF download, zero watermarks, and no account required.</p>
                        </div>
                        <a href="{{ $post->resolveToolUrl() }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs sm:text-sm font-bold transition shrink-0 whitespace-nowrap">
                            <span>Open Tool</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @endif
            </article>

            {{-- Sticky Desktop Sidebar: Table of Contents + Quick Tool Box --}}
            <aside class="lg:col-span-4 lg:sticky lg:top-24 space-y-6">
                @if(count($toc) > 0)
                    <div class="rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] p-5 space-y-3">
                        <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">On This Page</div>
                        <nav aria-label="Article contents" class="space-y-2 text-xs sm:text-[13px]">
                            @foreach($toc as $heading)
                                <a href="#{{ $heading['id'] }}"
                                   class="block font-medium text-[#46534b] hover:text-[#184f3a] transition leading-snug">
                                    {{ $heading['text'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>
                @endif

                <div class="rounded-2xl bg-[#193e30] text-white p-6 space-y-4">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#c8f3dc]">Formnexa Suite</div>
                    <h3 class="font-display text-xl font-extrabold tracking-tight">Generate documents in seconds</h3>
                    <p class="text-xs text-[#b7d2c4] leading-relaxed">
                        Build invoices, quotes, ATS resumes, or convert PDF files cleanly without sign-up friction.
                    </p>
                    <div class="space-y-2 pt-1 text-xs font-bold">
                        <a href="{{ route('invoice.studio') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                            <span>Free Invoice Generator</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('resume.builder') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                            <span>ATS Resume Builder</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <a href="{{ route('convert.hub') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 transition">
                            <span>10 PDF Converters</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>

        {{-- Related Guides --}}
        @if($relatedPosts->isNotEmpty())
            <section class="mt-16 pt-12 border-t border-[#dfe4df] space-y-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Keep Reading</span>
                        <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mt-1">Related guides</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="text-xs sm:text-sm font-bold text-[#184f3a] hover:underline whitespace-nowrap">
                        All Guides &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach($relatedPosts as $rel)
                        <article class="bg-[#f8f8f3] rounded-2xl border border-[#dfe4df] hover:border-[#184f3a] p-5 flex flex-col justify-between gap-4 transition">
                            <div class="space-y-2.5">
                                <span class="text-[11px] font-bold text-[#184f3a]">{{ $rel->category_label }}</span>
                                <h3 class="font-display text-lg font-extrabold text-[#17211b] leading-snug">
                                    <a href="{{ route('blog.show', $rel->slug) }}" class="hover:text-[#184f3a] transition">{{ $rel->title }}</a>
                                </h3>
                                <p class="text-xs text-[#536058] leading-relaxed">{{ \Illuminate\Support\Str::limit($rel->excerpt, 120) }}</p>
                            </div>
                            <a href="{{ route('blog.show', $rel->slug) }}" class="text-xs font-bold text-[#184f3a] hover:underline">
                                Read article &rarr;
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
