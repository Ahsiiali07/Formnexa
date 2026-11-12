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

        if (str_starts_with($view->name(), 'errors.')) {
            $seo['title'] = $seo['title'] ?: 'Page Not Found | Formnexa';
            $seo['robots'] = 'noindex,follow';
        }

        // Strip query strings (?import=1, ?utm_*, ?q=...) from canonical URLs by default
        $seo['canonical'] = $seo['canonical'] ?? url()->current();
        $seo['image'] = asset($seo['image'] ?? config('seo.default_image', 'images/formnexa-brand-kit.png'));

        $view->with('seo', $seo);
    }
}
