@props([
    'id' => 'password',
    'name' => 'password',
    'label' => 'Password',
    'placeholder' => 'Enter password',
    'required' => true,
    'autocomplete' => 'current-password',
    'showStrength' => false,
    'hint' => null,
    'autofocus' => false,
])

<div x-data="{
        show: false,
        val: '',
        get score() {
            let s = 0;
            if (this.val.length >= 8) s++;
            if (/[A-Z]/.test(this.val)) s++;
            if (/[0-9]/.test(this.val)) s++;
            if (/[^A-Za-z0-9]/.test(this.val)) s++;
            return s;
        },
        get label() {
            if (!this.val) return '';
            if (this.score <= 1) return 'Weak';
            if (this.score === 2) return 'Fair';
            if (this.score === 3) return 'Good';
            return 'Strong';
        },
        get barColor() {
            if (this.score <= 1) return 'bg-rose-500';
            if (this.score === 2) return 'bg-amber-500';
            if (this.score === 3) return 'bg-teal-600';
            return 'bg-[#184f3a]';
        }
    }">
    <div class="flex items-center justify-between mb-1.5">
        <label for="{{ $id }}" class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-[#536058]">
            {{ $label }}
        </label>
        @if($hint)
            <span class="text-[11px] text-[#68736d]">{{ $hint }}</span>
        @elseif($showStrength)
            <span x-show="val.length > 0" x-cloak class="text-[11px] font-bold"
                  :class="score <= 1 ? 'text-rose-600' : (score === 2 ? 'text-amber-600' : 'text-[#184f3a]')"
                  x-text="label"></span>
        @endif
    </div>

    <div class="relative">
        <input id="{{ $id }}"
               :type="show ? 'text' : 'password'"
               name="{{ $name }}"
               x-model="val"
               @if($required) required @endif
               @if($autofocus) autofocus @endif
               autocomplete="{{ $autocomplete }}"
               placeholder="{{ $placeholder }}"
               class="w-full pl-3.5 pr-11 py-2.5 rounded-xl bg-white border border-[#dfe4df] text-sm text-[#17211b] placeholder:text-[#68736d]/70 focus:border-[#184f3a] focus:ring-2 focus:ring-[#184f3a]/15 focus:outline-none transition">
        <button type="button"
                @click="show = !show"
                :aria-label="show ? 'Hide password' : 'Show password'"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-[#68736d] hover:text-[#17211b] transition cursor-pointer">
            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
            </svg>
        </button>
    </div>

    @if($showStrength)
        <div class="mt-2.5 space-y-2" x-show="val.length > 0" x-cloak>
            <div class="grid grid-cols-4 gap-1.5">
                <template x-for="i in 4" :key="i">
                    <div class="h-1.5 rounded-full transition-colors duration-200"
                         :class="score >= i ? barColor : 'bg-[#e6ebe7]'"></div>
                </template>
            </div>
            <div class="flex flex-wrap gap-1.5 text-[11px]">
                <span class="px-2 py-0.5 rounded-md font-medium"
                      :class="val.length >= 8 ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-[#f8f8f3] text-[#68736d]'">
                    8+ chars
                </span>
                <span class="px-2 py-0.5 rounded-md font-medium"
                      :class="/[A-Z]/.test(val) ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-[#f8f8f3] text-[#68736d]'">
                    Uppercase
                </span>
                <span class="px-2 py-0.5 rounded-md font-medium"
                      :class="/[0-9]/.test(val) ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-[#f8f8f3] text-[#68736d]'">
                    Number
                </span>
                <span class="px-2 py-0.5 rounded-md font-medium"
                      :class="/[^A-Za-z0-9]/.test(val) ? 'bg-[#e8f7ed] text-[#184f3a]' : 'bg-[#f8f8f3] text-[#68736d]'">
                    Symbol
                </span>
            </div>
        </div>
    @endif
</div>
