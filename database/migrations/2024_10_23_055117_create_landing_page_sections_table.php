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
        Schema::create('landing_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('unique_name')->unique();
            $table->text('content');
            $table->string('image')->nullable();
            $table->json('buttons');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landing_page_sections');
    }
};
