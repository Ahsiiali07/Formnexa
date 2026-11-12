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
            ->assertSee('<title>Free Invoice Maker, Resume Builder &amp; PDF Tools | Formnexa</title>', false)
            ->assertSee('<link rel="canonical" href="http://localhost">', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image" content="http://localhost/images/og/default.jpg"', false)
            ->assertSee('name="twitter:card" content="summary_large_image"', false)
            ->assertSee('{"@context":"https://schema.org"', false)
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebSite"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('Free Invoice Maker, <span>Resume Builder &amp; PDF Converter</span>', false)
            ->assertSee('International Invoice Format Guide for Asia, USA &amp; Europe', false)
            ->assertDontSeeText('Pakistan');

        $this->assertSame(1, preg_match_all('/<h1\b/i', $home->getContent()));

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

        // Unique SEO copy + schema on document generator and converter tool
        $quote = $this->get('/quote-generator');
        $quote->assertStatus(200)
            ->assertSee('Free Quote Generator – Make a Quotation PDF | Formnexa', false)
            ->assertSee('Quote vs. Estimate &amp; Sales Order', false)
            ->assertSee('"@type":"FAQPage"', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $quote->getContent()));

        $pdfToWord = $this->get('/convert/pdf-to-word');
        $pdfToWord->assertStatus(200)
            ->assertSee('PDF to Word Converter – Free PDF to DOCX | Formnexa', false)
            ->assertSee('Word to PDF Converter', false)
            ->assertSee('UtilitiesApplication', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $pdfToWord->getContent()));

        $htmlToPdf = $this->get('/convert/html-to-pdf');
        $htmlToPdf->assertStatus(200);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $htmlToPdf->getContent()));
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
    }

    public function test_public_blog_index_category_search_and_article_rendering(): void
    {
        $this->seed(BlogPostSeeder::class);

        $this->get('/blog')
            ->assertStatus(200)
            ->assertSee('Document, Resume &amp; PDF Guides Blog | Formnexa', false)
            ->assertSee('Asia, USA &amp; Europe', false)
            ->assertDontSeeText('Pakistan')
            ->assertSee('"@type":"BreadcrumbList"', false);

        $this->get('/blog/category/resumes')
            ->assertStatus(200)
            ->assertSee('Resumes &amp; ATS Guides', false)
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
        $this->get('/sitemap.xml')->assertDontSee('http://localhost/blog/global-tax-id-guide-usa-europe', false);

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
            ->assertSee('http://localhost/blog/global-tax-id-guide-usa-europe', false)
            ->assertDontSee('http://localhost/admin', false)
            ->assertDontSee('http://localhost/login', false);

        // Delete Blog via Admin
        $this->actingAs($admin)
            ->delete('/admin/blogs/' . $post->id)
            ->assertRedirect('/admin?tab=blogs');

        $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
    }
}
