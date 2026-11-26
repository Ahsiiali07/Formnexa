<?php

namespace Tests\Feature;

use Tests\TestCase;

class NewToolsTest extends TestCase
{
    /**
     * Test ATS Resume Checker page loads with HTTP 200 and SEO tags.
     */
    public function test_ats_resume_checker_page_loads_with_seo(): void
    {
        $response = $this->get('/ats-resume-checker');
        $response->assertStatus(200);
        $response->assertSee('<title>Free ATS Resume Checker: Instant Score, No Sign-up</title>', false);
        $response->assertSee('Free ATS Resume Checker');
        $response->assertSee('How the Formnexa ATS Score Works');
        $response->assertSee('images/og/ats-resume-checker.jpg', false);
        $response->assertSee('"@type":"WebApplication"', false);
        $response->assertSee('"applicationCategory":"BusinessApplication"', false);
        $response->assertSee('"@type":"FAQPage"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $response->assertDontSee('ATS-certified');
        $response->assertDontSee('Jobscan');
        $response->assertDontSee('Resume Worded');
        $this->assertSame(1, preg_match_all('/<h1\b/i', $response->getContent()));
    }

    /**
     * Test ATS Resume Checker API audit endpoint.
     */
    public function test_ats_resume_checker_api_analyzes_resume(): void
    {
        $sampleText = "Jordan Michaels\njordan.michaels@example.com\n(555) 234-5678\nSan Francisco, CA\nlinkedin.com/in/jordanmichaels\n\nPROFESSIONAL SUMMARY\nResults-driven Senior Full-Stack Engineer with 7+ years of experience.\n\nWORK EXPERIENCE\nSenior Software Engineer | Apex Solutions (2021 – Present)\n• Spearheaded microservices architecture reducing server latency by 45%.\n• Architected automated CI/CD pipelines across 14 deployments.\n\nEDUCATION\nBS Computer Science, UC Berkeley (2018)\n\nSKILLS\nPHP, Laravel, Python, Docker, AWS, PostgreSQL";

        $response = $this->postJson('/api/resume/check', [
            'resume_text' => $sampleText,
            'job_description' => 'Looking for a Senior Software Engineer with strong experience in Laravel, Python, and Docker.',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'overall_score',
            'grade',
            'categories' => [
                'contact',
                'sections',
                'impact',
                'formatting',
                'keywords',
            ],
            'recommendations',
        ]);

        $json = $response->json();
        $this->assertTrue($json['overall_score'] >= 50);
        $this->assertContains('laravel', $json['categories']['keywords']['matched']);
    }

    /**
     * Test ATS Resume Checker API validates empty input.
     */
    public function test_ats_resume_checker_validates_empty_input(): void
    {
        $response = $this->postJson('/api/resume/check', [
            'resume_text' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test Resignation Letter Generator loads with HTTP 200.
     */
    public function test_resignation_letter_page_loads_and_redirects(): void
    {
        $redirect = $this->get('/resignation-letter');
        $redirect->assertRedirect('/resignation-letter-generator');

        $response = $this->get('/resignation-letter-generator');
        $response->assertStatus(200);
        $response->assertSee('<title>Free Resignation Letter Generator (Two Weeks Notice)</title>', false);
        $response->assertSee('Free Resignation Letter &amp; Two Weeks Notice Generator', false);
        $response->assertSee('images/og/resignation-letter-generator.jpg', false);
        $response->assertSee('"@type":"WebApplication"', false);
        $response->assertSee('"@type":"FAQPage"', false);
        $response->assertSee('"@type":"BreadcrumbList"', false);
        $this->assertSame(1, preg_match_all('/<h1\b/i', $response->getContent()));

        foreach ([
            '/resignation-letter/new-job' => 'Resignation Letter Generator for a New Job',
            '/resignation-letter/two-weeks-notice' => 'Two Weeks Notice Letter Generator',
            '/resignation-letter/relocation' => 'Resignation Letter Generator for Relocation',
            '/resignation-letter/personal-reasons' => 'Resignation Letter for Personal Reasons',
            '/resignation-letter/short-notice' => 'Short Notice Resignation Letter Generator',
        ] as $url => $expectedH1) {
            $landing = $this->get($url);
            $landing->assertStatus(200)
                ->assertSee($expectedH1)
                ->assertSee('<link rel="canonical" href="http://localhost' . $url . '">', false)
                ->assertSee('"@type":"WebApplication"', false)
                ->assertSee('"@type":"FAQPage"', false)
                ->assertSee('"@type":"BreadcrumbList"', false);
            $this->assertSame(1, preg_match_all('/<h1\b/i', $landing->getContent()));
        }
    }

    /**
     * Test Resignation Letter PDF and Word exports.
     */
    public function test_resignation_letter_exports(): void
    {
        $payload = [
            'employee_name' => 'John Doe',
            'employee_title' => 'Product Manager',
            'company_name' => 'Tech Corp',
            'manager_name' => 'Jane Smith',
            'date' => 'November 26, 2026',
            'last_day' => 'December 10, 2026',
            'reason' => 'new_job',
            'tone' => 'grateful',
            'letter_body' => 'Please accept this letter as formal notification that I am resigning.',
        ];

        // Test PDF export
        $pdfResponse = $this->post('/resignation-letter/pdf', $payload);
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('content-type', 'application/pdf');

        // Test Word .doc export
        $docResponse = $this->post('/resignation-letter/doc', $payload);
        $docResponse->assertStatus(200);
        $this->assertStringContainsString('application/msword', (string) $docResponse->headers->get('content-type'));
    }

    /**
     * Test Paystub Generator and regional Salary Slip Generator load with HTTP 200 and clean SEO/trust signals.
     */
    public function test_paystub_generator_loads_and_redirects(): void
    {
        $response = $this->get('/paystub-generator');
        $response->assertStatus(200)
            ->assertSee('<title>Free Paystub &amp; Salary Slip Generator (PDF)</title>', false)
            ->assertSee('Free Paystub &amp; Salary Slip Generator', false)
            ->assertSee('For employers, small businesses and freelancers creating payroll records. Do not use generated documents to misrepresent income.')
            ->assertSee('images/og/paystub-generator.jpg', false)
            ->assertSee('"@type":"WebApplication"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertDontSee('FX-AUTHENTIC')
            ->assertDontSee('authentic');
        $this->assertSame(1, preg_match_all('/<h1\b/i', $response->getContent()));

        $regional = $this->get('/salary-slip-generator');
        $regional->assertStatus(200)
            ->assertSee('<title>Free Salary Slip Generator for Pakistan, India and UAE</title>', false)
            ->assertSee('Free Salary Slip Generator for Pakistan, India &amp; UAE', false)
            ->assertSee('<link rel="canonical" href="http://localhost/salary-slip-generator">', false)
            ->assertSee('"@type":"WebApplication"', false)
            ->assertSee('"@type":"FAQPage"', false)
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertDontSee('FX-AUTHENTIC')
            ->assertDontSee('authentic');
        $this->assertSame(1, preg_match_all('/<h1\b/i', $regional->getContent()));
    }

    /**
     * Verify Phase 6 internal linking matrix paths appear cleanly across site views.
     */
    public function test_internal_linking_matrix_across_pages(): void
    {
        $this->get('/')->assertStatus(200)
            ->assertSee('/ats-resume-checker')
            ->assertSee('/resignation-letter-generator')
            ->assertSee('/paystub-generator')
            ->assertSee('/salary-slip-generator');

        $this->get('/resume-builder')->assertStatus(200)->assertSee('/ats-resume-checker');
        $this->get('/resume-templates')->assertStatus(200)->assertSee('/ats-resume-checker');
        $this->get('/ats-resume-converter')->assertStatus(200)->assertSee('/ats-resume-checker');
        $this->get('/convert/pdf-to-ats')->assertStatus(200)->assertSee('/ats-resume-checker');

        $this->get('/invoice-generator')->assertStatus(200)->assertSee('/paystub-generator');
        $this->get('/timesheet-generator')->assertStatus(200)->assertSee('/paystub-generator');
    }

    /**
     * Test Paystub PDF generation.
     */
    public function test_paystub_pdf_generation(): void
    {
        $payload = [
            'company' => [
                'name' => 'Acme Corporation',
                'address' => '123 Main St, Cityville',
                'ein' => '12-3456789',
            ],
            'employee' => [
                'name' => 'Alice Walker',
                'title' => 'Financial Analyst',
                'id' => 'EMP-100',
                'ssn_last4' => '***-**-4455',
                'address' => '456 Oak Avenue',
            ],
            'pay_period' => [
                'frequency' => 'Monthly',
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-30',
                'pay_date' => '2026-11-30',
            ],
            'currency' => [
                'symbol' => '$',
            ],
            'earnings' => [
                [
                    'description' => 'Base Salary',
                    'hours' => 160,
                    'rate' => 30,
                    'amount' => 4800,
                    'ytd' => 48000,
                ],
            ],
            'deductions' => [
                [
                    'description' => 'Income Tax',
                    'amount' => 600,
                    'ytd' => 6000,
                ],
            ],
        ];

        $response = $this->post('/paystub-generator/pdf', $payload);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
