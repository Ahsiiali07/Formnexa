@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">

    {{-- Editorial Cream Hero Header --}}
    <section class="bg-[#fbfbf7] border-b border-[#dfe4df] pt-8 sm:pt-12 pb-10 sm:pb-12 px-4 sm:px-6">
        <div class="max-w-[1200px] mx-auto space-y-6">
            @if(!empty($activeCategory))
                <x-schema.breadcrumbs :items="[
                    ['Home', url('/')],
                    ['Blog', route('blog.index')],
                    [$activeCategoryLabel ?? 'Category', route('blog.category', $activeCategory)],
                ]" />
            @else
                <x-schema.breadcrumbs :items="[
                    ['Home', url('/')],
                    ['Blog', route('blog.index')],
                ]" />
            @endif

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="max-w-2xl space-y-3">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-[#dfe4df] text-[#184f3a] font-mono text-[11px] font-bold uppercase tracking-wider">
                        Formnexa Guides &amp; Resources
                    </span>
                    <h1 class="font-display text-3xl sm:text-5xl font-extrabold text-[#17211b] tracking-[-0.04em] leading-[1.06]">
                        @if(!empty($activeCategory))
                            {{ $activeCategoryLabel }} Guides
                        @else
                            Practical guides for invoices, resumes &amp; PDFs.
                        @endif
                    </h1>
                    <p class="text-sm sm:text-[17px] text-[#536058] leading-relaxed">
                        Clear tutorials, formatting checklists, and document templates built for freelancers, small businesses, and job seekers.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ route('blog.index') }}" class="flex items-center gap-2 bg-white border border-[#dfe4df] rounded-xl p-1.5 shadow-sm">
                        <input type="search" name="q" value="{{ $search }}" placeholder="Search guides..."
                               aria-label="Search blog guides"
                               class="w-full sm:w-52 px-3 py-1.5 text-xs sm:text-sm text-[#17211b] bg-transparent border-0 focus:ring-0 focus:outline-none">
                        <button type="submit" class="px-3.5 py-2 rounded-lg bg-[#17211b] hover:bg-[#184f3a] text-white text-xs font-bold transition shrink-0 cursor-pointer">
                            Search
                        </button>
                    </form>

                    @if(Auth::check() && Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard', ['tab' => 'blogs']) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-[#184f3a] text-xs font-bold hover:bg-[#d4eedc] transition whitespace-nowrap">
                            Manage in Admin
                        </a>
                    @endif
                </div>
            </div>

            {{-- Category Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide pt-2 pb-0.5">
                <a href="{{ route('blog.index') }}"
                   class="px-4 py-2 rounded-xl text-xs sm:text-[13px] font-semibold whitespace-nowrap border transition {{ empty($activeCategory) && $search === '' ? 'bg-[#17211b] text-white border-[#17211b]' : 'bg-white text-[#536058] border-[#dfe4df] hover:border-[#184f3a] hover:text-[#17211b]' }}">
                    All Articles
                </a>
                @foreach($categories as $slug => $label)
                    <a href="{{ route('blog.category', $slug) }}"
                       class="px-4 py-2 rounded-xl text-xs sm:text-[13px] font-semibold whitespace-nowrap border transition {{ $activeCategory === $slug ? 'bg-[#17211b] text-white border-[#17211b]' : 'bg-white text-[#536058] border-[#dfe4df] hover:border-[#184f3a] hover:text-[#17211b]' }}">
                        {{ $label }}
                        @if(isset($categoryCounts[$slug]))
                            <span class="ml-1 text-[11px] opacity-70">({{ $categoryCounts[$slug] }})</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-10 sm:py-14 space-y-12">

        @if($search !== '')
            <div class="flex items-center justify-between gap-3 p-4 rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] text-xs sm:text-sm">
                <span class="text-[#536058]">
                    Showing <strong class="text-[#17211b]">{{ $posts->total() }}</strong> {{ Str::plural('result', $posts->total()) }} for <strong class="text-[#184f3a]">&ldquo;{{ $search }}&rdquo;</strong>
                </span>
                <a href="{{ route('blog.index') }}" class="font-bold text-[#184f3a] hover:underline">Clear search</a>
            </div>
        @endif

        {{-- Featured Hero Article --}}
        @if($featuredPost)
            <article class="rounded-[28px] bg-[#e8f7ed] border border-[#c8f3dc] p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-10 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="flex flex-wrap items-center gap-2.5 text-xs font-semibold">
                        <span class="px-3 py-1 rounded-full bg-white text-[#184f3a] border border-[#c8f3dc]">Featured Guide</span>
                        <a href="{{ route('blog.category', $featuredPost->category) }}" class="text-[#184f3a] hover:underline">{{ $featuredPost->category_label }}</a>
                        <span class="text-[#68736d]">&middot; {{ $featuredPost->reading_time_minutes }} min read</span>
                    </div>
                    <h2 class="font-display text-2xl sm:text-4xl font-extrabold text-[#17211b] tracking-[-0.03em] leading-[1.12]">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="hover:text-[#184f3a] transition">
                            {{ $featuredPost->title }}
                        </a>
                    </h2>
                    <p class="text-sm sm:text-base text-[#46534b] leading-relaxed max-w-2xl">
                        {{ $featuredPost->excerpt }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs sm:text-sm font-bold transition shadow-sm">
                            Read Full Guide <span aria-hidden="true">&rarr;</span>
                        </a>
                        @if($featuredPost->resolveToolUrl())
                            <a href="{{ $featuredPost->resolveToolUrl() }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white border border-[#c6d1ca] hover:border-[#184f3a] text-[#17211b] text-xs sm:text-sm font-semibold transition">
                                {{ $featuredPost->featured_tool_label ?: 'Open Free Tool' }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-4 bg-white rounded-2xl border border-[#d4eedc] p-5 space-y-3 shadow-sm">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#68736d]">In this guide</div>
                    <ul class="space-y-2 text-xs font-medium text-[#17211b]">
                        @forelse(array_slice($featuredPost->tableOfContents(), 0, 4) as $heading)
                            <li class="flex items-start gap-2">
                                <span class="text-[#184f3a] font-bold">&bull;</span>
                                <span>{{ $heading['text'] }}</span>
                            </li>
                        @empty
                            <li>Step-by-step format checklist &amp; ready-to-use PDF generator.</li>
                        @endforelse
                    </ul>
                </div>
            </article>
        @endif

        {{-- Article Cards Grid --}}
        @php
            $gridPosts = $featuredPost ? $posts->getCollection()->where('id', '!=', $featuredPost->id) : $posts->getCollection();
        @endphp

        @if($gridPosts->isEmpty() && !$featuredPost)
            <div class="text-center py-16 px-4 rounded-3xl bg-[#f8f8f3] border border-[#dfe4df] space-y-4">
                <h2 class="font-display text-xl font-bold text-[#17211b]">No matching articles found</h2>
                <p class="text-sm text-[#536058]">Try another search term or explore all document and PDF tools.</p>
                <a href="{{ route('blog.index') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-[#184f3a] text-white text-xs font-bold">
                    View All Articles
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($gridPosts as $post)
                    @php
                        $cardTint = match($post->category) {
                            'resumes' => 'bg-[#f0ebff] text-[#6142be]',
                            'pdf-tools' => 'bg-[#fff0e8] text-[#b45309]',
                            default => 'bg-[#e8f7ed] text-[#184f3a]',
                        };
                    @endphp
                    <article class="group bg-white rounded-3xl border border-[#dfe4df] hover:border-[#184f3a] p-6 flex flex-col justify-between gap-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#17211b]/5">
                        <div class="space-y-3.5">
                            <div class="flex items-center justify-between gap-2 text-[11px] font-semibold">
                                <a href="{{ route('blog.category', $post->category) }}" class="px-2.5 py-1 rounded-full {{ $cardTint }}">
                                    {{ $post->category_label }}
                                </a>
                                <span class="text-[#68736d]">{{ $post->reading_time_minutes }} min read</span>
                            </div>

                            <h2 class="font-display text-xl font-extrabold text-[#17211b] tracking-tight leading-snug group-hover:text-[#184f3a] transition">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>

                            <p class="text-xs sm:text-[14px] text-[#536058] leading-relaxed">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-[#edf1ed] flex items-center justify-between gap-2 text-xs">
                            <span class="text-[#68736d]">{{ ($post->published_at ?? $post->created_at)?->format('M j, Y') }}</span>
                            <a href="{{ route('blog.show', $post->slug) }}" class="font-bold text-[#184f3a] inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                <span>Read guide</span>
                                <span aria-hidden="true">&rarr;</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if($posts->hasPages())
            <div class="pt-4">
                {{ $posts->links() }}
            </div>
        @endif

        {{-- Bottom Soft-Mint Workspace CTA --}}
        <section class="rounded-[28px] bg-[#f8f8f3] border border-[#dfe4df] p-7 sm:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Try Formnexa Free</span>
                <h2 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight">Ready to put these guides into action?</h2>
                <p class="text-sm text-[#536058]">Create invoices, resumes, and clean PDFs right in your browser &mdash; no sign-up or watermark required.</p>
            </div>
            <div class="flex flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('invoice.studio') }}" class="px-5 py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs sm:text-sm font-bold transition">Invoice Maker</a>
                <a href="{{ route('resume.builder') }}" class="px-5 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-[#17211b] text-xs sm:text-sm font-bold transition">Resume Builder</a>
                <a href="{{ route('convert.hub') }}" class="px-5 py-3 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-[#17211b] text-xs sm:text-sm font-bold transition">PDF Converters</a>
            </div>
        </section>
    </div>
</div>
@endsection
