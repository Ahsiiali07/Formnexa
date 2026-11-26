<?php

namespace App\Console\Commands;

use App\Services\IndexNowService;
use Illuminate\Console\Command;

class SubmitIndexNow extends Command
{
    protected $signature = 'seo:indexnow {--url= : Comma-separated URLs to submit (defaults to all URLs in sitemap.xml)} {--base= : Override canonical base URL (defaults to production host)}';

    protected $description = 'Submit Formnexa URLs to Bing/IndexNow for fast search engine discovery';

    public function handle(IndexNowService $indexNow): int
    {
        if (!$indexNow->isConfigured()) {
            $this->error('IndexNow is not configured or disabled.');

            return self::FAILURE;
        }

        $baseOverride = trim((string) $this->option('base')) ?: null;
        $rawUrls = trim((string) $this->option('url'));

        $urls = $rawUrls !== ''
            ? array_values(array_filter(array_map('trim', explode(',', $rawUrls))))
            : $indexNow->collectSitemapUrls($baseOverride);

        if (empty($urls)) {
            $this->warn('No indexable URLs found to submit.');

            return self::FAILURE;
        }

        $result = $indexNow->submitUrls($urls, $baseOverride);

        if ($result['success']) {
            $this->info($result['message'] . ' (Key location: ' . $indexNow->keyLocation($baseOverride) . ')');

            return self::SUCCESS;
        }

        $this->error($result['message']);

        return self::FAILURE;
    }
}
