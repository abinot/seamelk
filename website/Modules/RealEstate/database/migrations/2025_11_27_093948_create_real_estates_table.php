<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('real_estates', function (Blueprint $table) {
            $table->id();

            // اطلاعات پایه
            $table->string('name')->index();
            $table->string('type')->index();
            $table->string('code')->unique();
            $table->string('address');
            $table->string('location')->index();
            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('since_date')->index();
            $table->text('description');

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            // سفارشی‌سازی
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();

            // تصاویر
            $table->string('profile_picture_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('banner_url')->nullable();
            $table->string('slug')->unique()->index();

            // آمار و امتیاز
            $table->unsignedBigInteger('views_count')->default(0)->index();
            $table->unsignedBigInteger('comments_count')->default(0)->index();
            $table->unsignedBigInteger('likes_count')->default(0)->index();
            $table->decimal('rating_average', 3, 2)->default(0)->index();
            $table->unsignedBigInteger('rating_count')->default(0);

            // متادیتا
            $table->unsignedInteger('home_posts_count')->default(0)->index();
            $table->unsignedInteger('all_posts_count')->default(0)->index();
            $table->unsignedInteger('ads_posts_count')->default(0)->index();
            $table->unsignedInteger('customers_count')->default(0)->index();
            $table->unsignedInteger('workers_count')->default(0)->index();

            // وضعیت
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('real_estates');
    }
};
