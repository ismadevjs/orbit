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
        Schema::table('kyc_requests', function (Blueprint $table) {
            $table->text('residency_photo_path')->after('selfie_path')->nullable();
            $table->text('license_front_photo_path')->nullable();
            $table->text('license_back_photo_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kyc_requests', function (Blueprint $table) {
            $table->dropColumn('residency_photo_path');
            $table->dropColumn('license_back_photo_path');
            $table->dropColumn('license_front_photo_path');
        });
    }
};
