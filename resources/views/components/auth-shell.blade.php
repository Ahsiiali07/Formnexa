@props([
    'badge' => 'Formnexa Account',
    'title',
    'subtitle',
    'panelTitle' => 'Your document workspace, synced everywhere.',
    'panelSubtitle' => 'Keep your invoices, receipts, quotes, and ATS resumes protected behind verified email security.',
    'steps' => [],
    'activeStep' => 1,
    'footerBackRoute' => 'invoice.studio',
    'footerBackLabel' => 'Continue as Guest in Document Studio',
])

<div class="relative min-h-[calc(100vh-120px)] bg-[#f8f8f3] overflow-hidden py-8 sm:py-12 px-4 sm:px-6 flex items-center justify-center">
    <!-- Subtle Ambient Background Accents -->
    <div class="pointer-events-none absolute -top-28 -left-28 w-96 h-96 rounded-full bg-[#184f3a]/8 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-24 w-[28rem] h-[28rem] rounded-full bg-[#377d60]/10 blur-3xl"></div>

    <div class="relative w-full max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden bg-white border border-[#dfe4df] shadow-2xl shadow-[#17211b]/8">

            <!-- Left Botanical Security Showcase Panel (Desktop) -->
            <div class="hidden lg:flex lg:col-span-5 flex-col justify-between bg-[#17211b] text-white p-9 relative overflow-hidden">
                <div class="pointer-events-none absolute inset-0 opacity-20"
                     style="background-image: radial-gradient(circle at 20% 20%, #377d60 0%, transparent 55%), radial-gradient(circle at 85% 85%, #184f3a 0%, transparent 50%);"></div>

                <div class="relative z-10 space-y-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group">
                        <span class="fn-brand-mark" style="width: 38px; height: 38px; border-radius: 11px; gap: 3px;" aria-hidden="true">
                            <i class="b1" style="width: 4px; height: 18px;"></i>
                            <i class="b2" style="width: 4px; height: 13px;"></i>
                            <i class="b3" style="width: 4px; height: 19px;"></i>
                        </span>
                        <span class="font-display font-extrabold text-[20px] tracking-[-0.04em] text-white">
                            form<span class="text-[#78c8a2]">nexa</span>
                        </span>
                    </a>

                    <div class="space-y-3 pt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 border border-white/15 text-[11px] font-mono uppercase tracking-wider text-[#a7e3c5]">
                            <x-icon name="shield-check" class="w-3.5 h-3.5" />
                            <span>{{ $badge }}</span>
                        </span>

                        <h2 class="font-display text-2xl font-extrabold leading-tight tracking-[-0.025em] text-white">
                            {{ $panelTitle }}
                        </h2>
                        <p class="text-sm text-[#c7d3cc] leading-relaxed">
                            {{ $panelSubtitle }}
                        </p>
                    </div>

                    @if(!empty($steps))
                        <div class="pt-3 space-y-2.5">
                            <div class="font-mono text-[10px] uppercase tracking-widest text-[#8ca095]">Verification Flow</div>
                            <div class="space-y-2">
                                @foreach($steps as $index => $stepLabel)
                                    @php
                                        $stepNumber = $index + 1;
                                        $isDone = $stepNumber < $activeStep;
                                        $isCurrent = $stepNumber === $activeStep;
                                    @endphp
                                    <div class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border {{ $isCurrent ? 'bg-[#184f3a]/70 border-[#4ba07b]/50 text-white' : ($isDone ? 'bg-white/5 border-white/10 text-[#a7e3c5]' : 'bg-white/[0.02] border-white/5 text-[#8ca095]') }}">
                                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 {{ $isCurrent ? 'bg-[#78c8a2] text-[#17211b]' : ($isDone ? 'bg-[#377d60] text-white' : 'bg-white/10 text-[#9db0a5]') }}">
                                            @if($isDone)
                                                &#10003;
                                            @else
                                                {{ $stepNumber }}
                                            @endif
                                        </span>
                                        <span class="text-xs font-semibold">{{ $stepLabel }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Security & Privacy Footnote -->
                <div class="relative z-10 pt-6 mt-6 border-t border-white/10 space-y-3">
                    <div class="flex items-start gap-2.5 text-xs text-[#c7d3cc]">
                        <x-icon name="check-circle" class="w-4 h-4 text-[#78c8a2] mt-0.5" />
                        <span>Guest generators always stay free without login.</span>
                    </div>
                    <div class="flex items-start gap-2.5 text-xs text-[#c7d3cc]">
                        <x-icon name="shield" class="w-4 h-4 text-[#78c8a2] mt-0.5" />
                        <span>Hashed 6-digit OTP verification &amp; encrypted sessions protect your saved workspace.</span>
                    </div>
                </div>
            </div>

            <!-- Right Form Workspace -->
            <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between bg-white">
                <div>
                    <!-- Mobile Brand Header -->
                    <div class="flex items-center justify-between lg:hidden mb-6 pb-4 border-b border-[#dfe4df]">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                            <span class="fn-brand-mark" style="width: 34px; height: 34px; border-radius: 10px; gap: 2.5px;" aria-hidden="true">
                                <i class="b1" style="width: 3.5px; height: 15px;"></i>
                                <i class="b2" style="width: 3.5px; height: 11px;"></i>
                                <i class="b3" style="width: 3.5px; height: 16px;"></i>
                            </span>
                            <span class="font-display font-extrabold text-[18px] tracking-[-0.04em] text-[#17211b]">
                                form<span class="text-[#377d60]">nexa</span>
                            </span>
                        </a>
                        <span class="px-2.5 py-1 rounded-full bg-[#e8f7ed] text-[#184f3a] font-mono text-[10px] font-bold uppercase tracking-wider">
                            {{ $badge }}
                        </span>
                    </div>

                    @if(!empty($steps))
                        <!-- Mobile Compact Step Pills -->
                        <div class="flex lg:hidden items-center gap-2 mb-5 overflow-x-auto pb-1">
                            @foreach($steps as $index => $stepLabel)
                                @php
                                    $stepNumber = $index + 1;
                                    $isDone = $stepNumber < $activeStep;
                                    $isCurrent = $stepNumber === $activeStep;
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold whitespace-nowrap {{ $isCurrent ? 'bg-[#184f3a] text-white' : ($isDone ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-[#f8f8f3] text-[#68736d] border border-[#dfe4df]') }}">
                                    <span>{{ $isDone ? '✓' : $stepNumber }}.</span>
                                    <span>{{ $stepLabel }}</span>
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Main H1 & Subtitle -->
                    <div class="mb-6">
                        <h1 class="font-display text-2xl sm:text-3xl font-extrabold text-[#17211b] tracking-[-0.03em]">
                            {{ $title }}
                        </h1>
                        <p class="text-xs sm:text-sm text-[#536058] mt-1.5 leading-relaxed">
                            {{ $subtitle }}
                        </p>
                    </div>

                    <!-- Global Alerts -->
                    @if ($errors->any())
                        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium space-y-1">
                            @foreach ($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-700 font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-5 p-3.5 rounded-xl bg-[#e8f7ed] border border-[#c8f3dc] text-xs text-[#17211b] font-medium">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-medium">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Page Form Content -->
                    {{ $slot }}
                </div>

                <!-- Bottom Guest Fallback Link -->
                @if($footerBackRoute)
                    <div class="mt-7 pt-5 border-t border-[#dfe4df] flex flex-wrap items-center justify-between gap-2 text-xs text-[#536058]">
                        <a href="{{ route($footerBackRoute) }}" class="font-semibold text-[#536058] hover:text-[#184f3a] transition">
                            &larr; {{ $footerBackLabel }}
                        </a>
                        <span class="font-mono text-[10px] uppercase tracking-wider text-[#68736d]">256-bit TLS &middot; Email OTP</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
