<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ja_JP');

        return [
            'user_id'     => User::inRandomOrder()->first()->id,
            'nickname'    => $faker->word(),
            'picture'     => 'png/ダミー用プロフィール画像.png',
            'postal_code' => $faker->randomNumber(7),
            'address'     => $faker->address(),
            'building'    => $faker->text(20),
        ];
    }
}
