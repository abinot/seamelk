<?php

namespace Modules\RealEstate\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\RealEstate\Models\RealEstate;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class RealEstateForm extends Component
{
    use WithFileUploads;

    // فیلدهای فرم
    public $name, $type, $code, $address, $location, $owner_id, $since_date, $description;
    public $is_active = true;
    public $seo_title, $seo_description, $seo_keywords;
    public $custom_css, $custom_js;
    public $slug;
    public $verified_at;

    // فایل‌های آپلودی (TemporaryUploadedFile)
    public $logoFile;     // لوگو (آپلود)
    public $bannerFile;   // بنر (آپلود)
    public $thumbnailFile; // تامنیل آپلود شده دستی (اختیاری)

    // مسیرهای ذخیره شده (string) که در DB میرن
    public $profile_picture_url;
    public $banner_url;
    public $thumbnail_url;

    public $auto_thumbnail = false; // تیک تولید خودکار تامنیل

    public $isAdmin = false;
    public $users = [];

    protected function rules()
    {
        $base = [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:real_estates,code',
            'address' => 'required|string|max:1000',
            'location' => 'nullable|string|max:255',
            'since_date' => 'required|string', // جلالی ورودی
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'profile_picture_url' => 'nullable|string',
            'banner_url' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            // فایل‌ها
            'logoFile' => 'nullable|image|max:10240',    // 10MB
            'bannerFile' => 'nullable|image|max:10240',
            'thumbnailFile' => 'nullable|image|max:10240',
        ];

        if ($this->isAdmin) {
            $base = array_merge($base, [
                'seo_title' => 'nullable|string|max:255',
                'seo_description' => 'nullable|string|max:160',
                'seo_keywords' => 'nullable|string|max:500',
                'custom_css' => 'nullable|string',
                'custom_js' => 'nullable|string',
                'slug' => 'nullable|string|max:255|unique:real_estates,slug',
                'owner_id' => 'required|exists:users,id',
                'verified_at' => 'nullable|date',
            ]);
        } else {
            $base['owner_id'] = 'required|exists:users,id';
        }

        return $base;
    }

    public function mount()
    {
        $this->isAdmin = Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
        $this->owner_id = Auth::id();

        if ($this->isAdmin) {
            $this->users = \App\Models\User::orderBy('name')->get();
        }
    }

    public function updatedLogoFile()
    {
        // preview handled in blade via temporaryUrl()
    }

    public function updatedBannerFile()
    {
        // preview handled in blade via temporaryUrl()
    }

    private function storeUploadedImage($file, $folder = 'real_estates')
    {
        if (! $file) return null;

        // نام فایل یکتا
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("$folder", $filename, 'public'); // storage/app/public/real_estates/...
        return Storage::url($path); // => /storage/real_estates/xxxxx.jpg
    }

    private function generateThumbnailFromLogoAndBanner($logoPathLocal, $bannerPathLocal)
    {
        // logoPathLocal and bannerPathLocal are local temp paths (->getRealPath())
        // این تابع یک تصویر کوچک می‌سازد و آن را در storage/public/real_estates/thumbnails ذخیره می‌کند
        try {
            $img = Image::make($bannerPathLocal)->resize(800, 450, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });

            $logo = Image::make($logoPathLocal);
            // اندازه‌ی لوگو را مناسب کن (مثلاً 150px عرض)
            $logo->resize(150, null, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });

            // درج لوگو در مرکز بنر
            $img->insert($logo, 'center');

            $thumbName = 'real_estates/thumbnails/' . uniqid() . '.jpg';
            $fullPath = storage_path('app/public/' . $thumbName);
            // ایجاد دایرکتوری اگر نبود
            @mkdir(dirname($fullPath), 0755, true);
            $img->save($fullPath, 85); // کیفیت 85

            return Storage::url($thumbName);
        } catch (\Throwable $e) {
            // در صورت خطا null برگردان
            return null;
        }
    }

    public function save()
    {
        // اعتبارسنجی اولیه
        $this->validate($this->rules());

        // تبدیل تاریخ جلالی
        try {
            $miladi = Jalalian::fromFormat('Y-m-d', $this->since_date)->toCarbon();
        } catch (\Throwable $e) {
            $this->addError('since_date', 'فرمت تاریخ جلالی صحیح نیست. مثال: 1402-05-16');
            return;
        }

        // ذخیره فایل‌ها (اگر آپلود شده‌اند)
        if ($this->logoFile) {
            $this->profile_picture_url = $this->storeUploadedImage($this->logoFile, 'real_estates/logos');
        }

        if ($this->bannerFile) {
            $this->banner_url = $this->storeUploadedImage($this->bannerFile, 'real_estates/banners');
        }

        // اگر کاربر فایل thumbnail آپلود کرده، اولویت با آن است
        if ($this->thumbnailFile) {
            $this->thumbnail_url = $this->storeUploadedImage($this->thumbnailFile, 'real_estates/thumbnails');
        } elseif ($this->auto_thumbnail && $this->logoFile && $this->bannerFile) {
            // تولید خودکار تامنیل از نسخه‌های temp
            $thumbUrl = $this->generateThumbnailFromLogoAndBanner(
                $this->logoFile->getRealPath(),
                $this->bannerFile->getRealPath()
            );
            $this->thumbnail_url = $thumbUrl;
        }

        // owner id
        $ownerId = $this->isAdmin ? $this->owner_id : Auth::id();

        // slug
        if ($this->isAdmin && !empty($this->slug)) {
            $slug = Str::slug($this->slug);
        } else {
            $slug = Str::slug($this->name);
        }
        if (empty($slug)) $slug = 'estate-' . uniqid();
        $original = $slug;
        $i = 1;
        while (RealEstate::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        // seo defaults
        $seo_title = $this->seo_title ?: $this->name;
        $seo_description = $this->seo_description ?: substr($this->description ?? '', 0, 160);
        $seo_keywords = $this->seo_keywords ?: implode(', ', array_filter([$this->name, $this->type]));

        // verified_at parse only if admin
        $verifiedAt = null;
        if ($this->isAdmin && !empty($this->verified_at)) {
            try {
                $verifiedAt = Carbon::parse($this->verified_at);
            } catch (\Throwable $e) {
                $verifiedAt = null;
            }
        }

        // ایجاد رکورد — توجه کن که مدل تو باید fillable شامل این فیلدها باشه
        RealEstate::create([
            'name' => $this->name,
            'type' => $this->type,
            'code' => $this->code,
            'address' => $this->address,
            'location' => $this->location,
            'owner_id' => $ownerId,
            'since_date' => $miladi,
            'description' => $this->description,
            'seo_title' => $seo_title,
            'seo_description' => $seo_description,
            'seo_keywords' => $seo_keywords,
            'custom_css' => $this->isAdmin ? $this->custom_css : null,
            'custom_js' => $this->isAdmin ? $this->custom_js : null,
            'profile_picture_url' => $this->profile_picture_url,
            'banner_url' => $this->banner_url,
            'thumbnail_url' => $this->thumbnail_url,
            'slug' => $slug,
            'is_active' => (bool)$this->is_active,
            'verified_at' => $verifiedAt,
        ]);

        session()->flash('success', 'بنگاه با موفقیت ثبت شد.');

        // پاکسازی فرم و فایل‌های موقتی
        $this->resetValidation();
        $this->reset([
            'name','type','code','address','location','since_date','description',
            'seo_title','seo_description','seo_keywords','custom_css','custom_js',
            'slug','is_active','verified_at',
            'logoFile','bannerFile','thumbnailFile','profile_picture_url','banner_url','thumbnail_url','auto_thumbnail'
        ]);

        $this->owner_id = $this->isAdmin ? null : Auth::id();
    }

    public function render()
    {
        return view('realestate::livewire.real-estate-form', [
            'isAdmin' => $this->isAdmin,
            'users' => $this->users,
        ]);
    }
}
