@extends('layouts.app')

@section('title', 'My Document History — Formnexa')

@section('header_actions')
<a href="{{ route('invoice.studio') }}" class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-black/[0.04] border border-black/[0.09] text-[#111111] transition whitespace-nowrap shrink-0">
    <svg class="w-3.5 h-3.5 text-[#0066FF] shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
    <span class="hidden sm:inline">Create Document</span>
    <span class="sm:hidden">New</span>
</a>
@endsection

@section('content')
<div class="min-h-screen bg-[#F6F5F1] py-10 sm:py-14">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">Personal Workspace</div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Document History
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Signed in as <strong class="text-slate-800">{{ Auth::user()->name }}</strong> ({{ Auth::user()->email }})
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('resume.builder') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:border-[#0066FF] text-xs font-bold text-slate-700 transition">
                    + New Resume
                </a>
                <a href="{{ route('invoice.studio') }}" class="px-5 py-2.5 rounded-xl bg-[#0066FF] hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition">
                    + New Invoice
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs sm:text-sm text-emerald-900 font-medium flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Quick Summary Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Invoices</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_count'] }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Paid Invoices</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['paid_count'] }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Draft / Pending</div>
                <div class="text-2xl font-black text-amber-700 mt-1">{{ $stats['pending_count'] }}</div>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-[#0066FF]">Cumulative Billed</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ number_format($stats['total_billed'], 2) }}</div>
            </div>
            <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-600">Saved Resumes</div>
                <div class="text-2xl font-black text-slate-900 mt-1">{{ $stats['resume_count'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Saved Resumes Section -->
        @if(isset($resumes) && $resumes->count() > 0)
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900">Saved Resumes &amp; CVs</h2>
                    <p class="text-[11px] text-slate-500">Download updated PDFs or switch templates anytime</p>
                </div>
                <a href="{{ route('resume.builder') }}" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-[#0066FF] text-white text-xs font-bold transition whitespace-nowrap">+ Build Resume</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-5">
                @foreach($resumes as $res)
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <div class="font-extrabold text-sm text-slate-900 truncate">{{ $res->title }}</div>
                                <div class="text-xs text-slate-500 truncate">{{ $res->content['personal']['full_name'] ?? '' }} &middot; {{ $res->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="w-3 h-3 rounded-full shrink-0 mt-1" style="background-color: {{ $res->accent_color ?: '#162a42' }}"></span>
                        </div>
                        <div class="flex items-center justify-between gap-2 pt-1">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('resume.builder', ['load' => $res->uuid]) }}" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#0066FF] text-xs font-bold text-slate-700 transition">Edit</a>
                                <a href="{{ route('resume.show', $res->uuid) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:border-[#0066FF] text-xs font-bold text-slate-700 transition">View</a>
                                <a href="{{ route('resume.pdf', $res->uuid) }}" class="px-3 py-1.5 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold transition">PDF</a>
                            </div>
                            <form method="POST" action="{{ route('resume.destroy', $res->uuid) }}" onsubmit="return confirm('Delete resume {{ addslashes($res->title) }} permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Search & Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('invoice.history.page') }}" class="flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 max-w-md">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search by invoice #, client name, or email..." class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-[#0066FF] focus:outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 sm:flex-initial px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">Search</button>
                    @if($search !== '')
                        <a href="{{ route('invoice.history.page', ['status' => $statusFilter]) }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold px-2">Clear</a>
                    @endif
                </div>
            </form>

            <!-- Status Filter Tabs -->
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['all' => 'All', 'draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid'] as $sKey => $sLabel)
                    <a href="{{ route('invoice.history.page', ['status' => $sKey, 'q' => $search]) }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold border transition {{ $statusFilter === $sKey ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        {{ $sLabel }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Invoices History Table (Desktop) & Cards (Mobile) -->
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            @if($invoices->count() > 0)
                <!-- Mobile Card List -->
                <div class="md:hidden divide-y divide-slate-100 font-sans-inter">
                    @foreach($invoices as $inv)
                    <div class="p-4 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $inv->accent_color ?: '#0d9488' }}"></span>
                                <div class="min-w-0">
                                    <div class="font-extrabold text-sm text-slate-900">#{{ $inv->invoice_number }}</div>
                                    <div class="text-[11px] text-slate-500 uppercase tracking-wide font-semibold">
                                        {{ $inv->invoice_title ?: 'INVOICE' }} &middot; {{ ucfirst($inv->template_style ?: 'modern') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-extrabold text-sm text-slate-900 tabular-nums">
                                    {{ $inv->currency_symbol }}{{ number_format($inv->total, 2) }}
                                </div>
                                <div class="text-[10px] font-semibold text-slate-400">{{ $inv->currency }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0">
                                <div class="font-bold text-slate-800 truncate">{{ $inv->to_name ?: 'Unnamed Client' }}</div>
                                <div class="text-[11px] text-slate-400">
                                    {{ $inv->issue_date ? $inv->issue_date->format('M d, Y') : $inv->created_at->format('M d, Y') }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route('invoice.status', $inv->uuid) }}" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-full px-3 py-1 border cursor-pointer focus:outline-none {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($inv->status === 'sent' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                    <option value="draft" {{ $inv->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ $inv->status === 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="paid" {{ $inv->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="overdue" {{ $inv->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </form>
                        </div>

                        <div class="flex items-center justify-between gap-2 pt-1">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('invoice.studio', ['load' => $inv->uuid]) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">Edit</a>
                                <a href="{{ route('invoice.show', $inv->uuid) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">View</a>
                                <a href="{{ route('invoice.pdf', $inv->uuid) }}" class="px-3 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold transition">PDF</a>
                            </div>
                            <form method="POST" action="{{ route('invoice.destroy', $inv->uuid) }}" onsubmit="return confirm('Delete document #{{ $inv->invoice_number }} permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-5">Document</th>
                                <th class="py-3.5 px-5">Client / Recipient</th>
                                <th class="py-3.5 px-5">Date</th>
                                <th class="py-3.5 px-5">Amount</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm font-sans-inter">
                            @foreach($invoices as $inv)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $inv->accent_color ?: '#0d9488' }}"></span>
                                        <div>
                                            <div class="font-extrabold text-slate-900">#{{ $inv->invoice_number }}</div>
                                            <div class="text-[11px] text-slate-500 uppercase tracking-wide font-semibold">
                                                {{ $inv->invoice_title ?: 'INVOICE' }} &middot; {{ ucfirst($inv->template_style ?: 'modern') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <div class="font-bold text-slate-800">{{ $inv->to_name ?: 'Unnamed Client' }}</div>
                                    @if($inv->to_email)
                                        <div class="text-xs text-slate-400">{{ $inv->to_email }}</div>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-xs text-slate-600">
                                    <div>{{ $inv->issue_date ? $inv->issue_date->format('M d, Y') : $inv->created_at->format('M d, Y') }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $inv->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="py-4 px-5 font-extrabold text-slate-900 tabular-nums">
                                    {{ $inv->currency_symbol }}{{ number_format($inv->total, 2) }}
                                    <span class="text-[10px] font-semibold text-slate-400">{{ $inv->currency }}</span>
                                </td>
                                <td class="py-4 px-5">
                                    <form method="POST" action="{{ route('invoice.status', $inv->uuid) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-full px-3 py-1 border cursor-pointer focus:outline-none {{ $inv->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($inv->status === 'sent' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                            <option value="draft" {{ $inv->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="sent" {{ $inv->status === 'sent' ? 'selected' : '' }}>Sent</option>
                                            <option value="paid" {{ $inv->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="overdue" {{ $inv->status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('invoice.studio', ['load' => $inv->uuid]) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                            Edit
                                        </a>
                                        <a href="{{ route('invoice.show', $inv->uuid) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                            View
                                        </a>
                                        <a href="{{ route('invoice.pdf', $inv->uuid) }}" class="px-3 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 text-xs font-bold transition">
                                            PDF
                                        </a>
                                        <form method="POST" action="{{ route('invoice.destroy', $inv->uuid) }}" onsubmit="return confirm('Delete document #{{ $inv->invoice_number }} permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($invoices->hasPages())
                <div class="p-5 border-t border-slate-100">
                    {{ $invoices->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-16 px-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4 text-2xl">📄</div>
                    <h3 class="text-base font-extrabold text-slate-900">No documents saved yet</h3>
                    <p class="text-xs text-slate-500 mt-1 mb-6 max-w-sm mx-auto">
                        Create an invoice, quote, receipt, or choose a template in the studio. Any document you save, download, or email while signed in will appear here.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('invoice.studio') }}" class="px-5 py-2.5 rounded-xl bg-[#0066FF] text-white text-xs font-bold shadow-md hover:bg-blue-700 transition">Create First Invoice</a>
                        <a href="{{ route('templates.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-slate-200 transition">Browse Templates</a>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
