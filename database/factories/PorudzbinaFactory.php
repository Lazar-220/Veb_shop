<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Porudzbina>
 */
class PorudzbinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $korisnici=User::all()->pluck('id')->toArray();
        return [
            'user_id'=>$this->faker->randomElement($korisnici),
            'datum'=>$this->faker->dateTimeBetween('-1 year','now')->format('Y-m-d'),
            'ukupna_cena' => 0, // računa se kasnije
            'ime' => $this->faker->firstName,
            'prezime' => $this->faker->lastName,
            'drzava' => 'Srbija',
            'grad' => $this->faker->city,
            'adresa' => $this->faker->streetAddress,
            'postanski_broj' => $this->faker->postcode,
            'telefon' => $this->faker->phoneNumber,
            'poslato' => $this->faker->boolean(30),
        ];
    }
}
