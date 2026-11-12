@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8f8f3] py-8 sm:py-12 px-4 sm:px-6">
    <div class="max-w-[1200px] mx-auto space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-mono font-bold uppercase tracking-wider text-[#184f3a]">Editorial Workspace</div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-tight mt-1">
                    Blog &amp; SEO Article Manager
                </h1>
                <p class="text-xs sm:text-sm text-[#536058] mt-1">
                    Publish tutorials, templates guides, and long-tail search articles. Saving updates <code class="font-mono text-xs">sitemap.xml</code> automatically.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('blog.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    View Public Blog
                </a>
                <a href="{{ route('blog.create') }}" class="px-5 py-2.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold shadow-sm transition">
                    + New Article
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-[#e8f7ed] border border-[#c8f3dc] text-xs sm:text-sm text-[#184f3a] font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-[#dfe4df] shadow-sm overflow-hidden">
            @if($posts->isEmpty())
                <div class="p-10 text-center space-y-3">
                    <p class="text-sm text-[#536058]">No blog posts yet.</p>
                    <a href="{{ route('blog.create') }}" class="inline-flex px-5 py-2.5 rounded-xl bg-[#184f3a] text-white text-xs font-bold">Write First Article</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead class="bg-[#fbfbf7] border-b border-[#dfe4df] text-[11px] font-mono uppercase tracking-wider text-[#68736d]">
                            <tr>
                                <th class="px-5 py-3.5">Article Title &amp; Focus Keyword</th>
                                <th class="px-4 py-3.5">Category</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Updated</th>
                                <th class="px-5 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#edf1ed]">
                            @foreach($posts as $post)
                                <tr class="hover:bg-[#fbfbf7]/70">
                                    <td class="px-5 py-4 max-w-md">
                                        <div class="font-bold text-[#17211b] leading-snug">{{ $post->title }}</div>
                                        <div class="text-[11px] text-[#68736d] mt-1 flex flex-wrap items-center gap-2">
                                            <span class="font-mono">/blog/{{ $post->slug }}</span>
                                            @if($post->focus_keyword)
                                                <span class="px-2 py-0.5 rounded bg-[#f8f8f3] border border-[#dfe4df] text-[#184f3a]">KW: {{ $post->focus_keyword }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-xs font-semibold text-[#536058]">
                                        {{ $post->category_label }}
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($post->is_published)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#e8f7ed] text-[#184f3a]">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">Draft</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-xs text-[#68736d]">
                                        {{ $post->updated_at?->diffForHumans() }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="inline-flex px-3 py-1.5 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b]">View</a>
                                        <a href="{{ route('blog.edit', $post) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-[#e8f7ed] text-[#184f3a] hover:bg-[#d4eedc] text-xs font-bold">Edit</a>
                                        <form method="POST" action="{{ route('blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete this article permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold cursor-pointer">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
