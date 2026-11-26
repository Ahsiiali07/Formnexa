@props([
    'name' => 'code',
    'label' => '6-Digit Verification Code',
    'initialValue' => '',
])

<div x-data="{
        digits: ['', '', '', '', '', ''],
        init() {
            const seed = @js((string) $initialValue).replace(/\D/g, '').slice(0, 6);
            if (seed.length) {
                for (let i = 0; i < 6; i++) {
                    this.digits[i] = seed[i] || '';
                }
            }
            this.$nextTick(() => {
                if (!seed.length) {
                    this.focusBox(0);
                }
            });
        },
        get code() {
            return this.digits.join('');
        },
        focusBox(idx) {
            const el = this.$el.querySelector('[data-otp-idx=\'' + idx + '\']');
            if (el) el.focus();
        },
        handleInput(idx, e) {
            const raw = (e.target.value || '').replace(/\D/g, '');
            if (raw.length > 1) {
                this.applyPaste(raw);
                return;
            }
            this.digits[idx] = raw;
            e.target.value = raw;
            if (raw && idx < 5) {
                this.focusBox(idx + 1);
            }
        },
        handleKeydown(idx, e) {
            if (e.key === 'Backspace' && !this.digits[idx] && idx > 0) {
                this.digits[idx - 1] = '';
                this.focusBox(idx - 1);
            } else if (e.key === 'ArrowLeft' && idx > 0) {
                this.focusBox(idx - 1);
            } else if (e.key === 'ArrowRight' && idx < 5) {
                this.focusBox(idx + 1);
            }
        },
        handlePaste(e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text') || '';
            this.applyPaste(text.replace(/\D/g, ''));
        },
        applyPaste(clean) {
            const chars = clean.slice(0, 6).split('');
            if (!chars.length) return;
            for (let i = 0; i < 6; i++) {
                this.digits[i] = chars[i] || '';
            }
            this.focusBox(Math.min(chars.length, 5));
        }
    }">
    <div class="flex items-center justify-between mb-2">
        <span class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058]">
            {{ $label }}
        </span>
        <span class="text-[11px] text-[#68736d]">Paste or type 6 digits</span>
    </div>

    <input type="hidden" name="{{ $name }}" :value="code">

    <div class="grid grid-cols-6 gap-2 sm:gap-3"
         style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 0.625rem;"
         @paste="handlePaste($event)">
        @for($idx = 0; $idx < 6; $idx++)
            <input data-otp-idx="{{ $idx }}"
                   type="text"
                   inputmode="numeric"
                   maxlength="6"
                   autocomplete="{{ $idx === 0 ? 'one-time-code' : 'off' }}"
                   aria-label="Digit {{ $idx + 1 }}"
                   x-model="digits[{{ $idx }}]"
                   @input="handleInput({{ $idx }}, $event)"
                   @keydown="handleKeydown({{ $idx }}, $event)"
                   @focus="$event.target.select()"
                   class="h-12 sm:h-14 w-full min-w-0 text-center font-mono text-xl sm:text-2xl font-extrabold rounded-xl border border-[#dfe4df] bg-[#f8f8f3]/70 text-[#17211b] focus:bg-white focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/20 focus:outline-none transition">
        @endfor
    </div>
</div>
