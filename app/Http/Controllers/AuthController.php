<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\InvoiceService;
use App\Services\ResumeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show Login Page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('invoice.history.page');
        }

        return view('auth.login');
    }

    /**
     * Authenticate an existing user.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->claimGuestSessionInvoices(Auth::id(), $request);

            return redirect()
                ->intended(route('invoice.history.page'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email');
    }

    /**
     * Show Registration / Sign Up Page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('invoice.history.page');
        }

        return view('auth.register');
    }

    /**
     * Create a new user account and sign in.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->claimGuestSessionInvoices($user->id, $request);

        return redirect()
            ->intended(route('invoice.history.page'))
            ->with('success', 'Account created! Your document history is now synced to your account.');
    }

    /**
     * Sign out the user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    /**
     * Attach any unassigned invoices or resumes created in this guest session to the authenticated user.
     */
    protected function claimGuestSessionInvoices(int $userId, Request $request): void
    {
        $guestUuids = $request->session()->pull('guest_invoice_uuids', []);
        if (!empty($guestUuids) && is_array($guestUuids)) {
            app(InvoiceService::class)->claimGuestInvoices($userId, $guestUuids);
        }

        $guestResumeUuids = $request->session()->pull('guest_resume_uuids', []);
        if (!empty($guestResumeUuids) && is_array($guestResumeUuids)) {
            app(ResumeService::class)->claimGuestResumes($userId, $guestResumeUuids);
        }
    }
}
