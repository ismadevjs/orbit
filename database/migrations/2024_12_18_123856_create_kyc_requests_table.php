<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kyc_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('document_type'); // 'id_card', 'driving_license', 'passport'
            $table->string('selfie_path');
            $table->string('front_photo_path')->nullable();
            $table->string('back_photo_path')->nullable();
            $table->string('passport_photo_path')->nullable();
            $table->text('additional_info')->nullable(); // JSON or text
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing', 'completed', 'needtopay', 'message'])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_requests');
    }
};
