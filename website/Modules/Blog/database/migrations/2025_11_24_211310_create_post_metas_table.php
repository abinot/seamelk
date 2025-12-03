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
        Schema::create('post_metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('meta_key', 100)->index();
            $table->string('meta_type', 100)->nullable();
            $table->longText('meta_value')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->boolean('is_active')->default(True);
            $table->json('show');
            $table->string('delete_type')->default('none'); // onDeleteCascade - User Delete - Admin Delete - System Delete
            $table->longText('meta_note')->default('')->nullable();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade')->index();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_metas');
    }
};
