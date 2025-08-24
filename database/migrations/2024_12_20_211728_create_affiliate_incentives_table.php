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
        Schema::create('incentives', function (Blueprint $table) {
            $table->id();
            // Associate incentive with AffiliateStage
            $table->foreignId('affiliate_stage_id')->nullable()->constrained('affiliate_stages')->onDelete('cascade')->comment('مستوى التسويق (Affiliate Stage)');
           

            // Bonus Type: سنوي (Yearly) or شهري (Monthly)
            $table->enum('bonus_type', ['yearly', 'monthly'])->comment('نوع البونص: سنوي أو شهري');

            // Date fields for Monthly bonuses
            $table->date('from_date')->nullable()->comment('من تاريخ (From Date) للبونص الشهري');
            $table->date('to_date')->nullable()->comment('إلى تاريخ (To Date) للبونص الشهري');
            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incentives');
    }
};
