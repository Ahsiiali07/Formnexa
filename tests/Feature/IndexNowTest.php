<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IndexNowTest extends TestCase
{
    use RefreshDatabase;

    public function test_indexnow_key_verification_route_serves_key_and_rejects_mismatches(): void
    {
        config([
            'services.indexnow.enabled' => true,
            'services.indexnow.key' => '4e9a6c2f8d1b4e7390a5c8f12d6b3e94',
        ]);

        $this->get('/4e9a6c2f8d1b4e7390a5c8f12d6b3e94.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('4e9a6c2f8d1b4e7390a5c8f12d6b3e94', false);

        $this->get('/wrongkeyvalue1234.txt')->assertNotFound();
    }

    public function test_artisan_seo_indexnow_submits_sitemap_urls_to_indexnow_api(): void
    {
        config([
            'services.indexnow.enabled' => true,
            'services.indexnow.key' => '4e9a6c2f8d1b4e7390a5c8f12d6b3e94',
            'services.indexnow.endpoint' => 'https://api.indexnow.org/indexnow',
            'services.indexnow.host' => 'formnexa.dcpakpattan.gov.pk',
        ]);

        Http::fake([
            'https://api.indexnow.org/indexnow' => Http::response('', 200),
        ]);

        $this->artisan('seo:indexnow', [
            '--url' => 'https://formnexa.dcpakpattan.gov.pk/,https://formnexa.dcpakpattan.gov.pk/ats-resume-checker',
        ])->assertExitCode(0);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.indexnow.org/indexnow'
                && $request['host'] === 'formnexa.dcpakpattan.gov.pk'
                && $request['key'] === '4e9a6c2f8d1b4e7390a5c8f12d6b3e94'
                && $request['keyLocation'] === 'https://formnexa.dcpakpattan.gov.pk/4e9a6c2f8d1b4e7390a5c8f12d6b3e94.txt'
                && count($request['urlList']) === 2;
        });
    }

    public function test_admin_can_trigger_indexnow_submission(): void
    {
        config([
            'services.indexnow.enabled' => true,
            'services.indexnow.key' => '4e9a6c2f8d1b4e7390a5c8f12d6b3e94',
            'services.indexnow.endpoint' => 'https://api.indexnow.org/indexnow',
            'services.indexnow.host' => 'formnexa.dcpakpattan.gov.pk',
        ]);

        Http::fake([
            'https://api.indexnow.org/indexnow' => Http::response('', 202),
        ]);

        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post('/admin/seo/indexnow', [
                'sitemap_url' => 'https://formnexa.dcpakpattan.gov.pk',
            ])
            ->assertRedirect('/admin?tab=seo')
            ->assertSessionHas('success');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.indexnow.org/indexnow'
                && $request['host'] === 'formnexa.dcpakpattan.gov.pk'
                && count($request['urlList']) > 10;
        });
    }
}
