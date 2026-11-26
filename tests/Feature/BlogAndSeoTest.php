<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Invoice;
use App\Models\Resume;
use App\Models\User;
use Database\Seeders\BlogPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogAndSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_and_tool_pages_have_complete_seo_tags_and_schema(): void
    {
        $this->seed(BlogPostSeeder::class);

        $home = $this->get('/');
        $home->assertStatus(200)
            ->assertSee('<title>Free Invoice Maker, ATS Resume Builder &amp; PDF Tools | Formnexa</title>', false)
            ->assertSee('<link rel="canonical" href="http://localhost">', false)
            ->assertSee('href="http://localhost/favicon.svg?v=2"', false)
            ->assertSee('href="http://localhost/apple-touch-icon.png?v=2"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image" content="http://localhost/images/og/default.jpg"', false)
            ->assertSee('name="twitter:card" content="summary_large_image"', false)
            ->assertSee('{"@context":"https://schema.org"', false)
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"alternateName":"Formnexa"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('Free Invoice Maker, <span>Resume Builder &amp; PDF Converter</span>', false)
            ->assertSee('Practical guides to help you get paid &amp; hired.', false)
            ->assertDontSee('<meta name="keywords"', false)
            ->assertDontSee('<b></b>', false);

        $this->assertSame(1, preg_match_all('/<h1\b/i', $home->getContent()));
        $this->assertSame(0, preg_match_all('/<h4\b/i', $home->getContent()));
        $this->assertSame(0, preg_match_all('/<a\b(?![^>]*\bhref=)[^>]*>/i', $home->getContent()));
        $this->assertSame(0, preg_match_all('/<a\b[^>]*rel=["\']nofollow["\'][^>]*href=["\'](?:https?:\/\/(?:localhost|formnexa\.dcpakpattan\.gov\.pk)|\/)[^"\']*["\']/i', $home->getContent()));

        // Query parameters must strip cleanly from canonical URL and maintain a single H1
        $importView = $this->get('/resume-builder?import=1&utm_source=test');
        $importView->assertStatus(200)
            ->assertSee('<link rel="canonical" href="http://localhost/resume-builder">', false)
            ->assertSee('{"@context":"https://schema.org","@type":"WebApplication"', false)
            ->assertSee('{"@context":"https://schema.org","@type":"BreadcrumbList"', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $importView->getContent()));

        $templates = $this->get('/resume-templates');
        $templates->assertStatus(200);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $templates->getContent()));

        // Dedicated ATS Resume Converter landing page
        $atsConv = $this->get('/ats-resume-converter');
        $atsConv->assertStatus(200)
            ->assertSee('<title>Convert CV to ATS Format Free – Upload PDF or Word | Formnexa</title>', false)
            ->assertSee('Convert Your CV to ATS Format for Free', false)
            ->assertSee('Convert a PDF resume or Word resume to ATS format in a few minutes.', false)
            ->assertSee('Most CVs convert cleanly, but always review the result in the editor before you download:', false)
            ->assertSee('Single-column layouts such as Wall Street ATS Classic are generally the safest choice for automated screening.', false)
            ->assertSee('Is my CV stored?', false)
            ->assertSee('{"@context":"https://schema.org","@type":"WebApplication"', false)
            ->assertSee('{"@context":"https://schema.org","@type":"BreadcrumbList"', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $atsConv->getContent()));

        // Unique SEO copy + schema on document generator and converter tool
        $quote = $this->get('/quote-generator');
        $quote->assertStatus(200)
            ->assertSee('Free Quote Generator – Make a Quotation PDF | Formnexa', false)
            ->assertSee('Quote vs. Estimate &amp; Sales Order', false)
            ->assertSee('"@type":"FAQPage"', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $quote->getContent()));

        $pdfToWord = $this->get('/convert/pdf-to-word');
        $pdfToWord->assertStatus(200)
            ->assertSee('PDF to Word Converter: Free, Keeps Formatting | Formnexa', false)
            ->assertSee('Word to PDF Converter', false)
            ->assertSee('UtilitiesApplication', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $pdfToWord->getContent()));

        $htmlToPdf = $this->get('/convert/html-to-pdf');
        $htmlToPdf->assertStatus(200);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $htmlToPdf->getContent()));

        // Verify trailing-slash and www canonical 301 redirects
        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $slashReq = \Illuminate\Http\Request::create('http://localhost/ats-resume-checker/', 'GET');
        $slashRes = $kernel->handle($slashReq);
        $this->assertSame(301, $slashRes->getStatusCode());
        $this->assertSame('http://localhost/ats-resume-checker', $slashRes->headers->get('Location'));

        $wwwReq = \Illuminate\Http\Request::create('https://www.formnexa.dcpakpattan.gov.pk/paystub-generator/', 'GET');
        $wwwRes = $kernel->handle($wwwReq);
        $this->assertSame(301, $wwwRes->getStatusCode());
        $this->assertSame('https://formnexa.dcpakpattan.gov.pk/paystub-generator', $wwwRes->headers->get('Location'));
    }

    public function test_private_and_auth_routes_emit_noindex_headers_and_meta(): void
    {
        $login = $this->get('/login');
        $login->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('<meta name="robots" content="noindex,follow">', false);

        $this->get('/signup')
            ->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $invoice = Invoice::create([
            'invoice_number' => 'INV-9001',
            'invoice_title' => 'INVOICE',
            'from_name' => 'Studio Alpha',
            'to_name' => 'Client Beta',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'total' => 250,
            'balance_due' => 250,
        ]);

        $this->get('/invoice/' . $invoice->uuid)
            ->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);

        $resume = Resume::create([
            'title' => 'Jane Doe CV',
            'template_style' => 'flow_sidebar',
            'content' => ['personal' => ['full_name' => 'Jane Doe']],
        ]);

        $this->get('/resume/' . $resume->uuid)
            ->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('<meta name="robots" content="noindex,nofollow">', false);

        // Ensure /api/* endpoints return X-Robots-Tag: noindex, nofollow (including 405 MethodNotAllowed)
        $this->get('/api/v1/bootstrap')
            ->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        $this->get('/api/resumes')
            ->assertStatus(405)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }

    public function test_public_blog_index_category_search_and_article_rendering(): void
    {
        $this->seed(BlogPostSeeder::class);

        $this->get('/blog')
            ->assertStatus(200)
            ->assertSee('Document, Resume &amp; PDF Guides Blog | Formnexa', false)
            ->assertSee('Asia, USA &amp; Europe', false)
            ->assertSee('Browse Guides by Category')
            ->assertSee('Resume and ATS')
            ->assertSee('Careers')
            ->assertSee('Invoicing and Payroll')
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('/blog/category/resumes')
            ->assertStatus(200)
            ->assertSee('ATS Resume &amp; CV Guides – Formatting Rules &amp; Layouts | Formnexa Blog', false)
            ->assertSee('Modern hiring relies heavily on Applicant Tracking Systems (ATS)')
            ->assertSee('ATS-Friendly Resume');

        $this->get('/blog?q=Europe')
            ->assertStatus(200)
            ->assertSee('<meta name="robots" content="noindex,follow">', false)
            ->assertSee('<link rel="canonical" href="http://localhost/blog">', false)
            ->assertSee('Asia, USA &amp; Europe', false);

        $this->get('/blog/international-invoice-format-asia-usa-europe-guide')
            ->assertStatus(200)
            ->assertSee('property="og:type" content="article"', false)
            ->assertSee('property="og:image" content="http://localhost/images/og/default.jpg"', false)
            ->assertSee('property="og:image:width" content="1200"', false)
            ->assertSee('property="og:image:height" content="630"', false)
            ->assertSee('name="twitter:image" content="http://localhost/images/og/default.jpg"', false)
            ->assertSee('property="article:published_time"', false)
            ->assertSee('property="article:modified_time"', false)
            ->assertSee('{"@context":"https://schema.org","@type":"BlogPosting"', false)
            ->assertSee('{"@context":"https://schema.org","@type":"BreadcrumbList"', false)
            ->assertSee('"name":"International Invoice Format Guide for Asia, USA & Europe (Free Template)"', false)
            ->assertSee('Open Free Multi-Currency Invoice Generator', false)
            ->assertSee('<h2 id="essential-elements-of-a-cross-border-business-invoice"', false);

        $this->get('/blog/non-existent-slug-999')->assertStatus(404);
    }

    public function test_phase5_cluster_blog_posts_are_drafts_meet_seo_criteria_and_render_for_admin(): void
    {
        $this->seed(BlogPostSeeder::class);
        $admin = User::factory()->create(['is_admin' => true]);

        $slugs = [
            'ats-resume-score-explained',
            'how-to-check-if-your-resume-is-ats-friendly',
            'ats-resume-keywords-how-to-match-a-job-description',
            'how-to-write-a-resignation-letter',
            'two-weeks-notice-letter-examples',
            'how-to-resign-professionally-checklist',
            'resignation-letter-for-new-job',
            'what-is-on-a-pay-stub',
            'paystub-vs-payslip-vs-salary-slip',
            'gross-pay-vs-net-pay-explained',
            'how-to-create-pay-stubs-for-employees',
            'salary-slip-format-pakistan-india-uae',
        ];

        foreach ($slugs as $slug) {
            $post = BlogPost::where('slug', $slug)->firstOrFail();
            $this->assertFalse($post->is_published, "Post {$slug} should start as draft");
            $this->assertGreaterThanOrEqual(900, str_word_count(strip_tags($post->content)), "Post {$slug} word count check");
            $this->assertLessThanOrEqual(60, strlen((string) $post->meta_title), "Post {$slug} meta_title <= 60");
            $this->assertLessThanOrEqual(155, strlen((string) $post->meta_description), "Post {$slug} meta_description <= 155");

            // Guests see 404 for unpublished drafts; admin sees draft preview with full SEO schema
            $this->get('/blog/' . $slug)->assertStatus(404);
            $this->actingAs($admin)
                ->get('/blog/' . $slug)
                ->assertStatus(200)
                ->assertSee('Draft Preview')
                ->assertSee('Published ')
                ->assertSee('Updated ')
                ->assertSee('{"@context":"https://schema.org","@type":"BlogPosting"', false)
                ->assertSee('{"@context":"https://schema.org","@type":"BreadcrumbList"', false);
            auth()->logout();
        }
    }

    public function test_admin_dashboard_blogs_crud_active_toggle_and_modules_data(): void
    {
        $this->seed(BlogPostSeeder::class);

        $regularUser = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        Invoice::create([
            'user_id' => $regularUser->id,
            'invoice_number' => 'INV-7001',
            'invoice_title' => 'INVOICE',
            'from_name' => 'Euro Studio',
            'to_name' => 'Asia Labs',
            'currency' => 'EUR',
            'currency_symbol' => '€',
            'total' => 1200,
            'balance_due' => 1200,
            'status' => 'draft',
        ]);

        Resume::create([
            'user_id' => $regularUser->id,
            'title' => 'Product Lead CV',
            'template_style' => 'flow_sidebar',
            'content' => ['personal' => ['full_name' => 'Alex Morgan']],
        ]);

        // Unauthenticated and non-admin blocked
        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs($regularUser)->get('/admin')->assertStatus(403);

        // Admin sees all module data on /admin
        $this->actingAs($admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Admin Dashboard')
            ->assertSee('INV-7001')
            ->assertSee('Product Lead CV')
            ->assertSee($regularUser->email);

        // Add Blog via Admin
        $this->actingAs($admin)->post('/admin/blogs', [
            'title' => 'Global Tax ID Guide for USA and Europe Billing',
            'slug' => 'global-tax-id-guide-usa-europe',
            'category' => 'global-billing',
            'excerpt' => 'Short guide to formatting EIN and VAT IDs on international client invoices.',
            'content' => "## Regional Tax IDs\n\nInclude EIN for US clients and VAT ID across Europe.",
            'featured_tool_route' => 'invoice.studio',
            'featured_tool_label' => 'Launch Invoice Maker',
            'focus_keyword' => 'USA Europe invoice tax id',
            'is_published' => '1',
        ])->assertRedirect('/admin?tab=blogs');

        $post = BlogPost::where('slug', 'global-tax-id-guide-usa-europe')->firstOrFail();
        $this->assertTrue($post->is_published);

        // Edit Blog via Admin
        $this->actingAs($admin)->put('/admin/blogs/' . $post->id, [
            'title' => 'Updated Tax ID Guide for Asia, USA & Europe',
            'slug' => 'global-tax-id-guide-usa-europe',
            'category' => 'global-billing',
            'excerpt' => 'Updated short guide covering Asia, USA, and Europe billing fields.',
            'content' => "## Regional Tax IDs\n\nInclude EIN, VAT ID, or GST registration clearly.",
            'is_published' => '1',
        ])->assertRedirect('/admin?tab=blogs');

        $this->assertSame('Updated Tax ID Guide for Asia, USA & Europe', $post->fresh()->title);

        // Toggle Active -> Inactive hides it from public visitors & sitemap (logged-out gets 404)
        $this->actingAs($admin)
            ->patch('/admin/blogs/' . $post->id . '/toggle')
            ->assertRedirect('/admin?tab=blogs');

        $this->assertFalse($post->fresh()->is_published);
        auth()->logout();
        $this->get('/blog/' . $post->slug)->assertStatus(404);
        $this->get('/sitemap.xml')->assertDontSee('https://formnexa.dcpakpattan.gov.pk/blog/global-tax-id-guide-usa-europe', false);

        // Toggle Inactive -> Active restores it cleanly
        $this->actingAs($admin)
            ->patch('/admin/blogs/' . $post->id . '/toggle')
            ->assertRedirect('/admin?tab=blogs');

        $this->assertTrue($post->fresh()->is_published);
        $this->get('/blog/' . $post->slug)->assertStatus(200);

        // Sitemap contains active post and omits private/admin routes
        $this->actingAs($admin)->post('/admin/seo/sitemap')->assertRedirect('/admin?tab=seo');
        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertSee('https://formnexa.dcpakpattan.gov.pk/blog/global-tax-id-guide-usa-europe', false)
            ->assertDontSee('localhost', false)
            ->assertDontSee('127.0.0.1', false)
            ->assertDontSee('/admin', false)
            ->assertDontSee('/login', false);

        // Delete Blog via Admin
        $this->actingAs($admin)
            ->delete('/admin/blogs/' . $post->id)
            ->assertRedirect('/admin?tab=blogs');

        $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
    }

    public function test_gsc_invalid_template_redirects_and_no_raw_js_api_links(): void
    {
        $this->get('/invoice-generator?template=undefined&style=modern')
            ->assertStatus(301)
            ->assertRedirect(route('invoice.studio'));

        $this->get('/resume-builder?template=undefined')
            ->assertStatus(301)
            ->assertRedirect(route('resume.builder'));

        $this->get('/invoice')
            ->assertStatus(301)
            ->assertRedirect('/invoice-generator');

        $this->get('/email')
            ->assertStatus(301)
            ->assertRedirect('/invoice-generator');

        $invoiceStudio = $this->get('/invoice-generator')->assertOk()->getContent();
        $this->assertStringNotContainsString("fetch('/api/invoices'", $invoiceStudio);
        $this->assertStringNotContainsString('"/email"', $invoiceStudio);

        $resumeBuilder = $this->get('/resume-builder')->assertOk()->getContent();
        $this->assertStringNotContainsString("fetch('/api/resumes'", $resumeBuilder);
        $this->assertStringNotContainsString("fetch('/api/resumes/import'", $resumeBuilder);

        $templates = $this->get('/templates')->assertOk()->getContent();
        $this->assertStringContainsString('rel="nofollow"', $templates);

        $resumeTemplates = $this->get('/resume-templates')->assertOk()->getContent();
        $this->assertStringContainsString('rel="nofollow"', $resumeTemplates);
    }
}
