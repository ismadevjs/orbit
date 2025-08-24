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
        Schema::table('carousels', function (Blueprint $table) {
            $table->string('button_name')->nullable();
            $table->string('button_link')->nullable();
            $table->string('video_text')->nullable();
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropColumn(['button_name', 'button_link', 'video_text', 'video_id']);
        });
    }
};
