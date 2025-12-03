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
        Schema::create('real_estate_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('real_estate_id')->constrained('real_estate')->cascadeOnDelete();
            
            $table->string('meta_key', 100)->index(); // مثال: phone_number
            $table->string('meta_type', 50)->nullable(); // نوع داده: string, integer, boolean
            $table->string('meta_name', 100)->nullable(); // مثال: شماره تلفن
            $table->integer('meta_count_number')->nullable(); // برای مشخص کردن چندتا مقدار
            $table->timestamp('verified_at')->nullable();

            $table->longText('meta_value')->nullable(); // مقدار اصلی
            $table->string('meta_value2')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('show')->nullable();
            $table->string('delete_type')->default('none');
            $table->longText('meta_note')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_estate_metas');
    }
};
