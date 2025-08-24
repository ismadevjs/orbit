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
        Schema::create('contract_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('pdf_name');
            $table->string('pdf_path');
            $table->string('signature_user')->nullable();
            $table->string('signature_pdf_company')->nullable()->default('');
            $table->enum('status', ['pending', 'signed', 'rejected'])->default('pending');
            $table->timestamps();

            // Foreign key constraint (assuming you have a 'users' table)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('contract_list');
    }
};
