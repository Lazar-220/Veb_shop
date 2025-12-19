<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use App\Models\Galerija;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Slika>
 */
class SlikaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fotografije=Storage::disk('public')->allFiles('fotografije');
        return [
            'galerija_id'=>Galerija::pluck('id')->first(), //Galerija::orderBy('id')->value('id')
            'putanja_fotografije'=>count($fotografije)===0 ? 'fotografije/default.jpg' : $this->faker->randomElement($fotografije),
            'cena'=>$this->faker->randomFloat(2,3000,15000),
            'naziv'=>$this->faker->word(),
            'visina_cm'=>$this->faker->numberBetween(60,100),
            'sirina_cm'=>$this->faker->numberBetween(60,100),
            'dostupna'=>$this->faker->boolean(80)
        ];
    }
}
