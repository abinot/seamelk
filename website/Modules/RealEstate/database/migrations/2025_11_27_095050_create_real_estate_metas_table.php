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
            $table->string("meta_key")->index();
            $table->foreignId('real_state_id')->constrained('real_state')->onDelete('cascade')->index();
            $table->longText("meta_value");
            $table->longText("meta_note")->nullable();
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
