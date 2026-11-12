@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number . ' — Formnexa')

@section('header_actions')
<a href="{{ route('invoice.pdf', $invoice->uuid) }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-3.5 py-2 rounded-lg text-xs font-bold text-white bg-[#111111] hover:bg-[#0066FF] transition shadow-sm whitespace-nowrap shrink-0">
    <svg class="w-4 h-4 text-teal-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
    <span class="hidden sm:inline">Download PDF</span>
    <span class="sm:hidden">PDF</span>
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-3 sm:px-6 pt-6 sm:pt-10 pb-20 sm:pb-24">

    <!-- Action & Status Ribbon -->
    <div class="no-print mb-6 sm:mb-8 bg-white border border-slate-200/80 rounded-2xl sm:rounded-full shadow-sm px-4 sm:px-6 py-3.5 flex flex-wrap items-center justify-between gap-3 sm:gap-4">
        <div class="flex items-center gap-3">
            @if($invoice->status === 'paid' || $invoice->balance_due <= 0)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    PAID IN FULL
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    PAYMENT DUE
                </span>
            @endif

            <span class="text-xs text-slate-500">
                Amount Due: <strong class="text-slate-900 font-extrabold">{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }} {{ $invoice->currency }}</strong>
            </span>
        </div>

        <div class="flex items-center gap-2.5">
            @if(!empty($invoice->payment_link))
            <a href="{{ $invoice->payment_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-extrabold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Pay Now Online
            </a>
            @endif

            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.75A2.25 2.25 0 0015 1.5H9a2.25 2.25 0 00-2.25 2.25v3.456"/></svg>
                Print
            </button>

            <a href="{{ route('invoice.pdf', $invoice->uuid) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition">
                PDF
            </a>
        </div>
    </div>

    @php
        $style = $invoice->template_style ?: 'modern';
        $accent = $invoice->accent_color ?: '#0d9488';
        $cardClasses = match($style) {
            'classic' => 'bg-white rounded-none shadow-lg border-2 border-slate-800',
            'minimal' => 'bg-white rounded-xl shadow-sm border border-slate-200',
            'stripe' => 'bg-white rounded-2xl shadow-xl border border-slate-200 border-l-[12px]',
            'compact' => 'bg-white rounded-xl shadow-lg border border-slate-300',
            default => 'bg-white rounded-3xl shadow-2xl shadow-slate-200/80 border border-slate-200/80',
        };
        $cardStyle = $style === 'stripe'
            ? "border-left-color: {$accent};"
            : ($style === 'classic' ? "border-top: 6px solid {$accent};" : '');
    @endphp

    <!-- Rendered Invoice Card (Supports all 6 Custom Templates) -->
    <div class="{{ $cardClasses }} overflow-hidden print-container" style="{{ $cardStyle }}">
        
        <!-- Cover Banner / Bold Header -->
        @if(!empty($invoice->cover_image))
            <div class="h-44 sm:h-56 w-full overflow-hidden bg-slate-900">
                <img src="{{ $invoice->cover_image }}" alt="Invoice Cover" class="w-full h-full object-cover">
            </div>
        @elseif($style === 'bold')
            <div class="px-6 sm:px-12 py-8 sm:py-10 text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6" style="background-color: {{ $accent }}">
                <div class="flex items-center gap-4">
                    @if(!empty($invoice->logo_data))
                    <div class="w-18 h-18 rounded-2xl bg-white p-2 shadow-lg flex items-center justify-center overflow-hidden">
                        <img src="{{ $invoice->logo_data }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-black text-white">{{ $invoice->from_name ?: config('app.name', 'Formnexa') }}</h1>
                        <div class="text-xs text-white/80 font-sans-inter mt-1 space-y-0.5">
                            @if($invoice->from_email) <div>{{ $invoice->from_email }}</div> @endif
                            @if($invoice->from_phone) <div>{{ $invoice->from_phone }}</div> @endif
                            @if($invoice->from_tax_id) <div>Tax ID: {{ $invoice->from_tax_id }}</div> @endif
                        </div>
                    </div>
                </div>
                <div class="sm:text-right">
                    <h2 class="text-3xl font-black uppercase tracking-tight text-white">{{ $invoice->invoice_title ?: 'INVOICE' }}</h2>
                    <div class="text-xs font-bold text-white/90 mt-1"># {{ $invoice->invoice_number }}</div>
                </div>
            </div>
        @elseif($style === 'modern')
            <div class="h-16 w-full bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900"></div>
        @endif

        <div class="{{ $style === 'compact' ? 'p-5 sm:p-8 space-y-6' : 'p-6 sm:p-12 space-y-10' }}">
            <!-- Header Row (Non-Bold or Bold with Cover) -->
            @if($style !== 'bold' || !empty($invoice->cover_image))
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pb-6 {{ $style === 'classic' ? 'border-b-2 border-slate-900' : 'border-b border-slate-100' }}"
                 @if($style === 'stripe' || $style === 'compact') style="border-bottom: 2px solid {{ $accent }}" @endif>
                <div class="flex items-center gap-4">
                    @if(!empty($invoice->logo_data))
                    <div class="w-20 h-20 {{ $style === 'classic' ? 'rounded-none' : 'rounded-2xl' }} border border-slate-200 p-2 bg-white shadow-sm flex items-center justify-center overflow-hidden">
                        <img src="{{ $invoice->logo_data }}" alt="Logo" class="max-w-full max-h-full object-contain">
                    </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-black text-slate-900">{{ $invoice->from_name ?: config('app.name', 'Formnexa') }}</h1>
                        <div class="text-xs text-slate-500 font-sans-inter mt-1 space-y-0.5">
                            @if($invoice->from_email) <div>{{ $invoice->from_email }}</div> @endif
                            @if($invoice->from_phone) <div>{{ $invoice->from_phone }}</div> @endif
                            @if($invoice->from_tax_id) <div>Tax ID: {{ $invoice->from_tax_id }}</div> @endif
                        </div>
                    </div>
                </div>

                <div class="sm:text-right">
                    <h2 class="text-3xl font-black uppercase tracking-tight" style="color: {{ $accent }}">
                        {{ $invoice->invoice_title ?: 'INVOICE' }}
                    </h2>
                    <div class="text-xs font-bold text-slate-700 mt-1"># {{ $invoice->invoice_number }}</div>
                </div>
            </div>
            @endif

            <!-- Meta Ribbon -->
            @php
                $metaClasses = match($style) {
                    'classic' => 'p-4 rounded-none bg-slate-50 border border-slate-300',
                    'minimal' => 'py-4 px-1 bg-transparent border-y border-slate-200 rounded-none',
                    'compact' => 'p-3.5 rounded-lg bg-slate-50 border border-slate-300',
                    default => 'p-5 rounded-2xl bg-slate-50/80 border border-slate-200/80',
                };
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 {{ $metaClasses }} text-xs font-sans-inter">
                <div>
                    <span class="block font-bold uppercase tracking-wider text-slate-400 text-[10px] mb-1">Issue Date</span>
                    <span class="font-bold text-slate-900">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : now()->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="block font-bold uppercase tracking-wider text-slate-400 text-[10px] mb-1">Payment Terms</span>
                    <span class="font-bold text-slate-900">{{ $invoice->payment_terms ?: 'Due on receipt' }}</span>
                </div>
                <div>
                    <span class="block font-bold uppercase tracking-wider text-slate-400 text-[10px] mb-1">Due Date</span>
                    <span class="font-bold text-slate-900">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : '—' }}</span>
                </div>
                <div>
                    <span class="block font-bold uppercase tracking-wider text-slate-400 text-[10px] mb-1">Balance Due</span>
                    <span class="font-black text-base" style="color: {{ $accent }}">{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }}</span>
                </div>
            </div>

            <!-- Two-Column Parties -->
            @php
                $partyClasses = match($style) {
                    'classic' => 'p-4 rounded-none bg-white border border-slate-300',
                    'minimal' => 'py-2 px-0 border-0 bg-transparent',
                    'compact' => 'p-3.5 rounded-lg bg-slate-50/50 border border-slate-200',
                    default => 'p-5 rounded-2xl bg-slate-50/60 border border-slate-200',
                };
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs font-sans-inter">
                <div class="{{ $partyClasses }}">
                    <span class="font-bold uppercase tracking-wider text-[10px] text-slate-400 block mb-2">Billed From</span>
                    <div class="font-bold text-slate-900 text-sm mb-1">{{ $invoice->from_name ?: 'Business Name' }}</div>
                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $invoice->from_address }}</div>
                </div>

                <div class="{{ $partyClasses }}">
                    <span class="font-bold uppercase tracking-wider text-[10px] block mb-2" style="color: {{ $accent }}">Billed To (Client)</span>
                    <div class="font-bold text-slate-900 text-sm mb-1">{{ $invoice->to_name ?: 'Client Name' }}</div>
                    <div class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $invoice->to_address }}</div>
                    @if($invoice->to_email) <div class="text-slate-500 mt-1">Email: {{ $invoice->to_email }}</div> @endif
                    @if($invoice->to_phone) <div class="text-slate-500">Phone: {{ $invoice->to_phone }}</div> @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-xs font-sans-inter {{ $style === 'classic' ? 'border border-slate-300' : '' }}">
                    <thead>
                        @if($style === 'bold')
                            <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-white" style="background-color: {{ $accent }}">
                        @elseif($style === 'classic')
                            <tr class="bg-slate-100 text-slate-900 border-y-2 border-slate-900 text-left text-[11px] font-bold uppercase tracking-wider">
                        @elseif($style === 'stripe' || $style === 'compact')
                            <tr class="text-left text-[11px] font-bold uppercase tracking-wider text-slate-700" style="border-bottom: 2px solid {{ $accent }}">
                        @else
                            <tr class="border-b-2 border-slate-900 text-left text-[11px] font-bold uppercase tracking-wider text-slate-900">
                        @endif
                            <th class="py-3 px-3 w-[50%]">Item Description</th>
                            <th class="py-3 px-3 text-center w-[15%]">Qty</th>
                            <th class="py-3 px-3 text-right w-[17%]">Rate ({{ $invoice->currency_symbol }})</th>
                            <th class="py-3 px-3 text-right w-[18%]">Amount ({{ $invoice->currency_symbol }})</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($invoice->items as $idx => $item)
                        <tr class="{{ $style === 'compact' && $idx % 2 === 1 ? 'bg-slate-50/70' : '' }}">
                            <td class="{{ $style === 'compact' ? 'py-2' : 'py-3' }} px-3 font-semibold text-slate-900 whitespace-pre-line {{ $style === 'classic' ? 'border border-slate-200' : '' }}">{{ $item->description }}</td>
                            <td class="{{ $style === 'compact' ? 'py-2' : 'py-3' }} px-3 text-center text-slate-600 {{ $style === 'classic' ? 'border border-slate-200' : '' }}">{{ number_format($item->quantity, 2) + 0 }}</td>
                            <td class="{{ $style === 'compact' ? 'py-2' : 'py-3' }} px-3 text-right text-slate-600 {{ $style === 'classic' ? 'border border-slate-200' : '' }}">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="{{ $style === 'compact' ? 'py-2' : 'py-3' }} px-3 text-right font-bold text-slate-900 {{ $style === 'classic' ? 'border border-slate-200' : '' }}">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-slate-400">No items specified</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Notes & Totals -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 pt-6 border-t border-slate-200 font-sans-inter">
                <div class="md:col-span-7 space-y-4 text-xs">
                    @if(!empty($invoice->payment_link))
                    <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200">
                        <span class="font-bold text-teal-900 block mb-1">Pay Online Now</span>
                        <a href="{{ $invoice->payment_link }}" target="_blank" class="text-teal-700 font-bold underline break-all">{{ $invoice->payment_link }}</a>
                    </div>
                    @endif

                    @if($invoice->notes)
                    <div>
                        <h4 class="font-bold uppercase tracking-wider text-[10px] text-slate-400 mb-1">Notes & Payment Instructions</h4>
                        <div class="p-4 bg-slate-50 rounded-2xl text-slate-700 whitespace-pre-line leading-relaxed border border-slate-200">{{ $invoice->notes }}</div>
                    </div>
                    @endif

                    @if($invoice->terms)
                    <div>
                        <h4 class="font-bold uppercase tracking-wider text-[10px] text-slate-400 mb-1">Terms & Conditions</h4>
                        <div class="p-4 bg-slate-50 rounded-2xl text-slate-700 whitespace-pre-line leading-relaxed border border-slate-200">{{ $invoice->terms }}</div>
                    </div>
                    @endif

                    @if($invoice->signature_data)
                    <div class="pt-2">
                        <span class="font-bold uppercase tracking-wider text-[10px] text-slate-400 block mb-1">Authorized Signature</span>
                        <img src="{{ $invoice->signature_data }}" alt="Signature" class="max-h-16 max-w-[200px] object-contain">
                    </div>
                    @endif
                </div>

                <div class="md:col-span-5 bg-slate-50/80 border border-slate-200 rounded-3xl p-6 space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-bold text-slate-900">{{ $invoice->currency_symbol }}{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount_amount > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>Discount @if($invoice->discount_type === 'percent') ({{ $invoice->discount_rate + 0 }}%) @endif</span>
                        <span class="font-bold">- {{ $invoice->currency_symbol }}{{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Tax ({{ $invoice->tax_rate + 0 }}%)</span>
                        <span class="font-bold">+ {{ $invoice->currency_symbol }}{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->shipping_fee > 0)
                    <div class="flex justify-between text-slate-600">
                        <span>Shipping</span>
                        <span class="font-bold">+ {{ $invoice->currency_symbol }}{{ number_format($invoice->shipping_fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm font-extrabold text-slate-950 pt-2 border-t border-slate-200">
                        <span>Total</span>
                        <span>{{ $invoice->currency_symbol }}{{ number_format($invoice->total, 2) }}</span>
                    </div>
                    @if($invoice->amount_paid > 0)
                    <div class="flex justify-between text-emerald-700">
                        <span>Amount Paid</span>
                        <span class="font-bold">- {{ $invoice->currency_symbol }}{{ number_format($invoice->amount_paid, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-base font-black p-3 rounded-2xl bg-white border-2 text-slate-900 mt-3 shadow-sm" style="border-color: {{ $invoice->accent_color ?: '#0d9488' }}">
                        <span>Balance Due</span>
                        <span style="color: {{ $invoice->accent_color ?: '#0d9488' }}">{{ $invoice->currency_symbol }}{{ number_format($invoice->balance_due, 2) }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
