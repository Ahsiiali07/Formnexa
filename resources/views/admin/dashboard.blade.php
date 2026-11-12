@extends('layouts.app')

@section('header_actions')
<a href="{{ route('admin.blogs.create') }}"
   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-[10px] text-xs font-bold bg-[#184f3a] hover:bg-[#123e2d] text-white transition whitespace-nowrap shrink-0">
    <span>+ Add Blog</span>
</a>
@endsection

@section('content')
<div class="min-h-screen bg-[#f8f8f3] py-7 sm:py-10 px-4 sm:px-6"
     x-data="{ tab: @js($activeTab ?: 'overview') }">
    <div class="max-w-[1280px] mx-auto space-y-7">

        {{-- Admin Header --}}
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#e8f7ed] border border-[#c8f3dc] text-[#184f3a] font-mono text-[11px] font-bold uppercase tracking-wider mb-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#184f3a]"></span>
                    Formnexa Control Center
                </div>
                <h1 class="font-display text-2xl sm:text-4xl font-extrabold text-[#17211b] tracking-[-0.03em]">
                    Admin Dashboard
                </h1>
                <p class="text-xs sm:text-sm text-[#536058] mt-1">
                    Manage Blogs (Add, Edit, Active / Inactive, Delete) and inspect live data across Invoices, Resumes, Users &amp; SEO.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('blog.index') }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-white border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] transition">
                    Open Public Blog &rarr;
                </a>
                <a href="{{ route('admin.blogs.create') }}"
                   class="px-5 py-2.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold shadow-sm transition">
                    + Add New Blog
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-[#e8f7ed] border border-[#c8f3dc] text-xs sm:text-sm text-[#184f3a] font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs sm:text-sm text-rose-700 font-semibold">
                {{ session('error') }}
            </div>
        @endif

        {{-- Top Module KPIs --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <button type="button" @click="tab = 'blogs'" class="text-left bg-white rounded-2xl border border-[#dfe4df] hover:border-[#184f3a] p-5 shadow-sm transition cursor-pointer">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">Blog Posts</div>
                <div class="font-display text-3xl font-extrabold text-[#17211b] mt-1">{{ $stats['blogs_total'] }}</div>
                <div class="text-[11px] text-[#536058] mt-1">
                    <span class="font-bold text-[#184f3a]">{{ $stats['blogs_active'] }} Active</span> &bull; {{ $stats['blogs_inactive'] }} Inactive
                </div>
            </button>

            <button type="button" @click="tab = 'invoices'" class="text-left bg-white rounded-2xl border border-[#dfe4df] hover:border-[#184f3a] p-5 shadow-sm transition cursor-pointer">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#68736d]">Invoices &amp; Docs</div>
                <div class="font-display text-3xl font-extrabold text-[#17211b] mt-1">{{ $stats['invoices_total'] }}</div>
                <div class="text-[11px] text-[#536058] mt-1">
                    <span class="font-bold text-[#184f3a]">{{ $stats['invoices_paid'] }} Paid</span> &bull; {{ $stats['invoices_pending'] }} Draft/Due
                </div>
            </button>

            <button type="button" @click="tab = 'resumes'" class="text-left bg-white rounded-2xl border border-[#dfe4df] hover:border-[#7655d7] p-5 shadow-sm transition cursor-pointer">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#6142be]">Resumes Built</div>
                <div class="font-display text-3xl font-extrabold text-[#17211b] mt-1">{{ $stats['resumes_total'] }}</div>
                <div class="text-[11px] text-[#536058] mt-1">{{ $stats['resume_templates_count'] }} templates &bull; 10 engines</div>
            </button>

            <button type="button" @click="tab = 'users'" class="text-left bg-white rounded-2xl border border-[#dfe4df] hover:border-[#184f3a] p-5 shadow-sm transition cursor-pointer">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#68736d]">Registered Users</div>
                <div class="font-display text-3xl font-extrabold text-[#17211b] mt-1">{{ $stats['users_total'] }}</div>
                <div class="text-[11px] text-[#536058] mt-1">{{ $stats['users_admin'] }} {{ \Illuminate\Support\Str::plural('Admin', $stats['users_admin']) }}</div>
            </button>

            <button type="button" @click="tab = 'seo'" class="col-span-2 sm:col-span-1 text-left bg-white rounded-2xl border border-[#dfe4df] hover:border-[#b45309] p-5 shadow-sm transition cursor-pointer">
                <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#b45309]">Tools &amp; Sitemap</div>
                <div class="font-display text-3xl font-extrabold text-[#17211b] mt-1">{{ $sitemapMeta['urls_count'] }}</div>
                <div class="text-[11px] text-[#536058] mt-1">{{ $stats['generators_count'] }} Docs + {{ $stats['converters_count'] }} Converters</div>
            </button>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide pb-1">
            @foreach([
                'overview' => 'Overview',
                'blogs' => 'Blogs (' . $stats['blogs_total'] . ')',
                'invoices' => 'Invoices & Docs (' . $stats['invoices_total'] . ')',
                'resumes' => 'Resumes (' . $stats['resumes_total'] . ')',
                'users' => 'Users (' . $stats['users_total'] . ')',
                'seo' => 'Tools & SEO',
            ] as $tabKey => $tabLabel)
                <button type="button" @click="tab = '{{ $tabKey }}'"
                        :class="tab === '{{ $tabKey }}' ? 'bg-[#17211b] text-white border-[#17211b]' : 'bg-white text-[#536058] border-[#dfe4df] hover:border-[#184f3a] hover:text-[#17211b]'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-[13px] font-bold whitespace-nowrap border transition cursor-pointer">
                    {{ $tabLabel }}
                </button>
            @endforeach
        </div>

        {{-- TAB 1: OVERVIEW --}}
        <div x-show="tab === 'overview'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {{-- Quick Blogs Status Card --}}
                <div class="lg:col-span-7 bg-white rounded-3xl border border-[#dfe4df] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="font-display text-lg font-extrabold text-[#17211b]">Recent Blogs &amp; Status</h2>
                            <p class="text-xs text-[#68736d]">Quickly edit or toggle Active / Inactive status</p>
                        </div>
                        <button type="button" @click="tab = 'blogs'" class="text-xs font-bold text-[#184f3a] hover:underline cursor-pointer">
                            Manage All Blogs &rarr;
                        </button>
                    </div>

                    <div class="divide-y divide-[#edf1ed]">
                        @foreach($blogs->take(5) as $bItem)
                            <div class="py-3 flex flex-wrap items-center justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-xs sm:text-sm text-[#17211b] truncate">{{ $bItem->title }}</div>
                                    <div class="text-[11px] text-[#68736d] mt-0.5">{{ $bItem->category_label }} &bull; /blog/{{ $bItem->slug }}</div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form method="POST" action="{{ route('admin.blogs.toggle', $bItem) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded-full text-[11px] font-bold transition cursor-pointer {{ $bItem->is_published ? 'bg-[#e8f7ed] text-[#184f3a] hover:bg-[#d4eedc]' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                            {{ $bItem->is_published ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.blogs.edit', $bItem) }}" class="px-2.5 py-1 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] text-[11px] font-bold text-[#17211b] hover:border-[#184f3a]">Edit</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Website Module Summaries --}}
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-lg font-extrabold text-[#17211b]">Documents by Type</h2>
                            <button type="button" @click="tab = 'invoices'" class="text-xs font-bold text-[#184f3a] hover:underline cursor-pointer">View Docs &rarr;</button>
                        </div>
                        @if($docTypeBreakdown->isEmpty())
                            <p class="text-xs text-[#68736d]">No documents saved yet. Any invoice, quote, or receipt created in the studio will appear here.</p>
                        @else
                            <div class="grid grid-cols-2 gap-2.5">
                                @foreach($docTypeBreakdown as $row)
                                    <div class="p-3 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-[#17211b] truncate">{{ $row->invoice_title }}</span>
                                        <span class="font-mono text-xs font-extrabold text-[#184f3a]">{{ $row->total }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-[#193e30] text-white rounded-3xl p-6 space-y-3">
                        <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#c8f3dc]">SEO &amp; Sitemap Status</div>
                        <div class="flex items-baseline justify-between">
                            <div class="font-display text-2xl font-extrabold">{{ $sitemapMeta['urls_count'] }} Indexed URLs</div>
                            <span class="text-xs text-[#b7d2c4]">{{ $sitemapMeta['updated_at'] ?: 'Not generated' }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.seo.sitemap') }}">
                            @csrf
                            <button type="submit" class="mt-2 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#c8f3dc] hover:bg-white text-[#17211b] text-xs font-extrabold transition cursor-pointer">
                                Regenerate sitemap.xml Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: BLOGS MANAGEMENT (ADD / EDIT / ACTIVE-INACTIVE / DELETE) --}}
        <div x-show="tab === 'blogs'" x-cloak class="space-y-5">
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.dashboard', ['tab' => 'blogs', 'blog_status' => 'all', 'blog_q' => $blogSearch]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition {{ $blogStatus === 'all' ? 'bg-[#17211b] text-white border-[#17211b]' : 'bg-[#f8f8f3] text-[#536058] border-[#dfe4df]' }}">
                        All ({{ $stats['blogs_total'] }})
                    </a>
                    <a href="{{ route('admin.dashboard', ['tab' => 'blogs', 'blog_status' => 'active', 'blog_q' => $blogSearch]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition {{ $blogStatus === 'active' ? 'bg-[#184f3a] text-white border-[#184f3a]' : 'bg-[#e8f7ed] text-[#184f3a] border-[#c8f3dc]' }}">
                        Active ({{ $stats['blogs_active'] }})
                    </a>
                    <a href="{{ route('admin.dashboard', ['tab' => 'blogs', 'blog_status' => 'inactive', 'blog_q' => $blogSearch]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs font-bold border transition {{ $blogStatus === 'inactive' ? 'bg-slate-800 text-white border-slate-800' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                        Inactive ({{ $stats['blogs_inactive'] }})
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5">
                        <input type="hidden" name="tab" value="blogs">
                        <input type="hidden" name="blog_status" value="{{ $blogStatus }}">
                        <input type="search" name="blog_q" value="{{ $blogSearch }}" placeholder="Search title, slug, keyword..."
                               class="px-3 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] w-48 sm:w-60 focus:border-[#184f3a] focus:outline-none">
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#17211b] text-white text-xs font-bold cursor-pointer">Filter</button>
                    </form>
                    <a href="{{ route('admin.blogs.create') }}" class="px-4 py-2 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold transition">
                        + Add Blog
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-[#dfe4df] shadow-sm overflow-hidden">
                @if($blogs->isEmpty())
                    <div class="p-12 text-center space-y-3">
                        <p class="text-sm text-[#536058]">No blog posts match the current filter.</p>
                        <a href="{{ route('admin.blogs.create') }}" class="inline-flex px-5 py-2.5 rounded-xl bg-[#184f3a] text-white text-xs font-bold">Create Blog Post</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead class="bg-[#fbfbf7] border-b border-[#dfe4df] text-[11px] font-mono uppercase tracking-wider text-[#68736d]">
                                <tr>
                                    <th class="px-5 py-3.5">Blog Title &amp; URL</th>
                                    <th class="px-4 py-3.5">Category</th>
                                    <th class="px-4 py-3.5">State (Active / Inactive)</th>
                                    <th class="px-4 py-3.5">Updated</th>
                                    <th class="px-5 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#edf1ed]">
                                @foreach($blogs as $post)
                                    <tr class="hover:bg-[#fbfbf7]/80">
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
                                            <form method="POST" action="{{ route('admin.blogs.toggle', $post) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        title="Click to switch Active / Inactive"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition cursor-pointer {{ $post->is_published ? 'bg-[#e8f7ed] text-[#184f3a] border-[#c8f3dc] hover:bg-[#d4eedc]' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $post->is_published ? 'bg-[#184f3a]' : 'bg-slate-400' }}"></span>
                                                    <span>{{ $post->is_published ? 'Active' : 'Inactive' }}</span>
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-xs text-[#68736d]">
                                            {{ $post->updated_at?->diffForHumans() }}
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-right space-x-1.5">
                                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                                               class="inline-flex px-3 py-1.5 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b]">
                                                View
                                            </a>
                                            <a href="{{ route('admin.blogs.edit', $post) }}"
                                               class="inline-flex px-3 py-1.5 rounded-lg bg-[#e8f7ed] text-[#184f3a] hover:bg-[#d4eedc] text-xs font-bold">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.blogs.destroy', $post) }}" class="inline"
                                                  onsubmit="return confirm('Delete this blog permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold cursor-pointer">
                                                    Delete
                                                </button>
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

        {{-- TAB 3: INVOICES & BUSINESS DOCUMENTS MODULE --}}
        <div x-show="tab === 'invoices'" x-cloak class="space-y-5">
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-lg font-extrabold text-[#17211b]">All Invoices &amp; Business Documents</h2>
                    <p class="text-xs text-[#68736d]">Showing latest 40 records created across guests and registered accounts</p>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-1.5">
                    <input type="hidden" name="tab" value="invoices">
                    <input type="search" name="inv_q" value="{{ $invoiceSearch }}" placeholder="Search #, sender, client..."
                           class="px-3 py-2 rounded-xl border border-[#dfe4df] text-xs text-[#17211b] w-48 sm:w-64 focus:border-[#184f3a] focus:outline-none">
                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-[#17211b] text-white text-xs font-bold cursor-pointer">Search</button>
                </form>
            </div>

            <div class="bg-white rounded-3xl border border-[#dfe4df] shadow-sm overflow-hidden">
                @if($invoices->isEmpty())
                    <div class="p-10 text-center text-sm text-[#536058]">No invoices or documents found.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead class="bg-[#fbfbf7] border-b border-[#dfe4df] text-[11px] font-mono uppercase tracking-wider text-[#68736d]">
                                <tr>
                                    <th class="px-5 py-3.5">Document</th>
                                    <th class="px-4 py-3.5">From &rarr; To</th>
                                    <th class="px-4 py-3.5">Total</th>
                                    <th class="px-4 py-3.5">Owner</th>
                                    <th class="px-4 py-3.5">Status</th>
                                    <th class="px-5 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#edf1ed]">
                                @foreach($invoices as $inv)
                                    <tr class="hover:bg-[#fbfbf7]/80">
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <div class="font-bold text-[#17211b]">#{{ $inv->invoice_number }}</div>
                                            <div class="text-[11px] text-[#68736d]">{{ $inv->invoice_title }} &bull; {{ $inv->created_at?->format('M j, Y') }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-[#46534b] max-w-xs truncate">
                                            {{ $inv->from_name ?: 'Sender' }} &rarr; <strong class="text-[#17211b]">{{ $inv->to_name ?: 'Client' }}</strong>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap font-bold text-[#17211b]">
                                            {{ $inv->currency_symbol }}{{ number_format((float) $inv->total, 2) }} <span class="text-[11px] text-[#68736d]">{{ $inv->currency }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap text-xs text-[#68736d]">
                                            {{ $inv->user?->email ?? 'Guest session' }}
                                        </td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <form method="POST" action="{{ route('admin.invoices.toggle', $inv) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-2.5 py-1 rounded-full text-[11px] font-bold cursor-pointer {{ $inv->status === 'paid' ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ strtoupper($inv->status ?: 'draft') }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                            <a href="{{ route('invoice.show', $inv->uuid) }}" target="_blank" class="inline-flex px-2.5 py-1 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] text-xs font-bold text-[#17211b]">View</a>
                                            <a href="{{ route('invoice.pdf', $inv->uuid) }}" class="inline-flex px-2.5 py-1 rounded-lg bg-[#e8f7ed] text-[#184f3a] text-xs font-bold">PDF</a>
                                            <form method="POST" action="{{ route('admin.invoices.destroy', $inv) }}" class="inline" onsubmit="return confirm('Delete this document permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold cursor-pointer">Delete</button>
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

        {{-- TAB 4: RESUMES MODULE --}}
        <div x-show="tab === 'resumes'" x-cloak class="space-y-5">
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-5 shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-lg font-extrabold text-[#17211b]">Resumes &amp; CVs Created</h2>
                    <p class="text-xs text-[#68736d]">Latest 40 resumes built or imported across users and guests</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($resumeStyleBreakdown as $stRow)
                        <span class="px-3 py-1 rounded-full bg-[#f0ebff] text-[#6142be] text-xs font-bold">
                            {{ $resumeStyles[$stRow->template_style]['name'] ?? $stRow->template_style }}: {{ $stRow->total }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-[#dfe4df] shadow-sm overflow-hidden">
                @if($resumes->isEmpty())
                    <div class="p-10 text-center text-sm text-[#536058]">No resumes created yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs sm:text-sm">
                            <thead class="bg-[#fbfbf7] border-b border-[#dfe4df] text-[11px] font-mono uppercase tracking-wider text-[#68736d]">
                                <tr>
                                    <th class="px-5 py-3.5">Resume Title</th>
                                    <th class="px-4 py-3.5">Candidate</th>
                                    <th class="px-4 py-3.5">Template Layout</th>
                                    <th class="px-4 py-3.5">Owner</th>
                                    <th class="px-5 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#edf1ed]">
                                @foreach($resumes as $res)
                                    <tr class="hover:bg-[#fbfbf7]/80">
                                        <td class="px-5 py-3.5">
                                            <div class="font-bold text-[#17211b]">{{ $res->title }}</div>
                                            <div class="text-[11px] text-[#68736d]">{{ $res->created_at?->diffForHumans() }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-[#46534b]">
                                            {{ $res->content['personal']['full_name'] ?? 'Unnamed' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-xs font-semibold text-[#6142be]">
                                            {{ $resumeStyles[$res->template_style]['name'] ?? $res->template_style }}
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-[#68736d]">
                                            {{ $res->user?->email ?? 'Guest session' }}
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap text-right space-x-1.5">
                                            <a href="{{ route('resume.show', $res->uuid) }}" target="_blank" class="inline-flex px-2.5 py-1 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] text-xs font-bold text-[#17211b]">View</a>
                                            <a href="{{ route('resume.pdf', $res->uuid) }}" class="inline-flex px-2.5 py-1 rounded-lg bg-[#f0ebff] text-[#6142be] text-xs font-bold">PDF</a>
                                            <form method="POST" action="{{ route('admin.resumes.destroy', $res) }}" class="inline" onsubmit="return confirm('Delete this resume permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold cursor-pointer">Delete</button>
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

        {{-- TAB 5: USERS MODULE --}}
        <div x-show="tab === 'users'" x-cloak class="bg-white rounded-3xl border border-[#dfe4df] shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-[#dfe4df] flex items-center justify-between">
                <div>
                    <h2 class="font-display text-lg font-extrabold text-[#17211b]">Registered Accounts &amp; Roles</h2>
                    <p class="text-xs text-[#68736d]">View document volume per account and manage admin privileges</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead class="bg-[#fbfbf7] border-b border-[#dfe4df] text-[11px] font-mono uppercase tracking-wider text-[#68736d]">
                        <tr>
                            <th class="px-5 py-3.5">User</th>
                            <th class="px-4 py-3.5">Invoices</th>
                            <th class="px-4 py-3.5">Resumes</th>
                            <th class="px-4 py-3.5">Blogs</th>
                            <th class="px-4 py-3.5">Role</th>
                            <th class="px-5 py-3.5 text-right">Manage Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#edf1ed]">
                        @foreach($users as $usr)
                            <tr class="hover:bg-[#fbfbf7]/80">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-[#17211b]">{{ $usr->name }}</div>
                                    <div class="text-[11px] text-[#68736d]">{{ $usr->email }} &bull; Joined {{ $usr->created_at?->format('M j, Y') }}</div>
                                </td>
                                <td class="px-4 py-3.5 font-mono font-bold text-[#17211b]">{{ $usr->invoices_count }}</td>
                                <td class="px-4 py-3.5 font-mono font-bold text-[#17211b]">{{ $usr->resumes_count }}</td>
                                <td class="px-4 py-3.5 font-mono font-bold text-[#17211b]">{{ $usr->blog_posts_count }}</td>
                                <td class="px-4 py-3.5">
                                    @if($usr->is_admin)
                                        <span class="px-2.5 py-1 rounded-full bg-[#184f3a] text-white text-[11px] font-bold">Admin</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-[#f8f8f3] border border-[#dfe4df] text-[#536058] text-[11px] font-bold">Member</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @if($usr->id === Auth::id())
                                        <span class="text-xs text-[#68736d] font-medium">Current session</span>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $usr) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] text-xs font-bold text-[#17211b] cursor-pointer">
                                                {{ $usr->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 6: TOOLS CATALOG & SEO HEALTH --}}
        <div x-show="tab === 'seo'" x-cloak class="space-y-6">
            <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="text-[11px] font-mono font-bold uppercase tracking-wider text-[#184f3a]">XML Sitemap &amp; Indexing</div>
                    <h2 class="font-display text-xl font-extrabold text-[#17211b]">public/sitemap.xml ({{ $sitemapMeta['urls_count'] }} URLs)</h2>
                    <p class="text-xs text-[#536058]">
                        Includes homepage, 12 generators, 10 converters, template hubs, active blog articles &amp; categories. Auth/private routes use <code class="font-mono">noindex</code>.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] text-xs font-bold text-[#17211b]">Open sitemap.xml</a>
                    <form method="POST" action="{{ route('admin.seo.sitemap') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-xs font-bold cursor-pointer">
                            Regenerate Sitemap
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 shadow-sm space-y-3">
                    <h3 class="font-display text-base font-extrabold text-[#17211b]">12 Business Document Generators</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach($generators as $slug => $gen)
                            <a href="{{ $slug === 'invoice-generator' ? route('invoice.studio') : route('invoice.generator', $slug) }}" target="_blank"
                               class="px-3 py-2 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] font-semibold text-[#17211b] truncate">
                                {{ $gen['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-[#dfe4df] p-6 shadow-sm space-y-3">
                    <h3 class="font-display text-base font-extrabold text-[#17211b]">{{ count($converterTools) }} PDF Converter Tools</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach($converterTools as $tool)
                            <a href="{{ route('convert.tool', $tool['slug']) }}" target="_blank"
                               class="px-3 py-2 rounded-xl bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] font-semibold text-[#17211b] truncate">
                                {{ $tool['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
