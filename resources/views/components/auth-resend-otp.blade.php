@props([
    'action',
    'email' => '',
    'cooldownSeconds' => 0,
    'buttonLabel' => 'Resend Verification Code',
])

<div x-data="{
        seconds: {{ (int) $cooldownSeconds }},
        loading: false,
        feedback: '',
        feedbackOk: true,
        timer: null,
        init() {
            if (this.seconds > 0) {
                this.startTimer();
            }
        },
        startTimer() {
            if (this.timer) clearInterval(this.timer);
            this.timer = setInterval(() => {
                if (this.seconds > 0) {
                    this.seconds--;
                } else {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            }, 1000);
        },
        get formattedTime() {
            const mins = String(Math.floor(this.seconds / 60)).padStart(2, '0');
            const secs = String(this.seconds % 60).padStart(2, '0');
            return mins + ':' + secs;
        },
        async resend() {
            if (this.seconds > 0 || this.loading) return;
            this.loading = true;
            this.feedback = '';
            try {
                const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';
                const payloadEmail = document.querySelector('#reset_email')?.value || @js($email);
                const res = await fetch(@js($action), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ email: payloadEmail }),
                });
                const data = await res.json();
                this.feedback = data.message || (res.ok ? 'Code sent!' : 'Unable to resend right now.');
                this.feedbackOk = Boolean(data.success);
                if (typeof data.retry_after === 'number' && data.retry_after > 0) {
                    this.seconds = data.retry_after;
                    this.startTimer();
                } else if (res.ok) {
                    this.seconds = 60;
                    this.startTimer();
                }
            } catch (err) {
                this.feedback = 'Network error while resending code. Please try again.';
                this.feedbackOk = false;
            } finally {
                this.loading = false;
            }
        }
    }" class="mt-4 pt-4 border-t border-[#dfe4df]">
    <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
        <span class="text-[#536058]">
            Didn't receive the email?
        </span>

        <form method="POST" action="{{ $action }}" @submit.prevent="resend()">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit"
                    :disabled="seconds > 0 || loading"
                    class="inline-flex items-center gap-1.5 font-bold transition cursor-pointer disabled:cursor-not-allowed"
                    :class="seconds > 0 || loading ? 'text-[#68736d]' : 'text-[#184f3a] hover:underline'">
                <span x-show="loading" x-cloak>Sending...</span>
                <span x-show="!loading && seconds > 0" x-cloak>
                    Resend available in <span class="font-mono" x-text="formattedTime"></span>
                </span>
                <span x-show="!loading && seconds === 0">
                    {{ $buttonLabel }}
                </span>
            </button>
        </form>
    </div>

    <div x-show="feedback" x-cloak
         class="mt-2.5 p-2.5 rounded-lg text-xs font-medium"
         :class="feedbackOk ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-rose-50 border border-rose-200 text-rose-700'"
         x-text="feedback"></div>
</div>
