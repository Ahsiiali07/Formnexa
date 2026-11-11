<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\ConverterApiController;
use App\Http\Controllers\Api\HistoryApiController;
use App\Http\Controllers\Api\InvoiceApiController;
use App\Http\Controllers\Api\ResumeApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function () {
    // Unified Mobile App Startup / Discovery Bootstrap
    Route::get('/bootstrap', [CatalogApiController::class, 'bootstrap'])->name('bootstrap');

    // Optional Account Authentication (Bearer Token) & Guest Sync
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/register', [AuthApiController::class, 'register'])->name('register');
        Route::post('/login', [AuthApiController::class, 'login'])->name('login');

        Route::middleware('api.auth')->group(function () {
            Route::get('/me', [AuthApiController::class, 'me'])->name('me');
            Route::post('/claim', [AuthApiController::class, 'claim'])->name('claim');
            Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
        });
    });

    // Authenticated Unified Document History Dashboard
    Route::middleware('api.auth')->group(function () {
        Route::get('/history', [HistoryApiController::class, 'index'])->name('history');
    });

    // =========================================================================
    // 1. INVOICE & 12 BUSINESS DOCUMENT GENERATORS
    // =========================================================================
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/config', [CatalogApiController::class, 'invoiceConfig'])->name('config');
        Route::get('/templates', [CatalogApiController::class, 'invoiceTemplates'])->name('templates');
        Route::get('/{uuid}/pdf', [InvoiceApiController::class, 'downloadPdf'])->name('pdf');

        Route::middleware('api.optional')->group(function () {
            Route::get('/', [InvoiceApiController::class, 'index'])->name('index');
            Route::get('/history', [InvoiceApiController::class, 'history'])->name('history');
            Route::post('/', [InvoiceApiController::class, 'store'])->name('store');
            Route::get('/{uuid}', [InvoiceApiController::class, 'show'])->name('show');
            Route::match(['put', 'patch'], '/{uuid}', [InvoiceApiController::class, 'update'])->name('update');
            Route::post('/{uuid}/email', [InvoiceApiController::class, 'sendEmail'])->name('email');
        });

        Route::middleware('api.auth')->group(function () {
            Route::patch('/{uuid}/status', [InvoiceApiController::class, 'updateStatus'])->name('status');
            Route::delete('/{uuid}', [InvoiceApiController::class, 'destroy'])->name('destroy');
        });
    });

    // Public Shareable Client Links (Matches /invoice/{uuid} and /resume/{uuid})
    Route::prefix('public')->name('public.')->group(function () {
        Route::get('/invoices/{uuid}', [InvoiceApiController::class, 'publicShow'])->name('invoices.show');
        Route::get('/resumes/{uuid}', [ResumeApiController::class, 'publicShow'])->name('resumes.show');
    });

    // =========================================================================
    // 2. RESUME BUILDER & SMART CV IMPORTER
    // =========================================================================
    Route::prefix('resumes')->name('resumes.')->group(function () {
        Route::get('/config', [CatalogApiController::class, 'resumeConfig'])->name('config');
        Route::get('/templates', [CatalogApiController::class, 'resumeTemplates'])->name('templates');
        Route::post('/import', [ResumeApiController::class, 'import'])->name('import');
        Route::get('/{uuid}/pdf', [ResumeApiController::class, 'downloadPdf'])->name('pdf');

        Route::middleware('api.optional')->group(function () {
            Route::get('/', [ResumeApiController::class, 'index'])->name('index');
            Route::post('/', [ResumeApiController::class, 'store'])->name('store');
            Route::get('/{uuid}', [ResumeApiController::class, 'show'])->name('show');
            Route::match(['put', 'patch'], '/{uuid}', [ResumeApiController::class, 'update'])->name('update');
        });

        Route::middleware('api.auth')->group(function () {
            Route::delete('/{uuid}', [ResumeApiController::class, 'destroy'])->name('destroy');
        });
    });

    // =========================================================================
    // 3. PDF CONVERTER SUITE (10 TOOLS: TO PDF & FROM PDF)
    // =========================================================================
    Route::get('/converters', [CatalogApiController::class, 'converters'])->name('converters.index');
    Route::get('/converters/{tool}', [CatalogApiController::class, 'converterShow'])->name('converters.show');
    Route::post('/convert/{tool}', [ConverterApiController::class, 'process'])->name('convert.process');
});
