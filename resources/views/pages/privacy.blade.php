@extends('layouts.app')

@section('title', 'Privacy Policy — Formnexa')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] py-10 sm:py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-black/50 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-[#0066FF] transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Home
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">Privacy Policy</span>
        </nav>

        <!-- Main Document Container Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5 sm:p-10 md:p-14">
            
            <!-- Document Header -->
            <div class="pb-6 mb-8 border-b border-slate-200">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 mb-4 border border-emerald-100">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Confidentiality & Data Protection
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Privacy Policy
                </h1>
                <p class="text-xs text-slate-500 mt-2 font-medium">
                    Last Updated: March 2026 &middot; Committed to Complete Financial Privacy
                </p>
            </div>

            <!-- Legal Document Body -->
            <div class="space-y-8 text-slate-700 leading-relaxed font-sans-inter text-sm sm:text-base">

                <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200/60 text-xs text-emerald-950 leading-normal">
                    <strong>Your Privacy Matters:</strong> At <strong>Formnexa</strong>, we believe invoicing data is sensitive business information. We do not sell, rent, monetize, or harvest your client lists, line items, or billing amounts. Document drafts are saved in your local browser, giving you total control over your financial records.
                </div>

                <!-- 1. Scope & Introduction -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">1</span>
                        Scope & Overview
                    </h2>
                    <p class="text-slate-600 mb-3">
                        This Privacy Policy applies to the web application and services provided by <strong>Formnexa</strong> ("we", "us", or "our") at <a href="{{ route('invoice.studio') }}" class="text-teal-600 font-medium hover:underline">formnexa.com</a>. It outlines how we collect, handle, store, and protect information when you create, preview, generate PDFs, or transmit invoices and related business documents using our platform.
                    </p>
                </div>

                <!-- 2. Information We Process -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">2</span>
                        Information We Process
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Depending on how you use the Service, we process the following categories of information:
                    </p>

                    <div class="space-y-3 mt-4 text-xs sm:text-sm">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <h4 class="font-bold text-slate-900 mb-1">A. Document Content & Invoicing Details</h4>
                            <p class="text-slate-600">
                                Information you enter into invoice fields: sender and business name, address, tax identification numbers, client name, client billing address, item descriptions, rates, quantities, discounts, tax rates, payment instructions, and additional terms.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <h4 class="font-bold text-slate-900 mb-1">B. Uploaded Logos, Cover Images & Signatures</h4>
                            <p class="text-slate-600">
                                Image files you select to personalize your document (such as your corporate logo or header banner), and vector paths created when drawing an electronic signature on the signature pad.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <h4 class="font-bold text-slate-900 mb-1">C. Recipient Delivery Information</h4>
                            <p class="text-slate-600">
                                When using our "Send by Email" feature, we process the recipient email address, subject line, and optional message solely to transmit the invoice and PDF attachment directly to that recipient.
                            </p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <h4 class="font-bold text-slate-900 mb-1">D. Automatically Collected Technical Logs</h4>
                            <p class="text-slate-600">
                                Standard web server access logs including IP address, browser type, operating system, timestamp, and referring URL, used exclusively for security diagnostics, rate limiting, and spam mitigation.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 3. Local Browser Storage Architecture -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">3</span>
                        Client-Side Local Storage Privacy
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Unlike traditional platforms that store every keystroke in a centralized database, Formnexa operates with a <strong>privacy-first client architecture</strong>:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li><strong>In-Browser Drafts:</strong> As you type, changes are automatically cached to your browser's private <code class="bg-slate-100 px-1 py-0.5 rounded text-xs text-teal-800 font-mono">localStorage</code>. This data never touches our servers during drafting.</li>
                        <li><strong>Instant Wipe:</strong> Clicking <em>New Blank</em> or clearing your browser's site data completely erases your draft from your device immediately.</li>
                        <li><strong>Explicit Server Save:</strong> Document records are only transmitted to our database when you explicitly click <em>Save Draft</em>, <em>Download PDF</em>, or <em>Send Email</em> to create a shareable invoice record or generate your document.</li>
                    </ul>
                </div>

                <!-- 4. How We Use Information -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">4</span>
                        How We Use Your Information
                    </h2>
                    <p class="text-slate-600 mb-2">We process information solely for the following legitimate business purposes:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li>Rendering live interactive document previews on your screen.</li>
                        <li>Compiling high-resolution, vector-accurate PDF files for download and printing.</li>
                        <li>Transmitting invoice notifications and PDF attachments to designated client emails upon your request.</li>
                        <li>Generating unique, shareable client URLs (e.g., <code class="bg-slate-100 px-1 py-0.5 rounded text-xs text-teal-800 font-mono">/invoice/{uuid}</code>) so your clients can view and settle invoices online.</li>
                        <li>Monitoring system stability, preventing abuse, spam, and denial-of-service attempts.</li>
                    </ul>
                </div>

                <!-- 5. Strict Non-Sale & Information Sharing -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">5</span>
                        Strict Zero-Sale Commitment & Sharing
                    </h2>
                    <p class="text-slate-600 mb-3">
                        <strong>We will never sell, rent, license, or barter your personal information or financial data to any third party for marketing, advertising, or data-brokering purposes.</strong>
                    </p>
                    <p class="text-slate-600 mb-3">
                        We only share data with trusted infrastructure providers under strict confidentiality agreements:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li><strong>Infrastructure & Hosting:</strong> Secure cloud servers hosting the application and executing PDF compilation.</li>
                        <li><strong>Transactional Email Services:</strong> Encrypted email relay providers used strictly to dispatch requested invoices.</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information if required to do so by a binding court order, valid legal warrant, or applicable statute.</li>
                    </ul>
                </div>

                <!-- 6. Payment Information Disclaimer -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">6</span>
                        Payment Information & Third-Party Links
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Formnexa provides an optional "Payment Link" field where you can supply a payment link from services such as Stripe, PayPal, or Wise. Formnexa does not collect, capture, or store credit card numbers, bank account logins, or CVV codes. All payments initiated by your clients occur directly on the third-party processor's secure domain according to their independent privacy policies.
                    </p>
                </div>

                <!-- 7. Data Security -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">7</span>
                        Data Security & Encryption
                    </h2>
                    <p class="text-slate-600 mb-3">
                        We employ industry-standard administrative, technical, and physical safeguards:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li>All web traffic between your device and our servers is secured using modern TLS/HTTPS encryption.</li>
                        <li>Unique 128-bit UUID identifiers prevent unauthorized guessing of private invoice URLs.</li>
                        <li>CSRF tokens safeguard against cross-site request forgery attacks.</li>
                        <li>Internal access to database records is restricted strictly to authorized technical personnel under non-disclosure obligations.</li>
                    </ul>
                </div>

                <!-- 8. Your Data Rights & Deletion -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">8</span>
                        Your Rights & Data Deletion
                    </h2>
                    <p class="text-slate-600 mb-3">
                        You have complete authority over your data:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li><strong>Draft Removal:</strong> Clear your browser's cache or local storage at any time to remove all saved drafts.</li>
                        <li><strong>Server Data Erasure:</strong> If you have generated or saved an invoice on Formnexa and wish to have that record purged from our database, contact us with your invoice reference number or UUID, and we will delete it promptly.</li>
                    </ul>
                </div>

                <!-- 9. Cookies & Tracking -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">9</span>
                        Cookies & Local Storage
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Formnexa uses essential session cookies required for website operation and CSRF security verification. We do not use third-party invasive behavioral tracking or advertising cookies.
                    </p>
                </div>

                <!-- 10. Privacy Questions & Contact -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">10</span>
                        Privacy Questions & Contact
                    </h2>
                    <p class="text-slate-600 mb-2">
                        If you have questions, feedback, or requests regarding this Privacy Policy or your personal information, please contact:
                    </p>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-sm">
                        <div class="font-bold text-slate-800">Formnexa Privacy Office</div>
                        <div class="text-slate-600 text-xs mt-1">Email: <a href="mailto:privacy@formnexa.com" class="text-teal-600 font-medium hover:underline">privacy@formnexa.com</a></div>
                        <div class="text-slate-600 text-xs mt-0.5">Response Time: Inquiries answered within 48 business hours</div>
                    </div>
                </div>

            </div>

            <!-- Bottom Back Button -->
            <div class="mt-12 pt-6 border-t border-slate-200 flex items-center justify-between">
                <a href="{{ route('invoice.studio') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-teal-600 hover:text-teal-700 transition">
                    &larr; Return to Invoice Studio
                </a>
                <a href="{{ route('terms') }}" class="text-xs text-slate-500 hover:text-slate-900 transition font-medium">
                    View Terms of Service &rarr;
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
