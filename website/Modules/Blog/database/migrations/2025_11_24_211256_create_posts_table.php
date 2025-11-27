<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->index();

            // ستون‌های پایه
            $table->string("title")->index();
            $table->string("slug")->unique()->index();
            $table->longText("content");
            $table->string("excerpt")->nullable();
            $table->string("status")->default('draft')->index();

            // تصاویر
            $table->string("thumbnail_url")->nullable();
            $table->string("banner_url")->nullable();

            // آمار
            $table->unsignedBigInteger("views_count")->default(0)->index();
            $table->unsignedBigInteger("comments_count")->default(0)->index();
            $table->unsignedBigInteger("likes_count")->default(0)->index();
            $table->decimal("rating_average", 3, 2)->default(0)->index();
            $table->unsignedBigInteger("rating_count")->default(0);

            // SEO
            $table->string("seo_title")->nullable();
            $table->text("seo_description")->nullable();
            $table->string("seo_keywords")->nullable(); // به جای JSON، رشته ساده

            // Customization
            $table->text("custom_css")->nullable();
            $table->text("custom_js")->nullable();

            // دسته‌بندی و برچسب‌ها
            $table->string("keyword")->nullable();
            $table->unsignedBigInteger("category_id")->nullable()->index();
            $table->string("tags")->nullable(); // می‌تونی رشته comma-separated ذخیره کنی

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
