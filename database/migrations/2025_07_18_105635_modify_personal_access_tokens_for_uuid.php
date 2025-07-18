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
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop index terlebih dahulu
            $table->dropIndex(['tokenable_type', 'tokenable_id']);
            
            // Ubah tokenable_id menjadi UUID (string dengan length 36)
            $table->string('tokenable_id', 36)->change();
            
            // Buat ulang index
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Drop index
            $table->dropIndex(['tokenable_type', 'tokenable_id']);
            
            // Kembalikan ke bigInteger
            $table->bigInteger('tokenable_id')->change();
            
            // Buat ulang index
            $table->index(['tokenable_type', 'tokenable_id']);
        });
    }
};