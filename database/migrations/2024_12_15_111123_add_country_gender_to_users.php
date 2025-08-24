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
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->text('about')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('country');
            $table->dropColumn('address');
            $table->dropColumn('about');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('image');
        });
    }
};
