<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPostIdToCarouselsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('id'); // Add project_id column
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropForeign(['project_id']); // Drop foreign key constraint
            $table->dropColumn('project_id'); // Drop the post_id column
        });
    }
}
