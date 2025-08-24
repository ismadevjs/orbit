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
        Schema::table('headings', function (Blueprint $table) {
            $table->string('button_name')->nullable();
            $table->string('button_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('headings', function (Blueprint $table) {
            $table->dropColumn('button_name');
            $table->dropColumn('button_url');
        });
    }
};
