<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Galerija;
use App\Models\Porudzbina;
use App\Models\Slika;
use App\Models\Tehnika;
use App\Models\Stavka;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //ovo sluzi da obriseo stare podatke prilikom kreiranja novih
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); //ova linija je da se iskljuci provera FK
        DB::table('slika_tehnika')->truncate();
        User::truncate();
        Galerija::truncate();
        Porudzbina::truncate();
        Stavka::truncate();
        Slika::truncate();
        Tehnika::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::factory(10)->create();

        Galerija::factory()->create();

        $tehnike=Tehnika::factory(7)->create();
        $slike=Slika::factory(30)->create();
        foreach($slike as $slika){
            $slika->tehnike()->attach(
                $tehnike->random(rand(1,3))->pluck('id')->toArray()
            );
        }
        // tehnike()->attach([1,4,2]) ubacuje u pivot tabelu 1 slika_id i niz tehnika_id zahvaljujuci fji:
        // public function tehnike(){
        //     return $this->belongsToMany(Tehnika::class,'slika_tehnika','slika_id','tehnika_id');
        // }
        $slike=Slika::all();
        $porudzbine=Porudzbina::factory(20)->create();
        foreach($porudzbine as $porudzbina){

            $brojStavki=rand(1,5);
            $ukupno=0;

            $izabraneSlike=$slike->random($brojStavki);

            foreach($izabraneSlike as $index => $slika){
                $stavka=Stavka::factory()->create([
                    'porudzbina_id'=>$porudzbina->id,
                    'slika_id'=>$slika->id,
                    'rb'=>$index+1,
                    'cena'=>$slika->cena
                ]);

                $ukupno+=$stavka->cena*$stavka->kolicina;
            }

            $porudzbina->update([
                'ukupna_cena'=>$ukupno
            ]);
        }



        // $slike = Slika::all();

        // if ($slike->isEmpty()) {
        //     $this->command->warn('Nema slika u bazi!');
        //     return;
        // }

        // Porudzbina::factory()
        //     ->count(10)
        //     ->create()
        //     ->each(function ($porudzbina) use ($slike) {

        //         $brojStavki = rand(1, 5);
        //         $ukupno = 0;

        //         // nasumične, ali postojeće slike
        //         $izabraneSlike = $slike->random($brojStavki);

        //         foreach ($izabraneSlike as $index => $slika) {

        //             $stavka = Stavka::factory()->create([
        //                 'porudzbina_id' => $porudzbina->id,
        //                 'slika_id' => $slika->id,
        //                 'rb' => $index + 1,
        //                 'cena' => $slika->cena,
        //             ]);

        //             $ukupno += $stavka->cena * $stavka->kolicina;
        //         }

        //         $porudzbina->update([
        //             'ukupna_cena' => $ukupno
        //         ]);
        //     });


    }
}
