<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEncryptedTokenColumnLengthInReferralLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_links', function (Blueprint $table) {
            // Drop any index on encrypted_token (adjust based on your schema)
            $table->dropUnique('referral_links_encrypted_token_unique'); // Example: replace with actual index name if different
            
            // Change encrypted_token to TEXT
            $table->text('encrypted_token')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_links', function (Blueprint $table) {
            // Revert to original VARCHAR(255) and re-add the index
            $table->string('encrypted_token', 255)->change();
            $table->unique('encrypted_token', 'referral_links_encrypted_token_unique');
        });
    }
}