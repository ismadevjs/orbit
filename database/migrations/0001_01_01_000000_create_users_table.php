<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('active')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        // Ensure foreign key constraint is created in the sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Session ID as primary key
            $table->foreignId('user_id')->nullable()->constrained()->index(); // Foreign key to users table
            $table->string('ip_address', 45)->nullable(); // Nullable IP address
            $table->text('user_agent')->nullable(); // Nullable user agent
            $table->longText('payload'); // Payload for session data
            $table->integer('last_activity')->index(); // Last activity timestamp
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Primary key on email
            $table->string('token'); // Reset token
            $table->timestamp('created_at')->nullable(); // Nullable created timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions'); // Drop sessions table first due to foreign key constraint
        Schema::dropIfExists('password_reset_tokens');
    }
};

