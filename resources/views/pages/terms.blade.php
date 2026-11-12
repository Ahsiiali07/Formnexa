@extends('layouts.app')

@section('title', 'Terms of Service — Formnexa')

@section('content')
<div class="min-h-screen bg-[#f8f8f3] py-10 sm:py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs text-[#68736d] mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-[#184f3a] transition flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Home
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-slate-800 font-semibold">Terms of Service</span>
        </nav>

        <!-- Main Document Container Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 p-5 sm:p-10 md:p-14">
            
            <!-- Document Header -->
            <div class="pb-6 mb-8 border-b border-slate-200">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-teal-50 text-teal-700 mb-4 border border-teal-100">
                    <svg class="w-3 h-3 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    Legal Agreement
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Terms of Service
                </h1>
                <p class="text-xs text-slate-500 mt-2 font-medium">
                    Last Updated: March 2026 &middot; Effective Immediately
                </p>
            </div>

            <!-- Legal Document Body -->
            <div class="space-y-8 text-slate-700 leading-relaxed font-sans-inter text-sm sm:text-base">

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 leading-normal">
                    <strong>Please read this agreement carefully before using this service.</strong> By using, accessing, or generating documents via <strong>Formnexa</strong> (the "Service"), you agree to be bound by these terms. If you are entering into this agreement on behalf of a company or other legal entity, you represent and warrant that you have authority to bind such entity to these terms.
                </div>

                <!-- 1. Software & Document Generation Service -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">1</span>
                        Software & Document Generation Service
                    </h2>
                    <p class="text-slate-600 mb-3">
                        This agreement provides you access to and usage of Formnexa's web-based document generation suite, accessible at <a href="{{ route('invoice.studio') }}" class="text-teal-600 font-medium hover:underline">formnexa.com</a>.
                    </p>
                    <p class="text-slate-600 mb-3">
                        The Service enables users to compose, customize, preview, calculate, download, and email professional financial documents, including:
                    </p>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs sm:text-sm text-slate-600 bg-slate-50/70 p-4 rounded-xl border border-slate-200/70 my-3">
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Standard Commercial Invoices</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Official Payment Receipts</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Price & Service Quotes</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Project Cost Estimates</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Credit & Adjustment Notes</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Vendor Purchase Orders</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Confirmed Sales Orders</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Proforma & Customs Invoices</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Hourly Consulting Timesheets</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Maintenance Work Orders</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Customer Account Statements</li>
                        <li class="flex items-center gap-2"><span class="text-teal-600 font-bold">&check;</span> Warehouse Packing Slips</li>
                    </ul>
                </div>

                <!-- 2. Customer-Owned Data & Local Storage Privacy -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">2</span>
                        Customer-Owned Data & Local Storage
                    </h2>
                    <h3 class="font-bold text-slate-800 text-sm mt-3 mb-1">a. Ownership of Content</h3>
                    <p class="text-slate-600 mb-3">
                        All logos, company names, itemized line items, pricing, notes, and digital signatures entered by Customer remain the exclusive property of Customer ("Customer Data"). Formnexa does not claim ownership of any Customer Data.
                    </p>
                    <h3 class="font-bold text-slate-800 text-sm mt-3 mb-1">b. Client-Side Draft Storage</h3>
                    <p class="text-slate-600 mb-3">
                        Formnexa prioritizes privacy. Document drafts are continuously cached directly in your browser's local storage (<code class="bg-slate-100 px-1 py-0.5 rounded text-xs text-teal-800 font-mono">localStorage</code>). Your work-in-progress drafts are saved on your own device and are not transmitted to or stored on our servers until you explicitly click <em>Save</em>, <em>Download PDF</em>, or <em>Send Email</em>.
                    </p>
                    <h3 class="font-bold text-slate-800 text-sm mt-3 mb-1">c. Limited License</h3>
                    <p class="text-slate-600">
                        Customer grants Formnexa a limited, worldwide, non-exclusive license to process, format, render as PDF, and deliver Customer Data solely for the operational purpose of fulfilling Customer's requested document actions under this agreement.
                    </p>
                </div>

                <!-- 3. Digital Signatures & Payment Links -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">3</span>
                        Digital Signatures & Payment Links
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Formnexa provides an interactive canvas pad enabling issuers to apply electronic signatures, and fields to specify third-party payment links (e.g., Stripe Payment Links, PayPal.me, Wise, or custom wire instructions).
                    </p>
                    <p class="text-slate-600 mb-3">
                        <strong>Disclaimer of Financial Intermediation:</strong> Formnexa is strictly a software document generator. Formnexa is not a bank, payment processor, escrow service, or money transmitter. We do not process credit cards, hold funds, or arbitrate financial disputes between Customer and Customer's clients. Customer is solely responsible for verifying payment links and ensuring compliance with local electronic signature laws (such as the US ESIGN Act or EU eIDAS).
                    </p>
                </div>

                <!-- 4. PDF Generation & Email Transmission -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">4</span>
                        PDF Generation & Email Delivery
                    </h2>
                    <p class="text-slate-600 mb-3">
                        When Customer exports a document as a PDF, the document layout is rendered on our servers using high-resolution vector typesetting engines and returned directly to Customer.
                    </p>
                    <p class="text-slate-600 mb-3">
                        When Customer elects to send an invoice via email:
                    </p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li>Customer warrants that Customer has obtain lawful consent from the recipient to receive transactional correspondence.</li>
                        <li>Customer must not utilize the email delivery service to transmit unsolicited marketing messages, spam, phishing lures, or deceptive billing invoices.</li>
                        <li>Formnexa reserves the right to rate-limit or terminate email delivery privileges for accounts associated with high bounce rates, spam complaints, or fraudulent activities.</li>
                    </ul>
                </div>

                <!-- 5. Acceptable Use Policy -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">5</span>
                        Acceptable Use Policy
                    </h2>
                    <p class="text-slate-600 mb-3">Customer agrees not to:</p>
                    <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-slate-600 pl-2">
                        <li>Use the Service for any unlawful purpose, fraud, identity theft, or deceptive billing practices.</li>
                        <li>Interfere with, overload, or disrupt the integrity or security of Formnexa servers or networks.</li>
                        <li>Reverse engineer, decompile, or scrape the application's underlying source code or document engines.</li>
                        <li>Upload malicious payloads, executable scripts, or harmful files through image or logo upload inputs.</li>
                    </ul>
                </div>

                <!-- 6. Disclaimer of Warranties -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">6</span>
                        Disclaimer of Warranties
                    </h2>
                    <div class="bg-slate-100 rounded-xl p-4 text-xs font-mono uppercase text-slate-700 leading-relaxed border border-slate-200">
                        FORMNEXA DISCLAIMS ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, THE IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT. WHILE FORMNEXA TAKES REASONABLE MEASURES TO SAFEGUARD DATA INTEGRITY AND SYSTEM PERFORMANCE, FORMNEXA DOES NOT WARRANT THAT THE SERVICE WILL BE UNINTERRUPTED, BUG-FREE, OR FREE FROM DATA TRANSMISSION DELAYS.
                    </div>
                </div>

                <!-- 7. Limitation of Liability -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">7</span>
                        Limitation of Liability
                    </h2>
                    <p class="text-slate-600 mb-3">
                        To the maximum extent permitted by applicable law, in no event shall Formnexa, its developers, or affiliates be liable for any indirect, punitive, incidental, special, consequential, or exemplary damages—including but not limited to damages for loss of profits, goodwill, data, or business interruption—arising out of or in connection with the use or inability to use the Service.
                    </p>
                </div>

                <!-- 8. Modifications to Terms -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">8</span>
                        Modifications to Terms
                    </h2>
                    <p class="text-slate-600 mb-3">
                        Formnexa reserves the right to modify these Terms of Service at any time. When modifications are made, the "Last Updated" date at the top of this document will be updated accordingly. Continued use of the Service following such revisions constitutes your binding acceptance of the revised Terms.
                    </p>
                </div>

                <!-- 9. Contact & Inquiries -->
                <div>
                    <h2 class="text-lg sm:text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-teal-100 text-teal-800 text-xs font-extrabold inline-flex items-center justify-center">9</span>
                        Contact & Support
                    </h2>
                    <p class="text-slate-600 mb-2">
                        If you have any questions or feedback regarding these Terms of Service, please reach out:
                    </p>
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-sm">
                        <div class="font-bold text-slate-800">Formnexa Legal & Support Team</div>
                        <div class="text-slate-600 text-xs mt-1">Email: <a href="mailto:support@formnexa.com" class="text-teal-600 font-medium hover:underline">support@formnexa.com</a></div>
                        <div class="text-slate-600 text-xs mt-0.5">Website: <a href="{{ route('invoice.studio') }}" class="text-teal-600 font-medium hover:underline">https://formnexa.com</a></div>
                    </div>
                </div>

            </div>

            <!-- Bottom Back Button -->
            <div class="mt-12 pt-6 border-t border-slate-200 flex items-center justify-between">
                <a href="{{ route('invoice.studio') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-teal-600 hover:text-teal-700 transition">
                    &larr; Return to Invoice Studio
                </a>
                <a href="{{ route('privacy') }}" class="text-xs text-slate-500 hover:text-slate-900 transition font-medium">
                    View Privacy Policy &rarr;
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
