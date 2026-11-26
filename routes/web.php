<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AtsCheckerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConverterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\PaystubController;
use App\Http\Controllers\ResumeController;
use App\Support\ConverterCatalog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Unified Formnexa Homepage (3 Core Flows: Invoices, Resume Builder, PDF Converters)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dynamic / Cached XML Sitemap fallback
Route::get('/sitemap.xml', function () {
    $path = public_path('sitemap.xml');
    if (!file_exists($path) || str_contains((string) @file_get_contents($path), 'localhost') || str_contains((string) @file_get_contents($path), '127.0.0.1')) {
        Artisan::call('seo:sitemap', ['--url' => 'https://formnexa.dcpakpattan.gov.pk']);
    }

    $content = file_exists($path) ? (string) file_get_contents($path) : '';
    if ($content === '' || str_contains($content, 'localhost') || str_contains($content, '127.0.0.1')) {
        Artisan::call('seo:sitemap', ['--url' => 'https://formnexa.dcpakpattan.gov.pk']);
        $content = file_exists($path) ? (string) file_get_contents($path) : '';
    }

    // Safety scrub: replace any residual localhost or 127.0.0.1 occurrences
    $content = str_replace(
        ['http://localhost', 'https://localhost', 'http://127.0.0.1', 'https://127.0.0.1'],
        'https://formnexa.dcpakpattan.gov.pk',
        $content
    );

    return response($content, 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
})->name('sitemap');

// Public SEO Blog & Guides Module
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{category}', [BlogController::class, 'category'])
    ->where('category', '[a-z0-9\-]+')
    ->name('blog.category');

// 301 Permanent Redirects for legacy/renamed blog slugs to resolve GSC 404 errors
Route::redirect('/blog/convert-pdf-to-docx-without-losing-formatting-guide', '/blog/convert-pdf-to-editable-word-without-losing-formatting', 301);
Route::redirect('/blog/how-to-write-an-ats-friendly-resume-guide', '/blog/how-to-write-ats-friendly-resume-guide', 301);
Route::redirect('/blog/international-invoice-format-cross-border-currency-guide', '/blog/international-invoice-format-asia-usa-europe-guide', 301);
Route::redirect('/blog/weekly-timesheet-break-hours-freelance-contractor-guide', '/blog/weekly-timesheets-and-work-orders-contractor-guide', 301);
Route::redirect('/blog/invoice-format-pakistan-pkr-guide', '/blog/international-invoice-format-asia-usa-europe-guide', 301);

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9\-]+')
    ->name('blog.show');

// Live Invoice Studio (No Login Required)
Route::redirect('/invoice', '/invoice-generator', 301);
Route::redirect('/email', '/invoice-generator', 301);
Route::get('/invoice-generator', [InvoiceController::class, 'index'])->name('invoice.studio');

// Invoice Templates Gallery
Route::get('/templates', [InvoiceController::class, 'templates'])->name('templates.index');

// Resume Builder & Templates (No Login Required)
Route::get('/resume-builder', [ResumeController::class, 'builder'])->name('resume.builder');
Route::get('/resume-templates', [ResumeController::class, 'templates'])->name('resume.templates');
Route::get('/ats-resume-converter', [ResumeController::class, 'atsConverter'])->name('resume.ats_converter');
Route::get('/ats-resume-checker', [AtsCheckerController::class, 'index'])->name('resume.checker');
Route::post('/api/resume/check', [AtsCheckerController::class, 'check'])->name('resume.checker.process');
Route::post('/api/resumes/import', [ResumeController::class, 'import'])->name('resume.import');
Route::post('/api/resumes', [ResumeController::class, 'store'])->name('resume.store');

// Resignation Letter & Two Weeks Notice Maker
Route::get('/resignation-letter-generator', [LetterController::class, 'resignation'])->name('letter.resignation');
Route::redirect('/resignation-letter', '/resignation-letter-generator', 301);
Route::get('/resignation-letter/{slug}', [LetterController::class, 'landing'])
    ->whereIn('slug', array_keys(\App\Support\ResignationCatalog::landingPages()))
    ->name('letter.resignation.landing');
Route::post('/resignation-letter/pdf', [LetterController::class, 'downloadPdf'])->name('letter.resignation.pdf');
Route::post('/resignation-letter/doc', [LetterController::class, 'downloadDocx'])->name('letter.resignation.doc');

// Paystub & Salary Slip Maker
Route::get('/paystub-generator', [PaystubController::class, 'index'])->name('paystub.studio');
Route::get('/salary-slip-generator', [PaystubController::class, 'salarySlip'])->name('paystub.salary_slip');
Route::post('/paystub-generator/pdf', [PaystubController::class, 'downloadPdf'])->name('paystub.pdf');

// Private / Shareable Resume Links & PDF (Kept out of search engines with noindex)
Route::middleware('noindex')->group(function () {
    Route::get('/resume/{uuid}', [ResumeController::class, 'show'])->name('resume.show');
    Route::get('/resume/{uuid}/pdf', [ResumeController::class, 'downloadPdf'])->name('resume.pdf');
});

// Optional Authentication (Login, Sign Up, OTP Verification, Password Reset — Keep out of search engines)
Route::middleware('noindex')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/register', fn () => redirect()->route('register'));
    Route::post('/signup', [AuthController::class, 'register'])->name('register.submit');

    // Signup Email OTP Verification & Resend
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verification.notice');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verification.verify');
    Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verification.resend');

    // Forgot Password & OTP-Based Password Reset
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.form');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/reset-password/resend', [AuthController::class, 'resendResetOtp'])->name('password.reset.resend');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Personal Workspace
Route::middleware(['auth', 'noindex'])->group(function () {
    Route::get('/history', [InvoiceController::class, 'historyPage'])->name('invoice.history.page');
    Route::get('/account/password', [AuthController::class, 'showChangePassword'])->name('account.password');
    Route::put('/account/password', [AuthController::class, 'updatePassword'])->name('account.password.update');
    Route::patch('/invoice/{uuid}/status', [InvoiceController::class, 'updateStatus'])->name('invoice.status');
    Route::delete('/invoice/{uuid}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::delete('/resume/{uuid}', [ResumeController::class, 'destroy'])->name('resume.destroy');
});

// Admin Dashboard & Module Management (Blogs Add/Edit/Active/Delete + Invoices, Resumes, Users, SEO)
Route::middleware(['auth', 'admin', 'noindex'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/blogs/create', [AdminController::class, 'createBlog'])->name('admin.blogs.create');
    Route::post('/admin/blogs', [AdminController::class, 'storeBlog'])->name('admin.blogs.store');
    Route::get('/admin/blogs/{post:id}/edit', [AdminController::class, 'editBlog'])->name('admin.blogs.edit');
    Route::put('/admin/blogs/{post:id}', [AdminController::class, 'updateBlog'])->name('admin.blogs.update');
    Route::patch('/admin/blogs/{post:id}/toggle', [AdminController::class, 'toggleBlogStatus'])->name('admin.blogs.toggle');
    Route::delete('/admin/blogs/{post:id}', [AdminController::class, 'destroyBlog'])->name('admin.blogs.destroy');

    Route::patch('/admin/invoices/{invoice:id}/toggle', [AdminController::class, 'toggleInvoiceStatus'])->name('admin.invoices.toggle');
    Route::delete('/admin/invoices/{invoice:id}', [AdminController::class, 'destroyInvoice'])->name('admin.invoices.destroy');
    Route::delete('/admin/resumes/{resume:id}', [AdminController::class, 'destroyResume'])->name('admin.resumes.destroy');
    Route::patch('/admin/users/{user:id}/toggle-admin', [AdminController::class, 'toggleUserAdmin'])->name('admin.users.toggle-admin');
    Route::patch('/admin/contacts/{contact:id}/toggle-read', [AdminController::class, 'toggleContactRead'])->name('admin.contacts.toggle-read');
    Route::delete('/admin/contacts/{contact:id}', [AdminController::class, 'destroyContact'])->name('admin.contacts.destroy');
    Route::post('/admin/seo/sitemap', [AdminController::class, 'regenerateSitemap'])->name('admin.seo.sitemap');

    // Blog Studio shortcuts
    Route::get('/blog-studio', [BlogController::class, 'manage'])->name('blog.manage');
    Route::get('/blog-studio/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog-studio', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog-studio/{post:id}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog-studio/{post:id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog-studio/{post:id}', [BlogController::class, 'destroy'])->name('blog.destroy');
});

// Contact Us Page (Public submission & direct inquiry)
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Legal Pages (Terms of Service & Privacy Policy)
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// PDF Conversion Suite (10 Tools: Convert TO PDF & Convert FROM PDF — No Login Required)
Route::get('/convert', [ConverterController::class, 'hub'])->name('convert.hub');
Route::get('/convert/pdf-to-ats', [ResumeController::class, 'atsConverter'])->name('convert.pdf_to_ats');
Route::get('/convert/{tool}', [ConverterController::class, 'tool'])
    ->where('tool', ConverterCatalog::slugsRegex())
    ->name('convert.tool');
Route::post('/api/convert/{tool}', [ConverterController::class, 'process'])
    ->where('tool', ConverterCatalog::slugsRegex())
    ->name('convert.process');

// Specialized Document Generators (12 Business Documents)
Route::get('/{type}', [InvoiceController::class, 'generator'])
    ->where('type', 'invoice-generator|receipt-generator|quote-generator|estimate-generator|credit-note-generator|purchase-order-generator|sales-order-generator|proforma-invoice-generator|timesheet-generator|work-order-generator|account-statement-generator|packing-slip-generator')
    ->name('invoice.generator');

// Save / Update Invoice
Route::post('/api/invoices', [InvoiceController::class, 'store'])->name('invoice.store');

// Private / Shareable Invoice Links & PDF (Kept out of search engines with noindex)
Route::middleware('noindex')->group(function () {
    Route::get('/invoice/{uuid}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{uuid}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.pdf');
});

// Email Invoice
Route::post('/invoice/{uuid}/email', [InvoiceController::class, 'sendEmail'])->name('invoice.email');

// Recent Invoices History API
Route::get('/api/invoices/history', [InvoiceController::class, 'history'])->name('invoice.history');
