<x-layouts.app :title="__('Dashboard')">
    @php
        $user = auth()->user();
    @endphp

    @if($user && $user->hasRole('admin'))
        
        <livewire:admin.user-list/>
    @else
        {{-- اگر ادمین نبود --}}
        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />

                    <h4 class="font-bold mb-2">نقش‌های کاربر:</h4>
                    <ul class="space-y-1">
                        @forelse($user->getRoleNames() as $role)
                            <li>{{ $role }}</li>
                        @empty
                            <li class="text-gray-400">هیچ نقشی اختصاص نیافته است.</li>
                        @endforelse
                    </ul>

                    <h4 class="font-bold mb-2 mt-4">دسترسی‌های کاربر:</h4>
                    <ul class="space-y-1">
                        @forelse($user->getAllPermissions() as $permission)
                            <li>{{ $permission->name }}</li>
                        @empty
                            <li class="text-gray-400">هیچ دسترسی اختصاص نیافته است.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
                </div>
            </div>

            <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
    @endif
</x-layouts.app>
