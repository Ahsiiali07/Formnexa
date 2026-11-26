<?php

namespace App\Services;

use DOMDocument;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IndexNowService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.indexnow.enabled', true)
            && strlen($this->key()) >= 8;
    }

    public function key(): string
    {
        return trim((string) config('services.indexnow.key', ''));
    }

    public function endpoint(): string
    {
        return trim((string) config('services.indexnow.endpoint', 'https://api.indexnow.org/indexnow'));
    }

    public function host(?string $baseUrl = null): string
    {
        if ($baseUrl) {
            $parsed = parse_url($baseUrl, PHP_URL_HOST);
            if (is_string($parsed) && $parsed !== '' && !str_contains($parsed, 'localhost') && !str_contains($parsed, '127.0.0.1')) {
                return strtolower($parsed);
            }
        }

        return strtolower(trim((string) config('services.indexnow.host', 'formnexa.dcpakpattan.gov.pk')));
    }

    public function baseUrl(?string $override = null): string
    {
        return 'https://' . $this->host($override);
    }

    public function keyLocation(?string $baseUrl = null): string
    {
        return $this->baseUrl($baseUrl) . '/' . $this->key() . '.txt';
    }

    /**
     * Collect all canonical indexable URLs from public/sitemap.xml.
     *
     * @return list<string>
     */
    public function collectSitemapUrls(?string $baseUrl = null): array
    {
        $canonicalBase = $this->baseUrl($baseUrl);
        $path = public_path('sitemap.xml');

        if (!file_exists($path)) {
            Artisan::call('seo:sitemap', ['--url' => $canonicalBase]);
        }

        $xmlContent = file_exists($path) ? (string) file_get_contents($path) : '';
        if ($xmlContent === '') {
            return [];
        }

        $urls = [];
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        if ($dom->loadXML($xmlContent)) {
            foreach ($dom->getElementsByTagName('loc') as $node) {
                $loc = trim($node->textContent);
                if ($loc !== '') {
                    $urls[] = $loc;
                }
            }
        }
        libxml_clear_errors();

        return $this->filterUrlsForHost($urls, $this->host($canonicalBase));
    }

    /**
     * Submit an array of URLs to the IndexNow API in batches of up to 10,000.
     *
     * @param  list<string>  $urls
     * @return array{success: bool, submitted: int, status?: int, message: string}
     */
    public function submitUrls(array $urls, ?string $baseUrl = null): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'submitted' => 0,
                'message' => 'IndexNow is disabled or missing an API key.',
            ];
        }

        $host = $this->host($baseUrl);
        $cleanUrls = $this->filterUrlsForHost($urls, $host);

        if (empty($cleanUrls)) {
            return [
                'success' => false,
                'submitted' => 0,
                'message' => "No valid URLs matching host {$host} were provided.",
            ];
        }

        $key = $this->key();
        $keyLocation = $this->keyLocation($baseUrl);
        $endpoint = $this->endpoint();
        $totalSubmitted = 0;
        $lastStatus = 200;

        foreach (array_chunk($cleanUrls, 10000) as $batch) {
            try {
                $response = Http::timeout(12)
                    ->acceptJson()
                    ->post($endpoint, [
                        'host' => $host,
                        'key' => $key,
                        'keyLocation' => $keyLocation,
                        'urlList' => $batch,
                    ]);

                $lastStatus = $response->status();

                if (!in_array($lastStatus, [200, 202], true)) {
                    $body = trim($response->body());
                    Log::warning('IndexNow submission returned non-success status.', [
                        'status' => $lastStatus,
                        'body' => $body,
                        'count' => count($batch),
                    ]);

                    return [
                        'success' => false,
                        'submitted' => $totalSubmitted,
                        'status' => $lastStatus,
                        'message' => "IndexNow rejected submission with HTTP {$lastStatus}" . ($body !== '' ? ": {$body}" : '.'),
                    ];
                }

                $totalSubmitted += count($batch);
            } catch (\Throwable $e) {
                Log::warning('IndexNow request failed.', [
                    'error' => $e->getMessage(),
                ]);

                return [
                    'success' => false,
                    'submitted' => $totalSubmitted,
                    'message' => 'Could not reach IndexNow API: ' . $e->getMessage(),
                ];
            }
        }

        return [
            'success' => true,
            'submitted' => $totalSubmitted,
            'status' => $lastStatus,
            'message' => "Submitted {$totalSubmitted} URL" . ($totalSubmitted === 1 ? '' : 's') . " to IndexNow (HTTP {$lastStatus}).",
        ];
    }

    /**
     * Non-blocking automatic ping when content changes in production.
     *
     * @param  list<string>  $urls
     */
    public function autoPingUrls(array $urls, ?string $baseUrl = null): void
    {
        if (app()->runningUnitTests() || app()->environment('local', 'testing')) {
            return;
        }

        if (!$this->isConfigured()) {
            return;
        }

        $this->submitUrls($urls, $baseUrl);
    }

    /**
     * Keep only valid HTTPS/HTTP URLs belonging to the target host.
     *
     * @param  list<string>  $urls
     * @return list<string>
     */
    protected function filterUrlsForHost(array $urls, string $host): array
    {
        $filtered = [];

        foreach ($urls as $url) {
            $url = trim((string) $url);
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                continue;
            }

            $parsedHost = strtolower((string) parse_url($url, PHP_URL_HOST));
            if ($parsedHost !== $host) {
                continue;
            }

            $filtered[] = $url;
        }

        return array_values(array_unique($filtered));
    }
}
