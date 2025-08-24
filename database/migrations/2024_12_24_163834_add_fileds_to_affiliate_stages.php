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
        Schema::table('affiliate_stages', function (Blueprint $table) {
            $table->decimal('commission_percentage', 5, 2)->comment('نسبة العمولة (Commission Percentage)');
            $table->decimal('monthly_profit_less_50k', 5, 2)->comment('نسبة الأرباح الشهرية على فريق برأس مال أقل من 50 ألف (%)');
            $table->decimal('monthly_profit_more_50k', 5, 2)->comment('نسبة الأرباح الشهرية على فريق برأس مال أكثر من 50 ألف (%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_stages', function (Blueprint $table) {
            $table->dropColumn(['commission_percentage', 'monthly_profit_less_50k', 'monthly_profit_more_50k']);
        });
    }
};
