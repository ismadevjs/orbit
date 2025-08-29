<?php
// database/migrations/xxxx_xx_xx_create_languages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // en, fr
            $table->string('name');              // English, Français
            $table->timestamps();
        });
    }
public function down(): void {
        Schema::dropIfExists('languages');
    }
};
