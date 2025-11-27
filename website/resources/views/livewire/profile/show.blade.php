<div class="pt-6">
    <div class="flex items-center space-x-4 rtl:space-x-reverse">
        <!-- تصویر آواتار یا جایگزین حروف اول نام -->
        <div class="relative">
            @if(!empty($user['avatar']))
                <img 
                    class="h-20 w-20 rounded-full object-cover" 
                    src="{{ $user['avatar'] }}" 
                    alt="User avatar"
                />
            @else
                <div class="h-20 w-20 rounded-full bg-indigo-500 text-white flex items-center justify-center text-2xl font-bold select-none m-4">
                    {{ strtoupper(substr($user['name'], 0, 1)) }}
                </div>
            @endif

           
        </div>

        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user['name'] }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ $user['email'] }}</p>
            <p class="text-green-600 font-medium mt-1">Active Now</p>
        </div>
    </div>

    <!-- <div class="mt-6 flex space-x-4 rtl:space-x-reverse justify-center">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add Friend</button>
        <button class="px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">Message</button>
    </div> -->

    <div class="mt-6 grid grid-cols-2 gap-4 text-gray-700 dark:text-gray-300">
        <div>
            <span class="font-semibold block mb-1">Joined</span>
            <span>{{ \Illuminate\Support\Carbon::parse($user['created_at'])->format('Y/m/d') }}</span>
        </div>
        <div>
            <span class="font-semibold block mb-1">Email Verified</span>
            <span>{{ $user['email_verified_at'] ? 'Yes' : 'No' }}</span>
        </div>
        <div>
            <span class="font-semibold block mb-1">Last Updated</span>
            <span>{{ \Illuminate\Support\Carbon::parse($user['updated_at'])->format('Y/m/d') }}</span>
        </div>
        <div>
            <span class="font-semibold block mb-1">User ID</span>
            <span>{{ $user['id'] }}</span>
        </div>
        @php
    use BaconQrCode\Renderer\ImageRenderer;
    use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
    use BaconQrCode\Renderer\RendererStyle\RendererStyle;
    use BaconQrCode\Writer;

    $renderer = new ImageRenderer(
        new RendererStyle(200),
        new ImagickImageBackEnd()
    );

    $writer = new Writer($renderer);

    $url = config('app.url') . '/@' . $user->id;

    $imageData = $writer->writeString($url);
    $qrCode = 'data:image/png;base64,' . base64_encode($imageData);
@endphp

<img src="{{ $qrCode }}" alt="QR Code" class="w-40 h-40">
<a href="{{$url}}" > {{$url}}</a>
    </div>
</div>
