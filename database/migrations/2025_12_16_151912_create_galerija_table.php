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
        Schema::create('galerija', function (Blueprint $table) {
            $table->id();
            $table->string('naziv',30);
            $table->string('adresa',100);
            $table->decimal('longitude',15,6); 
            $table->decimal('latitude',15,6);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galerija');
    }
};
