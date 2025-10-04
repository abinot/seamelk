<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Modules\Auth\Models\AuthUserMeta;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $phone = '';
    public string $national_id = '';

    public function register(): void
{
    $validated = $this->validate([
        'phone' => ['required', 'regex:/^09\d{9}$/'],
        'national_id' => ['required', 'digits:10'],
    ]);

    // بررسی وجود کد ملی در دیتابیس
    $existingNational = User::where('email', $validated['national_id'] . '@seamelk.ir')->exists();
    if ($existingNational) {
        $this->addError('national_id', 'کد ملی وارد شده قبلاً ثبت شده است.');
        return;
    }

    // بررسی اینکه شماره تلفن قبلاً در meta ثبت نشده باشد
    $existingPhone = AuthUserMeta::where('key', 'phone')->where('value', $validated['phone'])->exists();
    if ($existingPhone) {
        $this->addError('phone', 'این شماره تلفن قبلاً ثبت شده است.');
        return;
    }

    // ساخت رمز تصادفی 8 کاراکتری
    $randomPassword = Str::random(10);

    // ساخت کاربر
    $user = User::create([
        'name' => $validated['national_id'],
        'email' => $validated['national_id'] . '@seamelk.ir',
        'password' => Hash::make($randomPassword),
    ]);

    // ایمیل داخلی بر اساس user_id در meta
    AuthUserMeta::create([
        'user_id' => $user->id,
        'key' => 'internal_email',
        'value' => $user->id . '@seamelk.ir',
    ]);

    // ذخیره شماره تلفن و کد ملی در meta
    AuthUserMeta::insert([
        [
            'user_id' => $user->id,
            'key' => 'phone',
            'value' => $validated['phone'],
        ],
        [
            'user_id' => $user->id,
            'key' => 'national_id',
            'value' => $validated['national_id'],
        ],
    ]);

    // ارسال رمز با SMS
    // SmsService::send($validated['phone'], "رمز ورود شما: $randomPassword");

    event(new Registered($user));

    Auth::login($user);
    Session::regenerate();
    dd($randomPassword);
    $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
}

};
?>


<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <flux:input
            wire:model="phone"
            label="شماره تلفن"
            type="tel"
            required
            placeholder="09XX XXX XXXX"
        />

        <flux:input
            wire:model="national_id"
            label="کد ملی"
            type="text"
            required
            placeholder="کد ملی ۱۰ رقمی"
        />

        <flux:button type="submit" variant="primary" class="w-full">
            ثبت‌نام
        </flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
