<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('position')->nullable(); // Nullable position column
            $table->string('image')->nullable(); // Nullable image column
            $table->string('experience')->nullable(); // Nullable experience column
            $table->string('location')->nullable(); // Nullable location column
            $table->string('practice_area')->nullable(); // Nullable practice_area column
            $table->string('projects_done')->nullable(); // Nullable projects_done column
            $table->string('title')->nullable(); // Nullable title column
            $table->text('description')->nullable(); // Nullable description column
            $table->string('instagram')->nullable(); // Nullable JSON column for social medias
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members'); // Drop the members table
    }
};
