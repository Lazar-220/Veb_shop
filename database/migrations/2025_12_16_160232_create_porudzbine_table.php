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
        Schema::create('porudzbine', function (Blueprint $table) {
            $table->id();    //user_id OBAVEZNO nullable()
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->date('datum');
            $table->decimal('ukupna_cena',15,2);
            $table->string('ime',30);
            $table->string('prezime',30);
            $table->enum('drzava',['Srbija'])->default('Srbija');
            $table->string('grad',30);
            $table->string('adresa',100);
            $table->string('postanski_broj',20);
            $table->string('telefon',30);
            $table->boolean('poslato')->default(true);//false
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porudzbine');
    }
};
