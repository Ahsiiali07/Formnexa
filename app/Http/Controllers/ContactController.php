<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the public Contact Us page.
     */
    public function show(): View
    {
        $seo = [
            'title' => 'Contact Us | Formnexa - Free Document & Resume Platform',
            'description' => 'Get in touch with the Formnexa team. Share feedback or ask questions about our free invoice maker, ATS resume builder, and PDF converter tools.',
            'canonical' => url('/contact'),
        ];

        return view('pages.contact', compact('seo'));
    }

    /**
     * Store submitted contact message (No email sending required).
     */
    public function store(Request $request): RedirectResponse
    {
        // Anti-spam honeypot
        if ($request->filled('website_hp')) {
            return back()->with('success', 'Thank you! Your message has been received.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'is_read' => false,
        ]);

        return back()->with('success', 'Thank you! Your message has been sent successfully. Our team will review it shortly.');
    }
}
