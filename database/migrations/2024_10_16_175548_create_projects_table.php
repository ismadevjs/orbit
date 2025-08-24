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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->text('description');
            $table->json('images');
            $table->string('area');
            $table->json('location'); // Storing latitude, longitude, and address
            $table->json('facilities'); // Facilities array
            $table->json('distances'); // Distances array
            $table->json('custom_field_names'); // Custom field names array
            $table->json('custom_field_values'); // Custom field values array
            $table->json('video_links'); // Video links array
            $table->string('speciality_video')->nullable();
            $table->string('detailed_video')->nullable();
            $table->json('project_types'); // Project types array
            $table->json('surfaces'); // Surfaces array
            $table->json('prices'); // Prices array
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
