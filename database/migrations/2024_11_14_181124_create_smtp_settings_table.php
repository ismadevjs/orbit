<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_driver')->default('smtp');
            $table->string('mail_host');
            $table->integer('mail_port');
            $table->string('mail_encryption')->nullable();
            $table->string('mail_username');
            $table->string('mail_password');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('smtp_settings');
    }
};
