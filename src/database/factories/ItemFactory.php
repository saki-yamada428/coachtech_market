<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Condition;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
            'user_id'      => User::inRandomOrder()->first()->id,
            'name'         => $faker->word(),
            'picture'      => 'png/box_danbo-ru_close.png',
            'brand'        => $faker->word(),
            'price'        => $faker->numberBetween(100,10000),
            'description'  => $faker->text(20),
            'condition_id' => Condition::inRandomOrder()->first()->id,
        ];
    }
}
