<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Throwable;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:sitemap';

    protected $description = 'Generate public/sitemap.xml from indexable static routes and published blog posts';

    public function handle(): int
    {
        $entries = [];

        foreach (config('seo-pages', []) as $path => $page) {
            if (str_contains((string) ($page['robots'] ?? ''), 'noindex')) {
                continue;
            }

            $loc = $path === '/' ? url('/') : url($path);
            $lastmod = Carbon::parse($page['updated'] ?? '2026-11-12')->toAtomString();
            $entries[$loc] = $lastmod;
        }

        try {
            if (Schema::hasTable('blog_posts')) {
                BlogPost::published()
                    ->orderByDesc('published_at')
                    ->get(['slug', 'updated_at', 'published_at'])
                    ->each(function (BlogPost $post) use (&$entries): void {
                        $loc = route('blog.show', $post->slug);
                        $entries[$loc] = ($post->updated_at ?? $post->published_at ?? now())->toAtomString();
                    });

                foreach (array_keys(BlogPost::categories()) as $category) {
                    $entries[route('blog.category', $category)] = now()->toAtomString();
                }
            }
        } catch (Throwable $e) {
            $this->warn('Skipped database blog URLs while generating sitemap: ' . $e->getMessage());
        }

        $xml = $this->buildXml($entries);
        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap written to public/sitemap.xml with ' . count($entries) . ' URLs.');

        return self::SUCCESS;
    }

    /**
     * Build clean UTF-8 XML string for urlset.
     *
     * @param array<string, string> $entries
     */
    protected function buildXml(array $entries): string
    {
        $out = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $out .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($entries as $loc => $lastmod) {
            $safeLoc = htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $safeDate = htmlspecialchars($lastmod, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $out .= "  <url>\n";
            $out .= "    <loc>{$safeLoc}</loc>\n";
            $out .= "    <lastmod>{$safeDate}</lastmod>\n";
            $out .= "  </url>\n";
        }

        $out .= "</urlset>\n";

        return $out;
    }
}
