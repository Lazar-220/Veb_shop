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
            $table->foreign('popust_id') //ovo je kad menjamo fk, za dodavanje se koristi foreignId->constrained
            ->references('id')
            ->on('popusti')
            ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('porudzbine', function (Blueprint $table) {
            $table->dropForeign(['porudzbine_popust_id_foreign']);
        });
    }
};
