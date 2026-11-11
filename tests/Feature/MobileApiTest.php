<?php

namespace Tests\Feature;

use App\Mail\SendInvoiceMail;
use App\Models\Invoice;
use App\Models\Resume;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpPresentation\IOFactory as PptIOFactory;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpSpreadsheet\IOFactory as SheetIOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\PhpWord;
use Tests\TestCase;
use ZipArchive;

class MobileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_website_pages_remain_operational(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/invoice-generator')->assertStatus(200);
        $this->get('/templates')->assertStatus(200);
        $this->get('/resume-builder')->assertStatus(200);
        $this->get('/resume-templates')->assertStatus(200);
        $this->get('/convert')->assertStatus(200);
        $this->get('/convert/jpg-to-pdf')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/signup')->assertStatus(200);
    }

    public function test_catalog_and_bootstrap_endpoints(): void
    {
        $bootstrap = $this->getJson('/api/v1/bootstrap');
        $bootstrap->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(12, 'invoice.generators')
            ->assertJsonCount(24, 'invoice.currencies')
            ->assertJsonCount(10, 'converter.tools');

        $invConfig = $this->getJson('/api/v1/invoices/config');
        $invConfig->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(12, 'generators')
            ->assertJsonCount(6, 'styles')
            ->assertJsonCount(24, 'currencies')
            ->assertJsonCount(3, 'cover_presets');

        $invTemplates = $this->getJson('/api/v1/invoices/templates?category=freelance&q=Developer');
        $invTemplates->assertOk()->assertJsonPath('success', true);
        $this->assertNotEmpty($invTemplates->json('templates'));

        $resConfig = $this->getJson('/api/v1/resumes/config');
        $resConfig->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(10, 'styles')
            ->assertJsonStructure(['default_content', 'blank_content']);

        $resTemplates = $this->getJson('/api/v1/resumes/templates?category=tech');
        $resTemplates->assertOk()->assertJsonPath('success', true);
        $this->assertNotEmpty($resTemplates->json('templates'));

        $converters = $this->getJson('/api/v1/converters');
        $converters->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(5, 'groups.to_pdf')
            ->assertJsonCount(5, 'groups.from_pdf');

        $this->getJson('/api/v1/converters/pdf-to-jpg')
            ->assertOk()
            ->assertJsonPath('tool.slug', 'pdf-to-jpg')
            ->assertJsonPath('tool.schema.upload_field', 'file');

        $this->getJson('/api/v1/converters/nonexistent-tool')->assertStatus(404);
    }

    public function test_auth_flow_and_guest_document_claiming(): void
    {
        // Create a guest invoice and a guest resume before registering
        $guestInv = $this->postJson('/api/v1/invoices', [
            'invoice_number' => 'INV-GUEST1',
            'items' => [['description' => 'Design Sprint', 'quantity' => 2, 'unit_price' => 500]],
        ])->assertCreated();
        $invUuid = $guestInv->json('uuid');

        $guestRes = $this->postJson('/api/v1/resumes', [
            'title' => 'Guest CV',
            'content' => ['personal' => ['full_name' => 'Alex Guest']],
        ])->assertCreated();
        $resUuid = $guestRes->json('uuid');

        // Register and claim guest documents
        $reg = $this->postJson('/api/v1/auth/register', [
            'name' => 'Alex Mobile',
            'email' => 'alex@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'device_name' => 'Pixel 9',
            'guest_invoice_uuids' => [$invUuid],
            'guest_resume_uuids' => [$resUuid],
        ]);

        $reg->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonPath('claimed.invoices', 1)
            ->assertJsonPath('claimed.resumes', 1);

        $token = $reg->json('token');
        $this->assertNotEmpty($token);

        // Fetch /me
        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('user.email', 'alex@example.com')
            ->assertJsonPath('stats.total_count', 1)
            ->assertJsonPath('stats.resume_count', 1);

        // Invalid token on optional-auth endpoint must be rejected (401)
        $this->withToken('invalid-token')
            ->getJson('/api/v1/invoices')
            ->assertStatus(401);

        // Logout revokes token
        $this->withToken($token)
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        $this->withToken($token)
            ->getJson('/api/v1/auth/me')
            ->assertStatus(401);

        // Login issues fresh token
        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'alex@example.com',
            'password' => 'secret123',
            'device_name' => 'iPhone 16',
        ])->assertOk();

        $this->assertNotEmpty($login->json('token'));
    }

    public function test_full_invoice_lifecycle_guest_and_authenticated(): void
    {
        Mail::fake();

        // 1. Create invoice as guest (with calculations)
        $create = $this->postJson('/api/v1/invoices', [
            'invoice_title' => 'QUOTE',
            'invoice_number' => 'QUO-9001',
            'currency' => 'EUR',
            'currency_symbol' => '€',
            'from_name' => 'Studio North',
            'to_name' => 'Acme GmbH',
            'to_email' => 'billing@acme.example',
            'discount_type' => 'percent',
            'discount_rate' => 10,
            'tax_rate' => 20,
            'shipping_fee' => 15,
            'amount_paid' => 50,
            'items' => [
                ['description' => 'UI Design', 'quantity' => 2, 'unit_price' => 100],
            ],
        ]);

        // Subtotal: 200, discount 10%: 20, taxable: 180, tax 20%: 36, shipping: 15 => total: 231, paid: 50 => balance: 181
        $create->assertCreated()
            ->assertJsonPath('invoice.subtotal', 200)
            ->assertJsonPath('invoice.discount_amount', 20)
            ->assertJsonPath('invoice.tax_amount', 36)
            ->assertJsonPath('invoice.total', 231)
            ->assertJsonPath('invoice.balance_due', 181)
            ->assertJsonPath('invoice.status', 'draft');

        $uuid = $create->json('uuid');

        // 2. Guest list & quick history
        $this->getJson('/api/v1/invoices?uuids=' . $uuid)
            ->assertOk()
            ->assertJsonPath('stats.total_count', 1)
            ->assertJsonCount(1, 'data');

        $this->getJson('/api/v1/invoices/history?uuids=' . $uuid)
            ->assertOk()
            ->assertJsonCount(1, 'data');

        // 3. Update via PUT with multipart logo upload + JSON items
        $logo = UploadedFile::fake()->image('logo.png', 100, 100);
        $update = $this->put('/api/v1/invoices/' . $uuid, [
            'invoice_number' => 'QUO-9001-REV',
            'logo_file' => $logo,
            'items' => json_encode([
                ['description' => 'UI Design Updated', 'quantity' => 3, 'unit_price' => 100],
            ]),
        ], ['Accept' => 'application/json']);

        $update->assertOk()
            ->assertJsonPath('invoice.invoice_number', 'QUO-9001-REV')
            ->assertJsonPath('invoice.total', 300);
        $this->assertStringStartsWith('data:image/', (string) $update->json('invoice.logo_data'));

        // 4. Download PDF
        $pdfResp = $this->get('/api/v1/invoices/' . $uuid . '/pdf');
        $pdfResp->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfResp->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $pdfResp->getContent());

        // 5. Email invoice
        $this->postJson('/api/v1/invoices/' . $uuid . '/email', [
            'to_email' => 'client@example.com',
            'subject' => 'Your Quote',
            'message' => 'Please review.',
        ])->assertOk()->assertJsonPath('status', 'sent');

        Mail::assertSent(SendInvoiceMail::class, 1);

        // 6. Public view
        $this->getJson('/api/v1/public/invoices/' . $uuid)
            ->assertOk()
            ->assertJsonPath('invoice.uuid', $uuid);

        // 7. Claim by owner & enforce cross-user 403
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $ownerToken = $this->postJson('/api/v1/auth/login', ['email' => $owner->email, 'password' => 'password'])->json('token');
        $otherToken = $this->postJson('/api/v1/auth/login', ['email' => $other->email, 'password' => 'password'])->json('token');

        $this->withToken($ownerToken)
            ->postJson('/api/v1/auth/claim', ['guest_invoice_uuids' => [$uuid]])
            ->assertOk()
            ->assertJsonPath('claimed.invoices', 1);

        // Other user cannot view/update/delete owner's invoice
        $this->withToken($otherToken)->getJson('/api/v1/invoices/' . $uuid)->assertStatus(403);
        $this->withToken($otherToken)->patchJson('/api/v1/invoices/' . $uuid . '/status', ['status' => 'paid'])->assertStatus(403);
        $this->withToken($otherToken)->deleteJson('/api/v1/invoices/' . $uuid)->assertStatus(403);

        // Owner updates status & deletes
        $this->withToken($ownerToken)
            ->patchJson('/api/v1/invoices/' . $uuid . '/status', ['status' => 'paid'])
            ->assertOk()
            ->assertJsonPath('invoice.status', 'paid');

        $this->withToken($ownerToken)
            ->getJson('/api/v1/history?status=paid')
            ->assertOk()
            ->assertJsonPath('stats.paid_count', 1)
            ->assertJsonCount(1, 'invoices.data');

        $this->withToken($ownerToken)
            ->deleteJson('/api/v1/invoices/' . $uuid)
            ->assertOk();

        $this->assertDatabaseMissing('invoices', ['uuid' => $uuid]);
    }

    public function test_full_resume_lifecycle_import_save_pdf_and_delete(): void
    {
        // 1. Import CV text
        $import = $this->postJson('/api/v1/resumes/import', [
            'cv_text' => "Jordan Michaels\nSenior Product Manager\njordan@example.com | +1 415-890-3321\n\nEXPERIENCE\nSenior PM | Horizon Cloud | 2022 - Present\n• Grew ARR by 34%\n\nSKILLS\nRoadmapping, SQL, Figma",
        ]);
        $import->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('parsed.personal.email', 'jordan@example.com');

        // 2. Import CV DOCX file
        $phpWord = new PhpWord();
        $sec = $phpWord->addSection();
        $sec->addText('Jane Developer');
        $sec->addText('jane@dev.io');
        $sec->addText('SKILLS');
        $sec->addText('Laravel, Flutter, Docker');
        $docxTmp = tempnam(sys_get_temp_dir(), 'cv_') . '.docx';
        WordIOFactory::createWriter($phpWord, 'Word2007')->save($docxTmp);
        $docxUpload = new UploadedFile($docxTmp, 'resume.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true);

        $this->post('/api/v1/resumes/import', ['cv_file' => $docxUpload], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('parsed.personal.email', 'jane@dev.io');
        @unlink($docxTmp);

        // 3. Create resume with photo upload & multipart JSON content
        $photo = UploadedFile::fake()->image('avatar.jpg', 120, 120);
        $create = $this->post('/api/v1/resumes', [
            'title' => 'Jordan PM Resume',
            'template_style' => 'editorial_figma',
            'accent_color' => '#7C1D3A',
            'font_family' => 'serif',
            'density' => 'compact',
            'photo_file' => $photo,
            'content' => json_encode($import->json('parsed')),
        ], ['Accept' => 'application/json']);

        $create->assertCreated()
            ->assertJsonPath('resume.template_style', 'editorial_figma')
            ->assertJsonPath('resume.density', 'compact');

        $uuid = $create->json('uuid');

        // 4. Download Resume PDF
        $pdfResp = $this->get('/api/v1/resumes/' . $uuid . '/pdf');
        $pdfResp->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdfResp->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $pdfResp->getContent());

        // 5. Claim, update, check history, and delete
        $user = User::factory()->create();
        $token = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'guest_resume_uuids' => [$uuid],
        ])->assertOk()->json('token');

        $this->withToken($token)
            ->putJson('/api/v1/resumes/' . $uuid, [
                'title' => 'Updated PM Resume',
                'content' => $import->json('parsed'),
            ])
            ->assertOk()
            ->assertJsonPath('resume.title', 'Updated PM Resume');

        $this->withToken($token)
            ->getJson('/api/v1/resumes')
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->withToken($token)
            ->getJson('/api/v1/history')
            ->assertOk()
            ->assertJsonPath('stats.resume_count', 1);

        $this->withToken($token)
            ->deleteJson('/api/v1/resumes/' . $uuid)
            ->assertOk();

        $this->assertDatabaseMissing('resumes', ['uuid' => $uuid]);
    }

    public function test_all_ten_pdf_converter_apis(): void
    {
        // Build a clean 2-page PDF fixture for all FROM-PDF tools
        $pdfBytes = Pdf::loadHTML(
            '<html><body><h1>Quarterly Sales</h1><p>Revenue 125000</p><div style="page-break-after:always;"></div><h1>Page Two</h1><p>Growth 34%</p></body></html>'
        )->output();
        $pdfPath = tempnam(sys_get_temp_dir(), 'fx_src_') . '.pdf';
        file_put_contents($pdfPath, $pdfBytes);

        try {
            // 1. JPG to PDF
            $img1 = UploadedFile::fake()->image('p1.jpg', 400, 300);
            $img2 = UploadedFile::fake()->image('p2.png', 400, 300);
            $r1 = $this->post('/api/v1/convert/jpg-to-pdf', [
                'files' => [$img1, $img2],
                'page_size' => 'a4',
                'orientation' => 'auto',
                'margin' => 'small',
            ], ['Accept' => 'application/json']);
            $r1->assertOk();
            $this->assertStringStartsWith('%PDF-', $r1->getContent());

            // 2. Word to PDF
            $phpWord = new PhpWord();
            $phpWord->addSection()->addText('Word to PDF API Test');
            $docxPath = tempnam(sys_get_temp_dir(), 'fx_w_') . '.docx';
            WordIOFactory::createWriter($phpWord, 'Word2007')->save($docxPath);
            $r2 = $this->post('/api/v1/convert/word-to-pdf', [
                'file' => new UploadedFile($docxPath, 'doc.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', null, true),
            ], ['Accept' => 'application/json']);
            @unlink($docxPath);
            $r2->assertOk();
            $this->assertStringStartsWith('%PDF-', $r2->getContent());

            // 3. PowerPoint to PDF
            $pres = new PhpPresentation();
            $pres->getActiveSlide()->createRichTextShape()->createTextRun('Slide One');
            $pptxPath = tempnam(sys_get_temp_dir(), 'fx_p_') . '.pptx';
            PptIOFactory::createWriter($pres, 'PowerPoint2007')->save($pptxPath);
            $r3 = $this->post('/api/v1/convert/powerpoint-to-pdf', [
                'file' => new UploadedFile($pptxPath, 'deck.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', null, true),
            ], ['Accept' => 'application/json']);
            @unlink($pptxPath);
            $r3->assertOk();
            $this->assertStringStartsWith('%PDF-', $r3->getContent());

            // 4. Excel to PDF
            $sheet = new Spreadsheet();
            $sheet->getActiveSheet()->setCellValue('A1', 'Item')->setCellValue('B1', 'Amount');
            $sheet->getActiveSheet()->setCellValue('A2', 'Audit')->setCellValue('B2', 1200);
            $xlsxPath = tempnam(sys_get_temp_dir(), 'fx_x_') . '.xlsx';
            SheetIOFactory::createWriter($sheet, 'Xlsx')->save($xlsxPath);
            $r4 = $this->post('/api/v1/convert/excel-to-pdf', [
                'file' => new UploadedFile($xlsxPath, 'sheet.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true),
            ], ['Accept' => 'application/json']);
            @unlink($xlsxPath);
            $r4->assertOk();
            $this->assertStringStartsWith('%PDF-', $r4->getContent());

            // 5. HTML to PDF
            $r5 = $this->postJson('/api/v1/convert/html-to-pdf', [
                'html_code' => '<h1>HTML API</h1><p>Clean output</p>',
                'page_size' => 'a4',
                'orientation' => 'portrait',
            ]);
            $r5->assertOk();
            $this->assertStringStartsWith('%PDF-', $r5->getContent());

            // 6A. PDF to JPG (ZIP)
            $r6Zip = $this->post('/api/v1/convert/pdf-to-jpg', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'output' => 'zip',
                'scale' => 1.5,
            ], ['Accept' => 'application/json']);
            $r6Zip->assertOk();
            $this->assertStringContainsString('application/zip', (string) $r6Zip->headers->get('Content-Type'));
            $tmpZip = tempnam(sys_get_temp_dir(), 'fx_vzip_') . '.zip';
            file_put_contents($tmpZip, $r6Zip->getContent());
            $zip = new ZipArchive();
            $this->assertTrue($zip->open($tmpZip) === true);
            $this->assertSame(2, $zip->numFiles);
            $zip->close();
            @unlink($tmpZip);

            // 6B. PDF to JPG (Single JPG page 2)
            $r6Jpg = $this->post('/api/v1/convert/pdf-to-jpg', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'output' => 'jpg',
                'page' => 2,
                'scale' => 1.5,
            ], ['Accept' => 'application/json']);
            $r6Jpg->assertOk();
            $this->assertStringContainsString('image/jpeg', (string) $r6Jpg->headers->get('Content-Type'));
            $this->assertNotFalse(@getimagesizefromstring($r6Jpg->getContent()));

            // 6C. PDF to JPG (JSON response with base64 pages)
            $r6Json = $this->post('/api/v1/convert/pdf-to-jpg', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'output' => 'json',
                'scale' => 1.5,
            ], ['Accept' => 'application/json']);
            $r6Json->assertOk()
                ->assertJsonPath('success', true)
                ->assertJsonPath('num_pages', 2)
                ->assertJsonCount(2, 'pages');

            // 7. PDF to Word (editable + visual)
            $r7Edit = $this->post('/api/v1/convert/pdf-to-word', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'mode' => 'editable',
            ], ['Accept' => 'application/json']);
            $r7Edit->assertOk();
            $this->assertStringStartsWith('PK', $r7Edit->getContent());

            $r7Vis = $this->post('/api/v1/convert/pdf-to-word', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'mode' => 'visual',
            ], ['Accept' => 'application/json']);
            $r7Vis->assertOk();
            $this->assertStringStartsWith('PK', $r7Vis->getContent());

            // 8. PDF to PowerPoint (visual server-side!)
            $r8 = $this->post('/api/v1/convert/pdf-to-powerpoint', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'mode' => 'visual',
            ], ['Accept' => 'application/json']);
            $r8->assertOk();
            $this->assertStringStartsWith('PK', $r8->getContent());

            // 9. PDF to Excel
            $r9 = $this->post('/api/v1/convert/pdf-to-excel', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'merge_sheets' => 'per_page',
            ], ['Accept' => 'application/json']);
            $r9->assertOk();
            $this->assertStringStartsWith('PK', $r9->getContent());

            // 10. PDF to PDF/A
            $r10 = $this->post('/api/v1/convert/pdf-to-pdfa', [
                'file' => new UploadedFile($pdfPath, 'sample.pdf', 'application/pdf', null, true),
                'conformance' => '1b',
            ], ['Accept' => 'application/json']);
            $r10->assertOk();
            $this->assertStringStartsWith('%PDF-', $r10->getContent());
        } finally {
            @unlink($pdfPath);
        }
    }

    public function test_existing_website_ajax_and_session_flows_remain_compatible(): void
    {
        // Guest creates an invoice and resume via web session routes
        $webInv = $this->postJson('/api/invoices', [
            'invoice_number' => 'WEB-101',
            'items' => [['description' => 'Web Sprint', 'quantity' => 1, 'unit_price' => 250]],
        ])->assertOk()->assertJsonPath('success', true);
        $invUuid = $webInv->json('uuid');

        $this->getJson('/api/invoices/history')
            ->assertOk()
            ->assertJsonCount(1);

        $this->postJson('/api/resumes/import', [
            'cv_text' => "Sam Web\nsam@web.test\nSKILLS\nPHP, Laravel",
        ])->assertOk()->assertJsonPath('success', true);

        $webRes = $this->postJson('/api/resumes', [
            'title' => 'Sam Web CV',
            'content' => ['personal' => ['full_name' => 'Sam Web']],
        ])->assertOk()->assertJsonPath('success', true);
        $resUuid = $webRes->json('uuid');

        // Signing up via web claims guest session documents and redirects to /history
        $this->post('/signup', [
            'name' => 'Sam Web',
            'email' => 'sam@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])->assertRedirect(route('invoice.history.page'));

        $this->get('/history')->assertOk();
        $this->assertDatabaseHas('invoices', ['uuid' => $invUuid, 'user_id' => auth()->id()]);
        $this->assertDatabaseHas('resumes', ['uuid' => $resUuid, 'user_id' => auth()->id()]);

        // Web authenticated status update & delete
        $this->patch("/invoice/{$invUuid}/status", ['status' => 'paid'])->assertRedirect();
        $this->assertDatabaseHas('invoices', ['uuid' => $invUuid, 'status' => 'paid']);

        $this->delete("/invoice/{$invUuid}")->assertRedirect();
        $this->delete("/resume/{$resUuid}")->assertRedirect();

        // Web converter route
        $conv = $this->postJson('/api/convert/html-to-pdf', [
            'html_code' => '<h1>Web Convert</h1>',
        ]);
        $conv->assertOk();
        $this->assertStringStartsWith('%PDF-', $conv->getContent());
    }
}
