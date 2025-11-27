<x-layouts.app>
    <div>
        <h1>پروفایل کاربر</h1>

        {{-- اینجا Livewire/Volt کامپوننت رو صدا می‌زنیم --}}
        @livewire('profile.show', ['username' => $username])
    </div>
</x-layouts.app>
