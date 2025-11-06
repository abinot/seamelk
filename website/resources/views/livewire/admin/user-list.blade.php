<?php
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout("components.layouts.admin")] class extends Component {
    public $users;

    public function mount()
    {
        $this->users = User::with('roles')->latest()->get();
    }

};
?>
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">لیست کاربران</h2>

    <table class="min-w-full bg-gray border border-gray-200 rounded-xl shadow-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="py-2 px-3 text-right border-b">#</th>
                <th class="py-2 px-3 text-right border-b">شناسه کاربری</th>
                <th class="py-2 px-3 text-right border-b">نام</th>
                <th class="py-2 px-3 text-right border-b">ایمیل</th>
                <th class="py-2 px-3 text-right border-b">نقش</th>
                <th class="py-2 px-3 text-right border-b">تاریخ ساخت</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-3 border-b">{{ $loop->iteration }}</td>
                    <td class="py-2 px-3 border-b">{{ $user->id }}</td>
                    <td class="py-2 px-3 border-b">{{ $user->name }}</td>
                    <td class="py-2 px-3 border-b">{{ $user->email }}</td>
                    <td class="py-2 px-3 border-b">
                        {{ $user->roles->pluck('name')->join(', ') ?: '—' }}
                    </td>
                    <td class="py-2 px-3 border-b text-sm">
                        {{ \Morilog\Jalali\Jalalian::fromDateTime($user->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i:s') }}
                    </td>


                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">
                        هیچ کاربری یافت نشد.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
