<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConverterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ResumeController;
use App\Support\ConverterCatalog;
use Illuminate\Support\Facades\Route;

// Unified Formnexa Homepage (3 Core Flows: Invoices, Resume Builder, PDF Converters)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Live Invoice Studio (Bloom.io-inspired /invoice-generator — No Login Required)
Route::get('/invoice-generator', [InvoiceController::class, 'index'])->name('invoice.studio');

// Invoice Templates Gallery
Route::get('/templates', [InvoiceController::class, 'templates'])->name('templates.index');

// Resume Builder & Templates (FlowCV-inspired — No Login Required)
Route::get('/resume-builder', [ResumeController::class, 'builder'])->name('resume.builder');
Route::get('/resume-templates', [ResumeController::class, 'templates'])->name('resume.templates');
Route::post('/api/resumes/import', [ResumeController::class, 'import'])->name('resume.import');
Route::post('/api/resumes', [ResumeController::class, 'store'])->name('resume.store');
Route::get('/resume/{uuid}', [ResumeController::class, 'show'])->name('resume.show');
Route::get('/resume/{uuid}/pdf', [ResumeController::class, 'downloadPdf'])->name('resume.pdf');

// Optional Authentication (Login & Sign Up for History Sync)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/signup', [AuthController::class, 'showRegister'])->name('register');
Route::get('/register', fn () => redirect()->route('register'));
Route::post('/signup', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Personal Document History Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/history', [InvoiceController::class, 'historyPage'])->name('invoice.history.page');
    Route::patch('/invoice/{uuid}/status', [InvoiceController::class, 'updateStatus'])->name('invoice.status');
    Route::delete('/invoice/{uuid}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::delete('/resume/{uuid}', [ResumeController::class, 'destroy'])->name('resume.destroy');
});

// Legal Pages (Terms of Service & Privacy Policy)
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// PDF Conversion Suite (10 Tools: Convert TO PDF & Convert FROM PDF — No Login Required)
Route::get('/convert', [ConverterController::class, 'hub'])->name('convert.hub');
Route::get('/convert/{tool}', [ConverterController::class, 'tool'])
    ->where('tool', ConverterCatalog::slugsRegex())
    ->name('convert.tool');
Route::post('/api/convert/{tool}', [ConverterController::class, 'process'])
    ->where('tool', ConverterCatalog::slugsRegex())
    ->name('convert.process');

// Specialized Document Generators (Receipt, Quote, Estimate, Credit Note, Purchase Order, Sales Order, etc.)
Route::get('/{type}', [InvoiceController::class, 'generator'])
    ->where('type', 'invoice-generator|receipt-generator|quote-generator|estimate-generator|credit-note-generator|purchase-order-generator|sales-order-generator|proforma-invoice-generator|timesheet-generator|work-order-generator|account-statement-generator|packing-slip-generator')
    ->name('invoice.generator');

// Save / Update Invoice
Route::post('/api/invoices', [InvoiceController::class, 'store'])->name('invoice.store');

// Public Invoice View
Route::get('/invoice/{uuid}', [InvoiceController::class, 'show'])->name('invoice.show');

// PDF Generation & Download
Route::get('/invoice/{uuid}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.pdf');

// Email Invoice
Route::post('/invoice/{uuid}/email', [InvoiceController::class, 'sendEmail'])->name('invoice.email');

// Recent Invoices History API
Route::get('/api/invoices/history', [InvoiceController::class, 'history'])->name('invoice.history');
