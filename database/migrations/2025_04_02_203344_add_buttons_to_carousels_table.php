<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddButtonsToCarouselsTable extends Migration
{
    public function up()
    {
        Schema::table('carousels', function (Blueprint $table) {
            // Add the buttons column as JSON
            $table->json('buttons')->nullable();


            // If you previously had button_name and button_link columns, you might want to drop them
            // Uncomment these lines if you need to remove old columns
            // $table->dropColumn('button_name');
            // $table->dropColumn('button_link');
        });
    }

    public function down()
    {
        Schema::table('carousels', function (Blueprint $table) {
            // Drop the buttons column
            $table->dropColumn('buttons');

            // If you dropped button_name and button_link, you might want to add them back
            // Uncomment and adjust these lines if needed
            // $table->string('button_name')->nullable();
            // $table->string('button_link')->nullable();
        });
    }
}
