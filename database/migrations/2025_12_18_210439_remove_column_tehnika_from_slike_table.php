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
            $table->dropColumn('tehnika');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slike', function (Blueprint $table) {
            $table->string('tehnika')->after('naziv');
        });
    }
};
