@extends('layouts.app')

@section('content')
<div class="min-h-[68vh] bg-[#f8f8f3] flex items-center justify-center px-4 sm:px-6 py-16">
    <div class="max-w-xl w-full bg-white rounded-[28px] border border-[#dfe4df] shadow-sm p-8 sm:p-12 text-center space-y-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] font-mono text-xs font-bold uppercase tracking-wider">
            Error 404
        </span>

        <div class="space-y-3">
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-[#17211b] tracking-tight">
                Page not found
            </h1>
            <p class="text-sm sm:text-base text-[#536058] leading-relaxed">
                The page you requested may have moved or no longer exists. Jump straight back into one of Formnexa&apos;s free workspaces below.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-left">
            <a href="{{ route('invoice.studio') }}" class="p-4 rounded-2xl bg-[#e8f7ed] border border-[#c8f3dc] hover:border-[#184f3a] transition group">
                <div class="text-sm font-bold text-[#17211b] group-hover:text-[#184f3a]">Invoice Generator &rarr;</div>
                <div class="text-xs text-[#536058] mt-0.5">12 free business docs</div>
            </a>
            <a href="{{ route('resume.builder') }}" class="p-4 rounded-2xl bg-[#f0ebff] border border-[#e3d9ff] hover:border-[#7655d7] transition group">
                <div class="text-sm font-bold text-[#17211b] group-hover:text-[#6142be]">Resume Builder &rarr;</div>
                <div class="text-xs text-[#536058] mt-0.5">ATS layouts &amp; CV import</div>
            </a>
            <a href="{{ route('convert.hub') }}" class="p-4 rounded-2xl bg-[#fff0e8] border border-[#fedac7] hover:border-[#e47857] transition group">
                <div class="text-sm font-bold text-[#17211b] group-hover:text-[#b45309]">PDF Converters &rarr;</div>
                <div class="text-xs text-[#536058] mt-0.5">10 free PDF conversion tools</div>
            </a>
            <a href="{{ route('blog.index') }}" class="p-4 rounded-2xl bg-[#f8f8f3] border border-[#dfe4df] hover:border-[#184f3a] transition group">
                <div class="text-sm font-bold text-[#17211b] group-hover:text-[#184f3a]">Guides &amp; Blog &rarr;</div>
                <div class="text-xs text-[#536058] mt-0.5">Templates &amp; workflow tips</div>
            </a>
        </div>

        <div class="pt-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#184f3a] hover:bg-[#123e2d] text-white text-sm font-bold transition">
                &larr; Back to Formnexa Home
            </a>
        </div>
    </div>
</div>
@endsection
