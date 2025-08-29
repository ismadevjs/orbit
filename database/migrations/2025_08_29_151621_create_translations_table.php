<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Only create table if it doesn't exist
        if (!Schema::hasTable('translations')) {
            Schema::create('translations', function (Blueprint $table) {
                $table->id();
                $table->string('key');

                // Check languages table exists
                if (Schema::hasTable('languages')) {
                    $table->foreignId('language_id')->constrained()->onDelete('cascade');
                } else {
                    $table->unsignedBigInteger('language_id');
                }

                $table->text('value')->nullable();
                $table->timestamps();
                $table->unique(['key','language_id']);
            });
        } else {
            // If table exists, make sure language_id column exists and is unsignedBigInteger
            Schema::table('translations', function (Blueprint $table) {
                if (!Schema::hasColumn('translations', 'language_id')) {
                    $table->unsignedBigInteger('language_id')->after('key');
                }
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('translations');
    }
};
