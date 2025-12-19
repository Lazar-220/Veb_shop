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
        Schema::create('slika_tehnika', function (Blueprint $table) {
            $table->foreignId('slika_id')->constrained('slike')->cascadeOnDelete();
            $table->foreignId('tehnika_id')->constrained('tehnike')->cascadeOnDelete();
            $table->primary(['slika_id','tehnika_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slika_tehnika');
    }
};
