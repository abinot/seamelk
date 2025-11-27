<?php

use Livewire\Volt\Component;
use App\Models\UserMeta;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $meta_key;
    public $meta_value;
    public $meta_type;
    public $show = []; // نقش‌های انتخاب شده
    public $editingId = null;

    protected $rules = [
        'meta_key'   => 'required|string|max:100',
        'meta_value' => 'nullable|string',
        'meta_type'  => 'nullable|string|max:100',
        'show'       => 'array',
    ];

    public function save()
    {
        $this->validate();

        Auth::user()->metas()->updateOrCreate(
            ['meta_key' => $this->meta_key],
            [
                'meta_value' => $this->meta_value,
                'meta_type'  => $this->meta_type,
                'show'       => json_encode($this->show),
                'is_active'  => true,
            ]
        );

        $this->reset(['meta_key','meta_value','meta_type','show','editingId']);
        session()->flash('success','Meta ذخیره شد.');
    }

    public function edit($id)
    {
        $meta = Auth::user()->metas()->findOrFail($id);
        $this->editingId = $id;
        $this->meta_key  = $meta->meta_key;
        $this->meta_value= $meta->meta_value;
        $this->meta_type = $meta->meta_type;
        $this->show      = json_decode($meta->show,true) ?? [];
    }

    public function delete($id)
    {
        Auth::user()->metas()->where('id',$id)->delete();
        session()->flash('success','Meta حذف شد.');
    }

    public function with()
    {
        return [
            'metas' => Auth::user()->metas()->get(),
            'roles' => Role::pluck('name')->toArray(),
        ];
    }
}; ?>

<div class="p-4">
    <form wire:submit.prevent="save" class="space-y-3">
        @if($editingId) <h1 class="text-lg">ویرایش {{$meta_key}}</h1> @endif
        <input type="text" wire:model="meta_key" placeholder="Meta Key" class="border p-2 w-full" @if($editingId) readonly @endif>
        <input type="text" wire:model="meta_value" placeholder="Meta Value" class="border p-2 w-full">
        <input type="text" wire:model="meta_type" placeholder="Meta Type" class="border p-2 w-full">

        <label class="block font-bold">نقش‌های قابل نمایش:</label>
        <div class="flex flex-wrap gap-2">
            @foreach($roles as $role)
                <label class="flex items-center space-x-1">
                    <input type="checkbox" wire:model="show" value="{{ $role }}">
                    <span>{{ $role }}</span>
                </label>
            @endforeach
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2">
            {{ $editingId ? 'ویرایش' : 'ذخیره' }}
        </button>
    </form>

    @if(session()->has('success'))
        <div class="text-green-600 mt-2">{{ session('success') }}</div>
    @endif

    <h3 class="mt-5 font-bold">لیست متاها</h3>
    <table class="table-auto w-full border mt-2">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2">Key</th>
                <th class="border px-2">Value</th>
                <th class="border px-2">Type</th>
                <th class="border px-2">Show Roles</th>
                <th class="border px-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($metas as $meta)
                <tr>
                    <td class="border px-2">{{ $meta->meta_key }}</td>
                    <td class="border px-2">{{ $meta->meta_value }}</td>
                    <td class="border px-2">{{ $meta->meta_type }}</td>
                    <td class="border px-2">
                        {{ implode(', ', json_decode($meta->show,true) ?? []) }}
                    </td>
                    <td class="border px-2">
                        <button wire:click="edit({{ $meta->id }})" class="text-blue-600">ویرایش</button>
                        <button wire:click="delete({{ $meta->id }})" class="text-red-600 ml-2">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
