<?php

namespace Tests\Feature;

use App\Mail\OtpVerificationMail;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_otp_verification_and_resend_cooldown_flow(): void
    {
        Mail::fake();

        $this->post('/signup', [
            'name' => 'Ahsan Studio',
            'email' => 'ahsan@example.com',
            'password' => 'StrongPass1!',
            'password_confirmation' => 'StrongPass1!',
        ])->assertRedirect(route('verification.notice'));

        $this->assertGuest();

        $user = User::where('email', 'ahsan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);

        $firstCode = null;
        Mail::assertSent(OtpVerificationMail::class, function (OtpVerificationMail $mail) use (&$firstCode) {
            if ($mail->hasTo('ahsan@example.com')) {
                $firstCode = $mail->code;

                return true;
            }

            return false;
        });
        $this->assertMatchesRegularExpression('/^\d{6}$/', (string) $firstCode);

        // Resend inside 60s cooldown is blocked with 429 JSON response
        $this->postJson('/verify-otp/resend', ['email' => 'ahsan@example.com'])
            ->assertStatus(429)
            ->assertJsonPath('success', false);

        // Wrong OTP fails with remaining attempts feedback
        $wrongCode = $firstCode === '000000' ? '111111' : '000000';
        $this->post('/verify-otp', [
            'email' => 'ahsan@example.com',
            'code' => $wrongCode,
        ])->assertSessionHasErrors('code');

        // Advance time past 60s cooldown and resend a new OTP
        $this->travel(61)->seconds();

        Mail::fake();
        $this->postJson('/verify-otp/resend', ['email' => 'ahsan@example.com'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('retry_after', 60);

        $resentCode = null;
        Mail::assertSent(OtpVerificationMail::class, function (OtpVerificationMail $mail) use (&$resentCode) {
            if ($mail->hasTo('ahsan@example.com')) {
                $resentCode = $mail->code;

                return true;
            }

            return false;
        });
        $this->assertMatchesRegularExpression('/^\d{6}$/', (string) $resentCode);

        // Old OTP was invalidated when resend generated a fresh code
        if ($firstCode !== $resentCode) {
            $this->post('/verify-otp', [
                'email' => 'ahsan@example.com',
                'code' => $firstCode,
            ])->assertSessionHasErrors('code');
        }

        // Resent OTP verifies email, sets verified timestamp, and logs the user in
        $this->post('/verify-otp', [
            'email' => 'ahsan@example.com',
            'code' => $resentCode,
        ])->assertRedirect(route('invoice.history.page'))
            ->assertSessionHas('signup_completed', true);

        $this->assertAuthenticatedAs($user->fresh());
        $this->assertNotNull($user->fresh()->email_verified_at);

        // Verified email cannot be registered again
        $this->post('/logout');
        $this->post('/signup', [
            'name' => 'Duplicate',
            'email' => 'ahsan@example.com',
            'password' => 'StrongPass1!',
            'password_confirmation' => 'StrongPass1!',
        ])->assertSessionHasErrors('email');
    }

    public function test_unverified_login_redirects_to_otp_verification(): void
    {
        Mail::fake();

        $user = User::factory()->unverified()->create([
            'email' => 'pending@example.com',
            'password' => 'SecretPass1!',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'SecretPass1!',
        ])->assertRedirect(route('verification.notice'));

        $this->assertGuest();

        $code = null;
        Mail::assertSent(OtpVerificationMail::class, function (OtpVerificationMail $mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('pending@example.com');
        });

        $this->post('/verify-otp', [
            'email' => $user->email,
            'code' => $code,
        ])->assertRedirect(route('invoice.history.page'));

        $this->assertAuthenticatedAs($user->fresh());
    }

    public function test_forgot_and_reset_password_otp_flow(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'resetme@example.com',
            'password' => 'OldPassword1!',
        ]);

        $this->get('/forgot-password')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

        // Unknown email shows explicit validation error on forgot-password screen
        $this->from('/forgot-password')
            ->post('/forgot-password', ['email' => 'nobody@example.com'])
            ->assertRedirect('/forgot-password')
            ->assertSessionHasErrors('email');
        Mail::assertNothingSent();

        // Real user receives reset OTP
        $this->post('/forgot-password', ['email' => 'resetme@example.com'])
            ->assertRedirect(route('password.reset.form'));

        $resetCode = null;
        Mail::assertSent(PasswordResetOtpMail::class, function (PasswordResetOtpMail $mail) use (&$resetCode) {
            if ($mail->hasTo('resetme@example.com')) {
                $resetCode = $mail->code;

                return true;
            }

            return false;
        });
        $this->assertMatchesRegularExpression('/^\d{6}$/', (string) $resetCode);

        // Cooldown enforced on resend
        $this->postJson('/reset-password/resend', ['email' => 'resetme@example.com'])
            ->assertStatus(429);

        // Reset password with valid OTP updates password and allows login
        $this->post('/reset-password', [
            'email' => 'resetme@example.com',
            'code' => $resetCode,
            'password' => 'NewPassword2@',
            'password_confirmation' => 'NewPassword2@',
        ])->assertRedirect(route('login'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword2@', $user->fresh()->password));

        // Reusing the same OTP fails
        $this->post('/reset-password', [
            'email' => 'resetme@example.com',
            'code' => $resetCode,
            'password' => 'AnotherPass3#',
            'password_confirmation' => 'AnotherPass3#',
        ])->assertSessionHasErrors('code');

        // Old password fails; new password signs in cleanly
        $this->post('/login', [
            'email' => 'resetme@example.com',
            'password' => 'OldPassword1!',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => 'resetme@example.com',
            'password' => 'NewPassword2@',
        ])->assertRedirect(route('invoice.history.page'));
    }

    public function test_authenticated_change_password_flow_and_noindex_headers(): void
    {
        $this->get('/verify-otp')->assertRedirect(route('register'));
        $this->get('/reset-password')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $this->get('/account/password')->assertRedirect(route('login'));

        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'CurrentPass1!',
        ]);

        $this->actingAs($user);

        $this->get('/account/password')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('Security &amp; Password', false);

        // Wrong current password rejected
        $this->put('/account/password', [
            'current_password' => 'WrongPass1!',
            'password' => 'UpdatedPass2!',
            'password_confirmation' => 'UpdatedPass2!',
        ])->assertSessionHasErrors('current_password');

        // Same password rejected
        $this->put('/account/password', [
            'current_password' => 'CurrentPass1!',
            'password' => 'CurrentPass1!',
            'password_confirmation' => 'CurrentPass1!',
        ])->assertSessionHasErrors('password');

        // Valid update changes stored password hash
        $this->put('/account/password', [
            'current_password' => 'CurrentPass1!',
            'password' => 'UpdatedPass2!',
            'password_confirmation' => 'UpdatedPass2!',
        ])->assertRedirect();

        $this->assertTrue(Hash::check('UpdatedPass2!', $user->fresh()->password));
    }
}
