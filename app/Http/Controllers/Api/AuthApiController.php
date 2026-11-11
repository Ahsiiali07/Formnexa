<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\Resume;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    /**
     * Create a new user account, claim optional guest documents, and issue a Bearer token.
     */
    public function register(
        Request $request,
        InvoiceService $invoiceService,
        ResumeService $resumeService
    ): JsonResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'device_name' => ['nullable', 'string', 'max:100'],
            'guest_invoice_uuids' => ['nullable', 'array'],
            'guest_invoice_uuids.*' => ['string'],
            'guest_resume_uuids' => ['nullable', 'array'],
            'guest_resume_uuids.*' => ['string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $claimedInvoices = $invoiceService->claimGuestInvoices($user->id, $validated['guest_invoice_uuids'] ?? []);
        $claimedResumes = $resumeService->claimGuestResumes($user->id, $validated['guest_resume_uuids'] ?? []);

        $issued = ApiToken::issueFor($user, $validated['device_name'] ?? 'mobile');

        return response()->json([
            'success' => true,
            'message' => 'Account created! Your document history is now synced to your account.',
            'token' => $issued['plainTextToken'],
            'token_type' => 'Bearer',
            'user' => $this->formatUser($user),
            'claimed' => [
                'invoices' => $claimedInvoices,
                'resumes' => $claimedResumes,
            ],
        ], 201);
    }

    /**
     * Authenticate an existing user, claim optional guest documents, and issue a Bearer token.
     */
    public function login(
        Request $request,
        InvoiceService $invoiceService,
        ResumeService $resumeService
    ): JsonResponse {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
            'guest_invoice_uuids' => ['nullable', 'array'],
            'guest_invoice_uuids.*' => ['string'],
            'guest_resume_uuids' => ['nullable', 'array'],
            'guest_resume_uuids.*' => ['string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials do not match our records.',
            ], 401);
        }

        $claimedInvoices = $invoiceService->claimGuestInvoices($user->id, $validated['guest_invoice_uuids'] ?? []);
        $claimedResumes = $resumeService->claimGuestResumes($user->id, $validated['guest_resume_uuids'] ?? []);

        $issued = ApiToken::issueFor($user, $validated['device_name'] ?? 'mobile');

        return response()->json([
            'success' => true,
            'message' => 'Welcome back, ' . $user->name . '!',
            'token' => $issued['plainTextToken'],
            'token_type' => 'Bearer',
            'user' => $this->formatUser($user),
            'claimed' => [
                'invoices' => $claimedInvoices,
                'resumes' => $claimedResumes,
            ],
        ]);
    }

    /**
     * Get the currently authenticated user profile and document summary stats.
     */
    public function me(Request $request, InvoiceService $invoiceService): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $stats = $invoiceService->getUserStats($user->id);
        $stats['resume_count'] = Resume::where('user_id', $user->id)->count();

        return response()->json([
            'success' => true,
            'user' => $this->formatUser($user),
            'stats' => $stats,
        ]);
    }

    /**
     * Claim locally stored guest invoices and resumes for the signed-in user.
     */
    public function claim(
        Request $request,
        InvoiceService $invoiceService,
        ResumeService $resumeService
    ): JsonResponse {
        $validated = $request->validate([
            'guest_invoice_uuids' => ['nullable', 'array'],
            'guest_invoice_uuids.*' => ['string'],
            'guest_resume_uuids' => ['nullable', 'array'],
            'guest_resume_uuids.*' => ['string'],
        ]);

        /** @var User $user */
        $user = $request->user();
        $claimedInvoices = $invoiceService->claimGuestInvoices($user->id, $validated['guest_invoice_uuids'] ?? []);
        $claimedResumes = $resumeService->claimGuestResumes($user->id, $validated['guest_resume_uuids'] ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Guest documents synced to your account.',
            'claimed' => [
                'invoices' => $claimedInvoices,
                'resumes' => $claimedResumes,
            ],
        ]);
    }

    /**
     * Revoke the current Bearer token (or all tokens if all_devices=true).
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($request->boolean('all_devices')) {
            $user->apiTokens()->delete();
        } else {
            $token = $request->attributes->get('api_token');
            if ($token instanceof ApiToken) {
                $token->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'You have been signed out.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function formatUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at?->toIso8601String(),
        ];
    }
}
