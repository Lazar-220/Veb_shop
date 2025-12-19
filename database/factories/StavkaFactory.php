<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Slika;
use App\Models\Porudzbina;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stavka>
 */
class StavkaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kolicina' => 1,
            // cena, porudzbina_id, slika_id i rb ide iz seedera
        ];
    }
}
