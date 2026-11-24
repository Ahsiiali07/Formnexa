<?php

namespace App\View\Composers;

use Illuminate\View\View;

class SeoComposer
{
    /**
     * Inject canonical, Open Graph, Twitter Card & page-specific SEO tags into layouts.
     */
    public function compose(View $view): void
    {
        $rawPath = request()->path();
        $path = $rawPath === '/' ? '/' : trim($rawPath, '/');

        $page = config('seo-pages', [])[$path] ?? [];
        $custom = $view->getData()['seo'] ?? [];

        $seo = array_merge(config('seo.defaults', []), $page, $custom);

        // Ensure unique meta description for parameter variants if crawled
        if ($path === 'resume-builder' && request()->query()) {
            if (request()->has('template')) {
                $tpl = \App\Support\ResumeCatalog::find((string) request()->query('template'));
                if ($tpl) {
                    $seo['title'] = $tpl['name'] . ' – ATS Resume Template | Formnexa';
                    $seo['description'] = 'Customize the ' . $tpl['name'] . ' ATS resume template. ' . $tpl['tagline'] . ' Free Word & PDF download.';
                }
            } elseif (request()->has('import')) {
                $seo['title'] = 'Convert CV to ATS Format Free – Import Resume | Formnexa';
                $seo['description'] = 'Convert your existing Word (.docx) or PDF CV into an ATS-compliant resume in 60 seconds with Formnexa free CV parser.';
            } elseif (request()->has('blank')) {
                $seo['title'] = 'Start Blank ATS CV Online Free | Formnexa';
                $seo['description'] = 'Start a fresh, blank ATS-compliant resume online. 100% free with instant Word DOCX and vector PDF download.';
            }
        }

        if (str_starts_with($view->name(), 'errors.') || str_starts_with($path, 'api/')) {
            $seo['title'] = $seo['title'] ?: 'Page Not Found | Formnexa';
            $seo['robots'] = 'noindex,follow';
        }

        if (str_starts_with($view->name(), 'resume.show') || str_starts_with($view->name(), 'invoice.show')) {
            $seo['robots'] = 'noindex,nofollow';
        }

        // Strip query strings (?import=1, ?utm_*, ?q=...) from canonical URLs by default
        $seo['canonical'] = $seo['canonical'] ?? url()->current();
        $rawImage = $seo['image'] ?? config('seo.default_image', 'images/og/default.jpg');
        $seo['image'] = (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://'))
            ? $rawImage
            : asset($rawImage);

        $view->with('seo', $seo);
    }
}
