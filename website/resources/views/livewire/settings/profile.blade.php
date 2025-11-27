<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithFileUploads;
    public string $name = "";
    public string $email = "";
    public $profile_image;
    public $new_profile_image;
    public $banner_image;
    public $new_banner_image;
    public bool $CanChangeEmail = False;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->profile_image = Auth::user()
            ->metas()
            ->where('meta_key', 'profile_picture_url')
            ->value('meta_value');

        $this->banner_image = Auth::user()
            ->metas()
            ->where('meta_key', 'profile_banner_url')
            ->value('meta_value');
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        if ($this->CanChangeEmail){
        $validated = $this->validate([
            "name" => ["required", "string", "max:255"],

            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                Rule::unique(User::class)->ignore($user->id),
            ],
            "new_profile_image" => ["nullable","image","mimes:jpg,jpeg,png","max:2048"],
            "new_banner_image" => ["nullable","image","mimes:jpg,jpeg,png","max:4096"],
        ]);


    }else{
                $validated = $this->validate([
            "name" => ["required", "string", "max:255"],
            "new_profile_image" => ["nullable","image","mimes:jpg,jpeg,png","max:2048"],
            "new_banner_image" => ["nullable","image","mimes:jpg,jpeg,png","max:4096"],
        ]);
        
    }
if ($this->new_profile_image) {
    $path = $this->new_profile_image->store("avatars","public");

    $user->metas()->updateOrCreate(
        ['meta_key' => 'profile_picture_url'],
        ['meta_value' => $path, 'meta_type' => 'image', 'is_active' => true , 'show'       => json_encode(Role::pluck('name')->toArray()),]
    );

    $this->profile_image = $path; // برای نمایش فوری در فرم
}

if ($this->new_banner_image) {
    $path = $this->new_banner_image->store("banners","public");

    $user->metas()->updateOrCreate(
        ['meta_key' => 'profile_banner_url'],
        ['meta_value' => $path, 'meta_type' => 'image', 'is_active' => true , 'show'       => json_encode(Role::pluck('name')->toArray()),]
    );

    $this->banner_image = $path; // برای نمایش فوری در فرم
}

        $user->fill($validated);

        if ($user->isDirty("email")) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch("profile-updated", name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route("dashboard", absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash("status", "verification-link-sent");
    }
}; ?>

<section class="w-full">
    @include("partials.settings-heading")

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">

        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <div class="flex flex-col items-center space-y-4">
                <!-- نمایش عکس فعلی یا پیش‌فرض -->
                <img 
                    src="{{ $new_profile_image ? $new_profile_image->temporaryUrl() : ($profile_image ? asset('storage/'.$profile_image) : '/images/default-avatar.png') }}" 
                    alt="Profile Image" 
                    class="w-24 h-24 rounded-full object-cover border"
                />

                <!-- ورودی آپلود -->
                <flux:input 
                    wire:model="new_profile_image" 
                    type="file" 
                    accept="image/*" 
                    :label="__('Profile Image')" 
                />
            </div>

            <!-- بخش بنر -->
            <div class="flex flex-col items-center space-y-4">
                <img 
                    src="{{ $new_banner_image ? $new_banner_image->temporaryUrl() : ($banner_image ? asset('storage/'.$banner_image) : '/images/default-banner.png') }}" 
                    alt="Profile Banner" 
                    class="w-full h-32 object-cover border rounded"
                />

                <flux:input 
                    wire:model="new_banner_image" 
                    type="file" 
                    accept="image/*" 
                    :label="__('Profile Banner')" 
                />
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            @if ($CanChangeEmail)
            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __("Your email address is unverified.") }}

                            <flux:link
                                class="text-sm cursor-pointer"
                                wire:click.prevent="resendVerificationNotification"
                            >
                                {{ __("Click here to re-send the verification email.") }}
                            </flux:link>
                        </flux:text>

                        @if (session("status") === "verification-link-sent")
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __("A new verification link has been sent to your email address.") }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __("Save") }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __("Saved.") }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
