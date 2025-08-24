<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->foreignIdFor(Project::class)->nullable();
            $table->foreignIdFor(User::class, 'referrer_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('zip');
            $table->string('type')->nullable();
            $table->string('budget');
            $table->string('phone')->nullable(); // Adding phone column from the second migration
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending'); // Adding status column
            $table->softDeletes(); // Adding soft deletes column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
