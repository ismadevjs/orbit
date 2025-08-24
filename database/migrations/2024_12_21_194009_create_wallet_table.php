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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links wallet to a user
            $table->decimal('capital', 15, 2)->default(0); // User's capital
            $table->decimal('bonus', 15, 2)->default(0); // Bonus amount
            $table->decimal('profit', 15, 2)->default(0); // Profit amount
            $table->decimal('pending_capital', 15, 2)->default(0); // Profit amount
            $table->decimal('pending_bonus', 15, 2)->default(0); // Profit amount
            $table->decimal('pending_profit', 15, 2)->default(0); // Profit amount
            $table->boolean('is_locked')->default(false); // Indicates if the wallet is locked
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};
