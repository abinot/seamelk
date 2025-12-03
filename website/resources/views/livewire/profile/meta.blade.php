<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public $meta_key;
    public $meta_value;
    public $meta_type;
    public $show = []; // نقش‌ها
    public $editingId = null;

    // نقش‌ها بر اساس درخواست شما
    public $roleSets = [
        'all' => [
            'admin', 'real_estate_member', 'super_admin', 'user', 'verified_user'
        ],
        'hidden' => [
            'admin', 'super_admin'
        ],
        'estate' => [
            'real_estate_member', 'admin', 'super_admin'
        ],
    ];
    public $roleLabels = [
    'all'    => 'همه کاربران',
    'estate' => 'کاربران املاکی',
    'hidden' => 'پنهان',
];


    protected $rules = [
        'meta_key'   => 'required|string|max:100',
        'meta_value' => 'nullable|string',
        'meta_type'  => 'nullable|string|max:100',
        'show' => 'required|string',

    ];

    public function save()
    {
        $this->validate();

        Auth::user()->metas()->updateOrCreate(
            ['meta_key' => $this->meta_key],
            [
                'meta_value' => $this->meta_value,
                'meta_type'  => $this->meta_type,

                // تبدیل مقدار انتخابی UI به نقش‌های واقعی
                'show'       => json_encode(
                    $this->roleSets[$this->show] ?? []
                ),

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

        // پیدا کردن کلید سطح نمایش (all / hidden / estate)
        foreach ($this->roleSets as $key => $roles) {
            if (json_encode($roles) === $meta->show) {
                $this->show = $key;
            }
        }
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
        ];
    }
};
?>

<div class="p-6 min-h-screen">

    <div class="rounded-xl shadow p-6 mb-6">

        <h2 class="text-lg font-semibold mb-4">
            {{ $editingId ? 'ویرایش متاداده' : 'متاداده جدید' }}
        </h2>

        <form wire:submit.prevent="save" class="space-y-6">

            {{-- key --}}
            <x-flux::fieldset>
                <x-flux::label class="flex items-center gap-2">
                    کلید
                    <x-flux::tooltip>
                        <span class="text-xs">مثلاً phone_number ، bio ، instagram</span>
                    </x-flux::tooltip>
                </x-flux::label>

                <x-flux::input wire:model="meta_key" placeholder="مثلاً phone_number" :disabled="$editingId" />
                <div class="text-xs mt-1">نام یکتا برای داده.</div>
            </x-flux::fieldset>

            {{-- value --}}
            <x-flux::fieldset>
                <x-flux::label class="flex items-center gap-2">
                    مقدار
                    <x-flux::tooltip><span class="text-xs">محتوا یا داده مورد نظر</span></x-flux::tooltip>
                </x-flux::label>

                <x-flux::input wire:model="meta_value" placeholder="مثلاً 0913…" />
                <div class="text-xs mt-1">مقدار داده.</div>
            </x-flux::fieldset>

            {{-- type --}}
            <x-flux::fieldset>
                <x-flux::label class="flex items-center gap-2">
                    نوع
                    <x-flux::tooltip><span class="text-xs">string / number / date</span></x-flux::tooltip>
                </x-flux::label>

                <x-flux::input wire:model="meta_type" placeholder="string / number / date" />
                <div class="text-xs mt-1">نوع داده را مشخص کنید.</div>
            </x-flux::fieldset>

            {{-- show --}}
            <x-flux::fieldset>
              

                <div class="space-y-3 mt-2">
<flux:radio.group wire:model="show" label="این داده به چه  کسانی نمایش داده شود؟">
<x-flux::radio 
 
    value="all" 

    label="نمایش برای همه کاربران" />

<x-flux::radio 
    
    value="estate" 



    label="فقط کاربران املاکی (بعلاوه مدیرها)" />

<x-flux::radio 
 
    value="hidden" 
 
 

    label="پنهان (فقط مدیران)" />



   </flux:radio.group>



                </div>

                <div class="text-xs mt-2">سطح نمایش رشته‌ها روی نقش‌های کاربری اعمال می‌شود.</div>
            </x-flux::fieldset>

            {{-- buttons --}}
            <div class="flex gap-3 pt-4">
                <x-flux::button type="submit" variant="primary">
                    {{ $editingId ? 'بروزرسانی' : 'ذخیره' }}
                </x-flux::button>

                @if($editingId)
                <x-flux::button wire:click="$set('editingId', null)" variant="primary">
                    انصراف
                </x-flux::button>
                @endif
            </div>
        </form>
    </div>

    {{-- جدول --}}
    <div class=" rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">متاداده‌ها</h3>
            <span class="bg-gray-100 text-gray-600 text-sm px-2 py-1 rounded">{{ count($metas) }} مورد</span>
        </div>

        @if(count($metas) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs text-gray-500">کلید</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500">مقدار</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500">نوع</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500">نمایش برای</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-500">عملیات</th>
                    </tr>
                </thead>

                <tbody class="">
                    @foreach($metas as $meta)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $meta->meta_key }}</td>
                        <td class="px-4 py-3 text-sm">{{ $meta->meta_value }}</td>
                        <td class="px-4 py-3 text-sm">{{ $meta->meta_type ?? '-' }}</td>

                      
@php
    $roles = json_decode($meta->show, true) ?? [];

    $display = null;
    foreach ($roleSets as $key => $set) {
        if ($roles === $set) {
            $display = $roleLabels[$key] ?? null;
            break;
        }
    }
@endphp

<td class="px-4 py-3 text-sm">
    {{ $display ?? implode('، ', $roles) }}
</td>


                      

                        <td class="px-4 py-3 text-sm flex gap-2">
                            <button wire:click="edit({{ $meta->id }})" class="text-blue-600 hover:text-blue-800">ویرایش</button>
                            <button wire:click="delete({{ $meta->id }})" class="text-red-600 hover:text-red-800" onclick="return confirm('حذف شود؟')">حذف</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">هیچ متاداده‌ای ثبت نشده است.</div>
        @endif
    </div>
</div>

