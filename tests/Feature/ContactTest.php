<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_loads_with_proper_seo_and_schema(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200)
            ->assertSee('Contact Us | Formnexa', false)
            ->assertSee('"@type": "ContactPage"', false)
            ->assertSee('Get in touch with the Formnexa team', false)
            ->assertSee('name="name"', false)
            ->assertSee('name="subject"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="message"', false)
            ->assertDontSee('<b></b>', false)
            ->assertDontSeeText('Pakistan');

        // Heading hierarchy checks
        $this->assertSame(1, preg_match_all('/<h1\b/i', $response->getContent()));
        $this->assertSame(0, preg_match_all('/<h4\b/i', $response->getContent()));
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->post('/contact', []);

        $response->assertSessionHasErrors(['name', 'subject', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_honeypot_silently_discards_bots(): void
    {
        $response = $this->post('/contact', [
            'website_hp' => 'http://spam-bot.site',
            'name' => 'Spam Bot',
            'subject' => 'Free money',
            'message' => 'Spam message that should be ignored by the honeypot.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_stores_inquiry_without_email(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Alex Vance',
            'subject' => 'Resume Builder & ATS Question',
            'message' => 'Does Formnexa support downloading resumes directly to ATS-friendly docx or pdf?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Alex Vance',
            'email' => null,
            'subject' => 'Resume Builder & ATS Question',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_stores_inquiry_with_optional_email(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Elena Rostova',
            'email' => 'elena@example.com',
            'subject' => 'Invoice Maker & Billing',
            'message' => 'Is it possible to add custom VAT IDs for European Union customers?',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Elena Rostova',
            'email' => 'elena@example.com',
            'is_read' => false,
        ]);
    }

    public function test_admin_can_view_contact_messages(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@formnexa.test',
            'is_admin' => true,
        ]);

        $inquiry = ContactMessage::create([
            'name' => 'Marcus Aurelius',
            'email' => 'marcus@stoic.org',
            'subject' => 'Feature Request & Feedback',
            'message' => 'Please add an Italian invoice format template option.',
            'is_read' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin?tab=contacts');

        $response->assertStatus(200)
            ->assertSee('Contact Inquiries')
            ->assertSee('Marcus Aurelius')
            ->assertSee('Feature Request &amp; Feedback', false)
            ->assertSee('Italian invoice format template');
    }

    public function test_admin_can_toggle_contact_read_status(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@formnexa.test',
            'is_admin' => true,
        ]);

        $inquiry = ContactMessage::create([
            'name' => 'Test User',
            'subject' => 'General Inquiry',
            'message' => 'Testing read status toggle.',
            'is_read' => false,
        ]);

        $this->assertFalse($inquiry->is_read);

        $response = $this->actingAs($admin)->patch("/admin/contacts/{$inquiry->id}/toggle-read");

        $response->assertRedirect('/admin?tab=contacts');
        $this->assertTrue($inquiry->fresh()->is_read);

        // Toggle back to unread
        $response2 = $this->actingAs($admin)->patch("/admin/contacts/{$inquiry->id}/toggle-read");
        $response2->assertRedirect('/admin?tab=contacts');
        $this->assertFalse($inquiry->fresh()->is_read);
    }

    public function test_admin_can_delete_contact_message(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@formnexa.test',
            'is_admin' => true,
        ]);

        $inquiry = ContactMessage::create([
            'name' => 'To Be Deleted',
            'subject' => 'Bug Report',
            'message' => 'A temporary message that should be deleted.',
            'is_read' => true,
        ]);

        $this->assertDatabaseHas('contact_messages', ['id' => $inquiry->id]);

        $response = $this->actingAs($admin)->delete("/admin/contacts/{$inquiry->id}");

        $response->assertRedirect('/admin?tab=contacts');
        $this->assertDatabaseMissing('contact_messages', ['id' => $inquiry->id]);
    }
}
