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
            // $table->foreignId('popust_id')->constrained('popusti')->restrictOnDelete()->nullable()->after('user_id');
            $table->foreignId('popust_id')
            ->nullable()
            ->after('user_id');
            $table->decimal('konacna_cena',15,2)->unsigned()->after('ukupna_cena');
            $table->tinyInteger('procenat_popusta_ss')->unsigned()->after('poslato');
            $table->string('tip_popusta_ss',50)->after('procenat_popusta_ss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('porudzbine', function (Blueprint $table) {
            $table->dropColumn(['popust_id','konacna_cena','procenat_popusta_ss','tip_popusta_ss']);
        });
    }
};
