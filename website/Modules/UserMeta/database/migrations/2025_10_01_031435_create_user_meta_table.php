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
        Schema::create('users_meta', function (Blueprint $table) {
            $table->id();

            // شناسه کاربر — constrained() خودش index و foreign key می‌سازد
            $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

            // کلید متا: از string استفاده می‌کنیم تا ایندکس‌پذیر باشد
            // طول 170 هم درست کار می‌کنه؛ اما اگر می‌خوای سازگاری حداکثری باشه از 191 استفاده کن
            $table->string('meta_key', 170)->index();

            // مقدار متا: متون طولانی را اینجا می‌ذاریم
            $table->longText('meta_value')->nullable();

            // یک note اختیاری کوتاه برای توضیح، بدون ایندکس سنگین
            $table->string('note', 170)->nullable()->index();

            $table->timestamps();

            // ایندکس ترکیبی برای کوئری‌های متداول: WHERE user_id = ? AND meta_key = ?
            $table->index(['user_id', 'meta_key'], 'idx_user_meta');
            $table->index(['user_id', 'note'], 'idx_user_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_meta');
    }
};




