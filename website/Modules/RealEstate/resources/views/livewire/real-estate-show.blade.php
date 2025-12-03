<div dir="rtl" class="rtl text-right">


@if (!$real_estate)
    <p>کاربر پیدا نشد.</p>
@else

<body>
<div class="h-full">

    <div class="bg-white rounded-lg shadow-xl pb-8">

        {{-- Settings Button --}}
        <div x-data="{ openSettings: false }" class="absolute right-12 mt-4 rounded">
            <button @click="openSettings = !openSettings"
                    class="border border-gray-400 p-2 rounded text-gray-300 hover:text-gray-300 bg-gray-100 bg-opacity-10 hover:bg-opacity-20"
                    title="Settings">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                          d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                </svg>
            </button>
            <div x-show="openSettings" @click.away="openSettings = false"
                 class="bg-white absolute right-0 w-40 py-2 mt-1 border border-gray-200 shadow-2xl"
                 style="display: none;">

                <div class="py-2 border-b">
                    <button class="w-full flex items-center px-6 py-1.5 space-x-2 hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        <span class="text-sm text-gray-700">همرسانی</span>
                    </button>
                </div>

                <div class="py-2">
                    <button class="w-full flex items-center py-1.5 px-6 space-x-2 hover:bg-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-sm text-gray-700">گزارش</span>
                    </button>

                    <button class="w-full flex items-center py-1.5 px-6 space-x-2 hover:bg-gray-200">
                        <span class="text-sm text-gray-700">بازخورد</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- Banner --}}
        <div class="w-full h-[300px]">
            <img src="{{ $real_estate->banner_url }}" alt="Cover Image"
                 class="w-full h-full object-cover object-top">
        </div>

        {{-- Header --}}
        <div class="flex flex-col -mt-20 pr-5">
            <img src="{{ $real_estate->profile_picture_url }}"
                 class="w-40 border-4 border-white bg-white rounded-full">
            <div class="flex items-center space-x-2 mt-2 pl-10">
                <p class="text-2xl">{{ $real_estate->name }}</p>

                @if($real_estate->verified_at)
                    <span class="bg-blue-500 rounded-full p-1" title="Verified">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="text-gray-100 h-2.5 w-2.5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                @endif
            </div>

            {{-- slug --}}
            <p class="text-gray-700 pl-10">
                @if($real_estate->slug)
                    ({{ $real_estate->slug }})
                @endif
            </p>

            {{-- header + type + code --}}
            <p class="text-sm text-gray-500 pl-10">
                نوع کسب‌و‌کار: {{ $real_estate->type }}  
                <br>
                کد صنف: {{ $real_estate->code }}
                <br>
                سال تأسیس: {{ jdate($real_estate->since_date)->format('Y') }}
                <br>
                آدرس: {{ $real_estate->address }}
                <br>
                موقعیت: {{ $real_estate->location }}
            </p>
        </div>

        <br>

        {{-- header2 --}}
        <p class="text-sm text-gray-700 pl-16">
            @if($real_estate && $real_estate->data)
                @foreach($real_estate->data as $data)
                    @if($data['key'] == "header2")
                        {{ $data['value'] }}
                    @endif
                @endforeach
            @endif
            <br>
        </p>

    </div>


    {{-- Body section --}}
    <div class="my-4 flex flex-col 2xl:flex-row space-y-4 2xl:space-y-0 2xl:space-x-4">

        {{-- Left Box: biography + description --}}
        <div class="flex flex-col w-full 2xl:w-2/3">
            <div class="flex-1 bg-white rounded-lg shadow-xl p-8">

                <p class="mt-2 text-gray-700">

                    {{-- custom biography --}}
                    @if($real_estate && $real_estate->data)
                        @foreach($real_estate->data as $data)
                            @if(in_array($data['key'], ["biography", "text"]) && $data['show'] == 2)
                                <li class="flex py-2">
                                    <span class="text-gray-700">{{ $data['value'] }}</span>
                                </li>
                            @endif
                        @endforeach
                    @endif

                    {{-- main description --}}
                    @if($real_estate->description)
                        <p class="mt-4 text-gray-800">{{ $real_estate->description }}</p>
                    @endif

                </p>
            </div>
        </div>

        {{-- Right Box: Metadata & Stats --}}
        <div class="w-full flex flex-col 2xl:w-1/3">
            <div class="flex-1 bg-white rounded-lg shadow-xl p-8">

                <ul class="mt-2 text-gray-700">

                    {{-- Views / Likes / Comments --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">بازدید:</span>
                        <span>{{ number_format($real_estate->views_count) }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">لایک:</span>
                        <span>{{ number_format($real_estate->likes_count) }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">دیدگاه:</span>
                        <span>{{ number_format($real_estate->comments_count) }}</span>
                    </li>

                    {{-- Rating --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">امتیاز:</span>
                        <span>{{ $real_estate->rating_average }} ({{ $real_estate->rating_count }} نظر)</span>
                    </li>

                    {{-- Counts --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">آگهی‌ها:</span>
                        <span>{{ $real_estate->all_posts_count }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">خانه‌ها:</span>
                        <span>{{ $real_estate->home_posts_count }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">تبلیغات:</span>
                        <span>{{ $real_estate->ads_posts_count }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">مشتریان:</span>
                        <span>{{ $real_estate->customers_count }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">کارکنان:</span>
                        <span>{{ $real_estate->workers_count }}</span>
                    </li>

                    {{-- SEO --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">SEO عنوان:</span>
                        <span>{{ $real_estate->seo_title ?? '—' }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">SEO توضیح:</span>
                        <span>{{ $real_estate->seo_description ?? '—' }}</span>
                    </li>
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">SEO کلیدواژه:</span>
                        <span>{{ $real_estate->seo_keywords ?? '—' }}</span>
                    </li>

                    {{-- Owner --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">مالک:</span>
                        <span>{{ $real_estate->owner_id }}</span>
                    </li>

                    {{-- Status --}}
                    <li class="flex border-y py-2">
                        <span class="font-bold w-24">وضعیت:</span>
                        <span>{{ $real_estate->is_active ? 'فعال' : 'غیرفعال' }}</span>
                    </li>

                </ul>

            </div>
        </div>

    </div>

</div>
</body>
@endif

</div>
