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
        Schema::table('porudzbine', function (Blueprint $table) {
            
            // prvo skidamo strani ključ
            $table->dropForeign(['user_id']);
       
            // menjamo kolonu da bude nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // ponovo dodajemo strani ključ
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('porudzbine', function (Blueprint $table) {
            
            $table->dropForeign(['user_id']);
        
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });
    }
};
