<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Throwable;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:sitemap {--url= : Override base URL (defaults to APP_URL)}';

    protected $description = 'Generate public/sitemap.xml from indexable static routes and published blog posts';

    public function handle(): int
    {
        $baseUrl = rtrim((string) $this->option('url'), '/');

        // Never allow localhost or loopback IPs in canonical sitemap URLs
        if ($baseUrl === '' || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            // Check X-Forwarded-Host from reverse proxy
            if (request()->hasHeader('x-forwarded-host')) {
                $fHost = explode(',', request()->header('x-forwarded-host'))[0];
                if (!str_contains($fHost, 'localhost') && !str_contains($fHost, '127.0.0.1')) {
                    $proto = request()->header('x-forwarded-proto') ?: 'https';
                    $baseUrl = rtrim($proto . '://' . trim($fHost), '/');
                }
            }
        }

        if (($baseUrl === '' || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) && request()->hasHeader('Host')) {
            $h = request()->getHost();
            if (!str_contains($h, 'localhost') && !str_contains($h, '127.0.0.1')) {
                $proto = request()->isSecure() ? 'https' : (request()->header('x-forwarded-proto') ?: 'https');
                $baseUrl = rtrim($proto . '://' . $h, '/');
            }
        }

        if ($baseUrl === '' || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $cfg = rtrim((string) config('app.url'), '/');
            if ($cfg !== '' && !str_contains($cfg, 'localhost') && !str_contains($cfg, '127.0.0.1')) {
                $baseUrl = $cfg;
            } else {
                $baseUrl = 'https://formnexa.dcpakpattan.gov.pk';
            }
        }

        // Hard guarantee: baseUrl is strictly the canonical production HTTPS domain
        if ($baseUrl === '' || str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1')) {
            $baseUrl = 'https://formnexa.dcpakpattan.gov.pk';
        }

        URL::forceRootUrl($baseUrl);
        URL::forceScheme('https');

        $entries = [];

        foreach (config('seo-pages', []) as $path => $page) {
            if (str_contains((string) ($page['robots'] ?? ''), 'noindex')) {
                continue;
            }

            $loc = $path === '/' ? $baseUrl : $baseUrl . '/' . ltrim($path, '/');
            $lastmod = Carbon::parse($page['updated'] ?? '2026-11-19')->toAtomString();
            $entries[$loc] = $lastmod;
        }

        try {
            if (Schema::hasTable('blog_posts')) {
                BlogPost::published()
                    ->orderByDesc('published_at')
                    ->get(['slug', 'updated_at', 'published_at'])
                    ->each(function (BlogPost $post) use (&$entries, $baseUrl): void {
                        $loc = $baseUrl . '/blog/' . ltrim($post->slug, '/');
                        $entries[$loc] = ($post->updated_at ?? $post->published_at ?? now())->toAtomString();
                    });

                foreach (array_keys(BlogPost::categories()) as $category) {
                    $loc = $baseUrl . '/blog/category/' . ltrim($category, '/');
                    $entries[$loc] = now()->toAtomString();
                }
            }
        } catch (Throwable $e) {
            $this->warn('Skipped database blog URLs while generating sitemap: ' . $e->getMessage());
        }

        $xml = $this->buildXml($entries);

        // Ultimate safety: scrub any loopback string if it ever crept in
        $xml = str_replace(
            ['http://localhost', 'https://localhost', 'http://127.0.0.1', 'https://127.0.0.1'],
            'https://formnexa.dcpakpattan.gov.pk',
            $xml
        );

        $targets = array_unique([
            public_path('sitemap.xml'),
            base_path('../public_html/sitemap.xml'),
            base_path('public_html/sitemap.xml'),
        ]);

        $writtenCount = 0;
        foreach ($targets as $target) {
            $dir = dirname($target);
            if (is_dir($dir)) {
                if (@file_put_contents($target, $xml) !== false) {
                    @chmod($target, 0666);
                    $writtenCount++;
                }
            }
        }

        $this->info('Sitemap written to public/sitemap.xml with ' . count($entries) . ' URLs (synced across ' . $writtenCount . ' locations).');

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
