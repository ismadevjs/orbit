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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم
            $table->text('description')->nullable(); // الوصف
            $table->decimal('min_amount', 10, 2); // المبلغ الأدنى
            $table->decimal('percentage', 5, 2); // النسبة
            // $table->decimal('bonus', 10, 2)->nullable(); // البونص
            $table->text('features')->nullable(); // الخصائص (stored as JSON)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_plans');
    }

};
