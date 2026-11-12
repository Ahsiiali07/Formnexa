<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Support\ConverterCatalog;
use App\Support\ResumeCatalog;
use App\Support\TemplateCatalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Unified Formnexa Homepage showcasing all three core workflows:
     * 1. Invoice & Document Suite
     * 2. Resume Builder & Smart CV Importer
     * 3. 10-Tool PDF Converter Suite
     */
    public function index(): View
    {
        try {
            $latestPosts = BlogPost::published()->orderByDesc('published_at')->orderByDesc('id')->take(3)->get();
        } catch (\Throwable) {
            $latestPosts = collect();
        }

        return view('home', [
            'generators' => InvoiceController::$generators,
            'invoiceStyles' => TemplateCatalog::styles(),
            'invoiceTemplates' => array_slice(TemplateCatalog::templates(), 0, 6),
            'resumeTemplates' => array_slice(ResumeCatalog::templates(), 0, 6),
            'resumeStyles' => ResumeCatalog::styles(),
            'converterGroups' => ConverterCatalog::grouped(),
            'latestPosts' => $latestPosts,
        ]);
    }
}
