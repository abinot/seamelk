<div class="p-6 bg-white rounded-lg shadow">

    @if(session()->has('success'))
        <div class="mb-4 p-3 rounded bg-green-600 text-white text-center">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- name --}}
        <div>
            <label class="block font-semibold">نام بنگاه</label>
            <input type="text" wire:model="name" class="w-full border rounded p-2" />
            @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        {{-- type --}}
        <div>
            <label class="block font-semibold">نوع فعالیت</label>
            <select wire:model="type" class="w-full border rounded p-2">
                <option value="">انتخاب...</option>
                <option value="melk">املاک</option>
                <option value="ejare">اجاره</option>
                <option value="kharid">خرید و فروش</option>
                <option value="multi">چندمنظوره</option>
            </select>
            @error('type') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        {{-- code & location --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold">کد بنگاه</label>
                <input type="text" wire:model="code" class="w-full border rounded p-2" />
                @error('code') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block font-semibold">location (مختصات یا کد)</label>
                <input type="text" wire:model="location" class="w-full border rounded p-2" placeholder="مثلاً H+wg4 یا 30.123,51.234" />
                @error('location') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- address --}}
        <div>
            <label class="block font-semibold">نشانی (قابل خواندن)</label>
            <input type="text" wire:model="address" class="w-full border rounded p-2" />
            @error('address') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        {{-- owner (admin only) --}}
        @if($isAdmin)
            <div>
                <label class="block font-semibold">مالک (ادمین)</label>
                <select wire:model="owner_id" class="w-full border rounded p-2">
                    <option value="">انتخاب مالک...</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                @error('owner_id') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
            </div>
        @endif

        {{-- since_date --}}
        <div>
            <label class="block font-semibold">تاریخ تاسیس (جلالی)</label>
            <input type="text" wire:model="since_date" id="since_date" class="w-full border rounded p-2" placeholder="1402-05-16" />
            @error('since_date') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        {{-- description --}}
        <div>
            <label class="block font-semibold">توضیحات</label>
            <textarea wire:model="description" class="w-full border rounded p-2" rows="4"></textarea>
            @error('description') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        {{-- uploads: logo, banner, thumbnail --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div>
                <label class="block font-semibold">لوگو (اختیاری)</label>
                <input type="file" wire:model="logoFile" accept="image/*" />
                @error('logoFile') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                @if($logoFile)
                    <img src="{{ $logoFile->temporaryUrl() }}" class="mt-2 w-28 h-28 object-cover rounded" alt="preview">
                @elseif($profile_picture_url)
                    <img src="{{ $profile_picture_url }}" class="mt-2 w-28 h-28 object-cover rounded" alt="logo">
                @endif
            </div>

            <div>
                <label class="block font-semibold">بنر (اختیاری)</label>
                <input type="file" wire:model="bannerFile" accept="image/*" />
                @error('bannerFile') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                @if($bannerFile)
                    <img src="{{ $bannerFile->temporaryUrl() }}" class="mt-2 w-full h-28 object-cover rounded" alt="preview">
                @elseif($banner_url)
                    <img src="{{ $banner_url }}" class="mt-2 w-full h-28 object-cover rounded" alt="banner">
                @endif
            </div>

            <div>
                <label class="block font-semibold">تامنیل (آپلود اختیاری)</label>
                <input type="file" wire:model="thumbnailFile" accept="image/*" />
                @error('thumbnailFile') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror

                @if($thumbnailFile)
                    <img src="{{ $thumbnailFile->temporaryUrl() }}" class="mt-2 w-28 h-28 object-cover rounded" alt="preview">
                @elseif($thumbnail_url)
                    <img src="{{ $thumbnail_url }}" class="mt-2 w-28 h-28 object-cover rounded" alt="thumbnail">
                @endif

                <div class="mt-2">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" wire:model="auto_thumbnail" @if(!$logoFile || !$bannerFile) disabled @endif>
                        <span class="text-sm">تولید خودکار تامنیل (نیاز به لوگو و بنر)</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- admin fields --}}
        @if($isAdmin)
            <div class="border-t pt-4 space-y-3">
                <div>
                    <label class="block font-semibold">Slug (دلخواه ادمین)</label>
                    <input type="text" wire:model="slug" class="w-full border rounded p-2" />
                    @error('slug') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block font-semibold">SEO عنوان</label>
                    <input type="text" wire:model="seo_title" class="w-full border rounded p-2" />
                </div>

                <div>
                    <label class="block font-semibold">SEO توضیحات</label>
                    <textarea wire:model="seo_description" class="w-full border rounded p-2" rows="3"></textarea>
                </div>

                <div>
                    <label class="block font-semibold">SEO کلمات کلیدی</label>
                    <input type="text" wire:model="seo_keywords" class="w-full border rounded p-2" />
                </div>

                <div>
                    <label class="block font-semibold">Custom CSS</label>
                    <textarea wire:model="custom_css" class="w-full border rounded p-2" rows="2"></textarea>
                </div>

                <div>
                    <label class="block font-semibold">Custom JS</label>
                    <textarea wire:model="custom_js" class="w-full border rounded p-2" rows="2"></textarea>
                </div>

                <div>
                    <label class="block font-semibold">Verified at</label>
                    <input type="datetime-local" wire:model="verified_at" class="w-full border rounded p-2" />
                </div>
            </div>
        @endif

        {{-- is_active --}}
        <div>
            <label class="block font-semibold">وضعیت</label>
            <select wire:model="is_active" class="w-full border rounded p-2">
                <option value="1">فعال</option>
                <option value="0">غیرفعال</option>
            </select>
        </div>

        <div>
            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded">💾 ذخیره بنگاه</button>
        </div>
    </form>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            $("#since_date").persianDatepicker({
                format: "YYYY-MM-DD",
                autoClose: true,
                onSelect: function(unix){
                    @this.set('since_date', new persianDate(unix).format('YYYY-MM-DD'));
                }
            });
        });
    </script>
    @endpush

</div>
