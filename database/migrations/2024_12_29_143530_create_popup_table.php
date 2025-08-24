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
        Schema::create('popups', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title')->nullable(); // Title of the popup
            $table->text('description')->nullable(); // Description of the popup
            $table->string('image')->nullable(); // Path to the image
            $table->boolean('status')->default(true); // Active status (true/false)
            $table->timestamp('start_date')->nullable(); // Start date for the popup
            $table->timestamp('end_date')->nullable(); // End date for the popup
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('popups');
    }
};
