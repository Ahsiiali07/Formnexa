@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8f8f3] py-8 sm:py-12 px-4 sm:px-6"
     x-data="{
        title: @js(old('title', $post->title ?? '')),
        slug: @js(old('slug', $post->slug ?? '')),
        metaTitle: @js(old('meta_title', $post->meta_title ?? '')),
        metaDescription: @js(old('meta_description', $post->meta_description ?? '')),
        excerpt: @js(old('excerpt', $post->excerpt ?? ''))
     }">
    <div class="max-w-5xl mx-auto space-y-6">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ $backUrl ?? route('blog.manage') }}" class="text-xs font-bold text-[#184f3a] hover:underline">&larr; {{ $backLabel ?? 'Back to Blog Manager' }}</a>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mt-1">
                    {{ $isEdit ? 'Edit SEO Article' : 'Create New SEO Article' }}
                </h1>
            </div>
            @if($isEdit)
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="px-4 py-2 rounded-xl bg-white border border-[#dfe4df] text-xs font-bold text-[#17211b] hover:border-[#184f3a]">
                    Preview Post &rarr;
                </a>
            @endif
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 space-y-1 font-medium">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST"
              action="{{ $formAction ?? ($isEdit ? route('blog.update', $post) : route('blog.store')) }}"
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            {{-- Left Column: Article Content --}}
            <div class="lg:col-span-8 bg-white rounded-3xl border border-[#dfe4df] p-6 sm:p-8 space-y-5 shadow-sm">
                <div>
                    <label for="title" class="block font-mono text-[11px] font-bold uppercase tracking-wider text-[#536058] mb-1.5">Article H1 Title *</label>
                    <input id="title" type="text" name="title" x-model="title" required
                           placeholder="e.g. How to Write an ATS-Friendly Resume in 2026"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#dfe4df] text-sm font-semibold text-[#17211b] focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="slug" class="block font-mono text-[11px] font-bold uppercase tracking-wider text-[#536058] mb-1.5">URL Slug (auto if blank)</label>
                        <input id="slug" type="text" name="slug" x-model="slug"
                               placeholder="ats-friendly-resume-guide"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#dfe4df] font-mono text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                    </div>

                    <div>
                        <label for="category" class="block font-mono text-[11px] font-bold uppercase tracking-wider text-[#536058] mb-1.5">Category *</label>
                        <select id="category" name="category" class="w-full px-3.5 py-2.5 rounded-xl border border-[#dfe4df] text-xs sm:text-sm font-semibold text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                            @foreach($categories as $catKey => $catLabel)
                                <option value="{{ $catKey }}" @selected(old('category', $post->category) === $catKey)>{{ $catLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="excerpt" class="block font-mono text-[11px] font-bold uppercase tracking-wider text-[#536058] mb-1.5">Short Summary / Excerpt *</label>
                    <textarea id="excerpt" name="excerpt" x-model="excerpt" rows="3" required
                              placeholder="2-sentence intro displayed on the blog directory and article header..."
                              class="w-full px-4 py-2.5 rounded-xl border border-[#dfe4df] text-sm text-[#17211b] focus:border-[#184f3a] focus:outline-none"></textarea>
                </div>

                <div>
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-1.5">
                        <label for="content" class="font-mono text-[11px] font-bold uppercase tracking-wider text-[#536058]">Article Body (Markdown Supported) *</label>
                        <span class="text-[11px] text-[#68736d]">Supports <code class="font-mono">## H2</code>, <code class="font-mono">### H3</code>, lists, tables &amp; <code class="font-mono">[links](/invoice-generator)</code></span>
                    </div>
                    <textarea id="content" name="content" rows="16" required
                              placeholder="## Section Heading&#10;&#10;Write practical guidance and link naturally to Formnexa tools..."
                              class="w-full px-4 py-3 rounded-xl border border-[#dfe4df] font-mono text-xs sm:text-[13px] leading-relaxed text-[#17211b] focus:border-[#184f3a] focus:outline-none">{{ old('content', $post->content) }}</textarea>
                </div>
            </div>

            {{-- Right Column: SEO Controls, SERP Preview & Tool Link --}}
            <div class="lg:col-span-4 space-y-5">
                <div class="bg-white rounded-3xl border border-[#dfe4df] p-5 sm:p-6 space-y-4 shadow-sm">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">On-Page SEO Settings</div>

                    <div>
                        <label for="focus_keyword" class="block text-xs font-bold text-[#17211b] mb-1">Focus Keyword</label>
                        <input id="focus_keyword" type="text" name="focus_keyword" value="{{ old('focus_keyword', $post->focus_keyword) }}"
                               placeholder="e.g. international invoice format Asia USA Europe"
                               class="w-full px-3.5 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <label for="meta_title" class="font-bold text-[#17211b]">SEO Meta Title</label>
                            <span class="font-mono text-[11px]" :class="(metaTitle || title).length > 60 ? 'text-amber-600' : 'text-[#68736d]'" x-text="(metaTitle || title).length + ' / 60'"></span>
                        </div>
                        <input id="meta_title" type="text" name="meta_title" x-model="metaTitle"
                               placeholder="Keep around 55-60 chars"
                               class="w-full px-3.5 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <label for="meta_description" class="font-bold text-[#17211b]">SEO Meta Description</label>
                            <span class="font-mono text-[11px]" :class="(metaDescription || excerpt).length > 160 ? 'text-amber-600' : 'text-[#68736d]'" x-text="(metaDescription || excerpt).length + ' / 155'"></span>
                        </div>
                        <textarea id="meta_description" name="meta_description" x-model="metaDescription" rows="3"
                                  placeholder="Concise summary with target keyword (120-155 chars)"
                                  class="w-full px-3.5 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none"></textarea>
                    </div>

                    {{-- Live Google SERP Preview --}}
                    <div class="p-3.5 rounded-2xl bg-[#fbfbf7] border border-[#dfe4df] space-y-1">
                        <div class="text-[10px] font-mono uppercase tracking-wider text-[#68736d]">SERP Snippet Preview</div>
                        <div class="text-[11px] text-[#184f3a] truncate">{{ url('/blog') }}/<span x-text="slug || 'article-slug'"></span></div>
                        <div class="text-sm font-bold text-[#1a0dab] leading-snug line-clamp-1" x-text="metaTitle || title || 'Article Title | Formnexa'"></div>
                        <div class="text-xs text-[#536058] leading-snug line-clamp-2" x-text="metaDescription || excerpt || 'Your article meta description will preview here...'"></div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-[#dfe4df] p-5 sm:p-6 space-y-4 shadow-sm">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Tool CTA &amp; Publishing</div>

                    <div>
                        <label for="featured_tool_route" class="block text-xs font-bold text-[#17211b] mb-1">Linked Formnexa Tool</label>
                        <select id="featured_tool_route" name="featured_tool_route" class="w-full px-3 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                            <option value="">None</option>
                            @foreach($toolOptions as $routeKey => $label)
                                <option value="{{ $routeKey }}" @selected(old('featured_tool_route', $post->featured_tool_route) === $routeKey)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="featured_tool_label" class="block text-xs font-bold text-[#17211b] mb-1">CTA Button Text</label>
                        <input id="featured_tool_label" type="text" name="featured_tool_label" value="{{ old('featured_tool_label', $post->featured_tool_label) }}"
                               placeholder="e.g. Open Free Invoice Generator"
                               class="w-full px-3.5 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] focus:border-[#184f3a] focus:outline-none">
                    </div>

                    <label class="flex items-center gap-2.5 text-xs font-bold text-[#17211b] cursor-pointer pt-1">
                        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? true))
                               class="rounded border-[#c6d1ca] text-[#184f3a] focus:ring-[#184f3a]">
                        <span>Active (Published &amp; in sitemap.xml)</span>
                    </label>

                    <button type="submit" class="w-full py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer">
                        {{ $isEdit ? 'Save & Update Sitemap' : 'Publish Article & Update Sitemap' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
