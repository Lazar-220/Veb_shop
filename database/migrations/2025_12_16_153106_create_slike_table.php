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
        Schema::create('slike', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galerija_id')->constrained('galerija')->nullOnDelete()->nullable();
            $table->string('putanja_fotografije');
            $table->decimal('cena',15,2);
            $table->string('naziv',50);
            $table->string('tehnika',50);
            $table->decimal('visina_cm',15,2);
            $table->decimal('sirina_cm',15,2);//integer stavi
            $table->boolean('dostupna')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slike');
    }
};
