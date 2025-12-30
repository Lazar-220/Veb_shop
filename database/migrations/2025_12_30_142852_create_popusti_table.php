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
        Schema::create('popusti', function (Blueprint $table) {
            $table->id();
            $table->boolean('aktivan')->default(true);
            $table->string('tip',50);
            $table->integer('procenat')->unsigned();
            $table->tinyInteger('danOd')->unsigned();;
            $table->tinyInteger('danDo')->unsigned();;
            $table->tinyInteger('mesecOd')->unsigned();;
            $table->tinyInteger('mesecDo')->unsigned();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popusti');
    }
};
