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
        Schema::table('slike', function (Blueprint $table) {
            $table->integer('visina_cm')->change();
            $table->integer('sirina_cm')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slike', function (Blueprint $table) {
            $table->decimal('visina_cm',15,2)->change();
            $table->decimal('sirina_cm',15,2)->change();
        
        });
    }
};
