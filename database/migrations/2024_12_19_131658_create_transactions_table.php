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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('payment_method', 50);
            $table->string('transaction_reference')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 10);
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->json('details')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->string('payment_account')->nullable();
            $table->decimal('payment_gateway_fee', 15, 2)->nullable();
            $table->boolean('is_test')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
