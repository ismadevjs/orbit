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
        Schema::create('affiliate_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('الاسم (Name)');
            $table->text('description')->nullable()->comment('الوصف (Description)');
            $table->integer('duration')->comment('مدة العمل (Duration in months)');
            $table->decimal('capital', 15, 2)->comment('رأس المال (Capital)');
            $table->unsignedInteger('team_size')->comment('عدد أفراد الفريق (Team Size)');
            $table->unsignedInteger('people_per_six_months')->comment('عدد الأشخاص الذي يجب جلبهم كل 6 أشهر (People to bring every 6 months)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_stages');
    }
};
