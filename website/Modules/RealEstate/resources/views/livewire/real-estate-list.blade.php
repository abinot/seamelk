<div>
    <h3 class="font-bold text-lg mb-3">بنگاه‌های شما</h3>

    @forelse($items as $estate)
        <div class="p-3 border rounded-lg mb-3 bg-gray-50 hover:bg-gray-100 transition">
                 <strong class="text-lg">{{ $estate->id }}</strong>
            <div class="flex justify-between items-center mb-1">
                <strong class="text-lg">{{ $estate->name }}</strong>
                <span class="text-sm text-gray-600">کد: {{ $estate->code }}</span>
            </div>

            <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                <div>
                    <span class="font-bold text-gray-800">نوع:</span>
                    {{ $estate->type }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">امتیاز:</span>
                    {{ $estate->rating_average ?? '–' }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">آدرس:</span>
                    {{ \Illuminate\Support\Str::limit($estate->address, 40) }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">موقعیت:</span>
                    {{ $estate->location }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">تاسیس:</span>
                    {{ jdate($estate->since_date)->format('Y/m/d') }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">وضعیت:</span>
                    {{ $estate->is_active ? 'فعال' : 'غیرفعال' }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">آگهی‌ها:</span>
                    {{ $estate->all_posts_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">مشتریان:</span>
                    {{ $estate->customers_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">کارکنان:</span>
                    {{ $estate->workers_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">نمایش‌ها:</span>
                    {{ $estate->views_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">کامنت‌ها:</span>
                    {{ $estate->comments_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">لایک‌ها:</span>
                    {{ $estate->likes_count }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">Slug:</span>
                    {{ $estate->slug }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">SEO عنوان:</span>
                    {{ $estate->seo_title ?? '–' }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">SEO توضیحات:</span>
                    {{ $estate->seo_description ?? '–' }}
                </div>

                <div>
                    <span class="font-bold text-gray-800">SEO کلمات کلیدی:</span>
                    {{ $estate->seo_keywords ?? '–' }}
                </div>
            </div>

            {{-- تصاویر --}}
            <div class="mt-2 flex gap-2">
                @if($estate->profile_picture_url)
                    <img src="{{ $estate->profile_picture_url }}" class="w-16 h-16 object-cover rounded" alt="لوگو">
                @endif
                @if($estate->thumbnail_url)
                    <img src="{{ $estate->thumbnail_url }}" class="w-16 h-16 object-cover rounded" alt="Thumbnail">
                @endif
                @if($estate->banner_url)
                    <img src="{{ $estate->banner_url }}" class="w-16 h-16 object-cover rounded" alt="بنر">
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500">شما هنوز هیچ بنگاهی ثبت نکرده‌اید.</p>
    @endforelse
</div>
