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
        Schema::table('affiliate_stages', function (Blueprint $table) {
            if (!Schema::hasColumn('affiliate_stages', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('team_size');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('affiliate_stages', function (Blueprint $table) {
            if (Schema::hasColumn('affiliate_stages', 'role_id')) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
        });
    }
};
