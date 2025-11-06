<?php
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Modules\Auth\Models\AuthUserMeta;
use Modules\Auth\Models\OTP;

new #[Layout("components.layouts.auth")] class extends Component {
    public string $identifier = ""; // ایمیل یا موبایل یا کدملی
    public string $password = "";
    public string $otp = "";
    public bool $otpSent = false;
    public bool $useOtp = false; // تعیین می‌کنه ورود با OTP باشه یا رمز ثابت

    // مرحله ۱: ارسال OTP
    public function sendOtp(): void
    {
        $this->validate([
            "identifier" => ["required"],
        ]);

        $phone = $this->normalizePhone($this->identifier);

        if (! $phone) {
            $this->addError("identifier", "شناسه معتبر نیست.");
            return;
        }

        // بررسی وجود کاربر
        $userId = AuthUserMeta::findUserIdByKeyValue("phone", $phone);
        $exists = $userId !== null;
        $record = OTP::where("phone", $phone)
        ->where("expires_at", ">", now())
        ->first();

        if (! $record) {
        $otp = (string) rand(100000, 999999);

        OTP::updateOrCreate(
            ["phone" => $phone],
            ["otp" => $otp, "expires_at" => now()->addMinutes(5)]
        );
        $this->otpSent = true;
        $this->useOtp = true;

        session()->flash("status", "کد تایید شما: $otp");
        }else{
            $this->otpSent = false;
            $this->useOtp = true;
            session()->flash("status","کد به تازگی فرستاده شده است.");
        }
        // تولید OTP




        // اگر کاربر وجود نداشت → در مرحله verifyOtp ثبت‌نام می‌کنیم
    }

    // مرحله ۲: بررسی OTP
    public function verifyOtp(): void
    {
        $phone = $this->normalizePhone($this->identifier);

        $record = OTP::where("phone", $phone)
        ->where("otp", (string) $this->otp)
        ->where("expires_at", ">", now())
        ->first();

        if (! $record) {
            $this->addError("otp", "کد وارد شده صحیح یا منقضی شده است.");
            return;
        }

        // پیدا کردن یا ساخت کاربر
        $userId = AuthUserMeta::findUserIdByKeyValue("phone", $phone);
        if ($userId) {
            $user = User::find($userId);
        } else {
            $randomPassword = Str::random(17);
            $user = User::create([
                "name" => $phone,
                "email" => "user{$phone}@seamelk.ir",
                "password" => Hash::make($randomPassword),
            ]);
            $user->update(["email" => $user->id . "@seamelk.ir"]);

            AuthUserMeta::create([
                "user_id" => $user->id,
                "key" => "phone",
                "value" => $phone,
            ]);

            // بررسی مطابقت شماره تلفن با ADMIN_PHONE و اختصاص نقش مدیر
            $adminPhone = env('ADMIN_PHONE');
            if ($adminPhone && $phone === $adminPhone) {
                $user->assignRole('admin'); // فرض بر این است که نقش 'admin' قبلاً ایجاد شده است
            }else{
                if($adminPhone){
                    $user->assignRole('user');
                }else{
                    echo("the ADMIN_PHONE not set on env. if you dont set ADMIN_PHONE the system can't found the Admin of the Seamelk");
                }
            }
        }

        $record->delete();

        Auth::login($user);
        session()->regenerate();

        $this->redirectIntended(route("dashboard", absolute: false), navigate: true);
    }


    // ورود با رمز ثابت
    public function loginWithPassword(): void
    {
        $this->validate([
            "identifier" => "required|string",
            "password" => "required|string",
        ]);

        $email = $this->resolveEmail($this->identifier);

        if ($email && Auth::attempt(["email" => $email, "password" => $this->password], true)) {
            session()->regenerate();
            $this->redirectIntended(route("dashboard", absolute: false), navigate: true);
        } else {
            $this->addError("password", "شناسه یا رمز عبور اشتباه است.");
        }
    }

    private function normalizePhone(string $input): ?string
    {
        return preg_match('/^09\d{9}$/', $input) ? $input : null;
    }

    private function resolveEmail(string $identifier): ?string
    {
        if (str_contains($identifier, "@")) return $identifier;
        if (preg_match('/^09\d{9}$/', $identifier)) {
            $userId = AuthUserMeta::findUserIdByKeyValue("phone", $identifier);
            return $userId ? User::find($userId)?->email : null;
        }
        if (preg_match('/^\d{10}$/', $identifier)) return $identifier . "@seamelk.ir";
        return null;
    }
};
?>
<div>
    <x-auth-header title="ورود / ثبت‌نام" description="با رمز ثابت یا رمز پویا وارد شوید" />

    <x-auth-session-status :status="session('status')" class="text-center" />

    @if(!$useOtp)
        {{-- حالت ورود با رمز ثابت --}}
        <form wire:submit="loginWithPassword" class="flex flex-col gap-6">
            <flux:input wire:model="identifier" label="شماره تلفن" required />

            <div class="flex gap-2 items-end">
                <flux:input
                    wire:model="password"
                    label=" رمز  عبور ثابت"
                    type="password"
                    required
                    class="flex-1"
                />

                <flux:button
                    type="button"
                    wire:click="sendOtp"
                    variant="primary"
                    class="px-4 py-2"
                >
                    ورود با رمز پویا
                </flux:button>
            </div>
            <p class="text-sm"> برای ورود با کد تایید و تلفن همراه روی گزینه بالا کلیک کنید </p>

            <flux:button type="submit" variant="primary" class="w-full">
                ورود
            </flux:button>
        </form>



    @else
        {{-- حالت ورود/ثبت‌نام با OTP --}}
        <form wire:submit="verifyOtp" class="flex flex-col gap-6">
            <flux:input wire:model="identifier" label="شماره تلفن" readonly />

            <div class="flex gap-2 items-end">
                <flux:input wire:model="otp" label="کد تایید" required />


                <flux:button
                    type="button"
                    disabled
                    variant="primary"
                    class="px-4 py-2"
                >
                   رمز پویا ارسال شد
                </flux:button>
            </div>
            <p> اعتبار تا ۵ دقیقه است </p>


            <flux:button type="submit" variant="primary" class="w-full">تایید</flux:button>
        </form>
    @endif
</div>
