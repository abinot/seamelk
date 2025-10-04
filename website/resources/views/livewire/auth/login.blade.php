<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Modules\Auth\Models\AuthUserMeta;

new #[Layout('components.layouts.auth')] class extends Component
{
    public string $identifier = ''; // ایمیل یا شماره تلفن یا کدملی
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'identifier' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        $identifier = trim($this->identifier);
        $email = null;

        // تشخیص نوع شناسه
        if (str_contains($identifier, '@')) {
            $email = $identifier;
        } elseif (preg_match('/^09\d{9}$/', $identifier)) {
            $userId = AuthUserMeta::findUserIdByKeyValue('phone', $identifier);
            if ($userId) $email = User::find($userId)?->email;
        } elseif (preg_match('/^\d{10}$/', $identifier)) {
            $email = $identifier . '@seamelk.ir';
        } else {
            $this->addError('identifier', 'فرمت شناسه پذیرفته‌شده نیست.');
            return;
        }

        if (! $email) {
            $this->addError('identifier', 'کاربری با این شناسه یافت نشد.');
            return;
        }

        // Rate limiter
        $this->ensureIsNotRateLimited($identifier);

        if (Auth::attempt(['email' => $email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($this->throttleKey($identifier));
        $this->addError('password', 'شناسه یا رمز عبور اشتباه است.');
    }

    protected function ensureIsNotRateLimited(string $identifier)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($identifier), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($identifier));
            throw ValidationException::withMessages([
                'identifier' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => ceil($seconds/60)]),
            ]);
        }
    }

    protected function throttleKey(string $identifier): string
    {
        return Str::lower($identifier).'|'.request()->ip();
    }
}; ?>
<div class="flex flex-col gap-6">
    <x-auth-header title="ورود به حساب کاربری" description="ایمیل، شماره تلفن یا کدملی و رمز عبور خود را وارد کنید"/>

    <x-auth-session-status :status="session('status')" class="text-center" />

    <form wire:submit="login" class="flex flex-col gap-6">

        <!-- Identifier -->
        <flux:input
            wire:model="identifier"
            label="ایمیل یا شماره تلفن یا کدملی"
            type="text"
            required
            autofocus
            placeholder="ایمیل، شماره تلفن یا کدملی"
        />
        @error('identifier') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

        <!-- Password -->
        <flux:input
            wire:model="password"
            label="رمز عبور"
            type="password"
            required
            autocomplete="current-password"
            placeholder="رمز عبور"
            viewable
        />
        @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" label="مرا به خاطر بسپار"/>

        <flux:button type="submit" variant="primary" class="w-full">ورود</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>حساب کاربری ندارید؟</span>
        <flux:link :href="route('register')" wire:navigate>ثبت‌نام</flux:link>
    </div>
</div>
