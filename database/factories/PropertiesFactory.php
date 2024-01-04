<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\{User, Catigories, Cities};
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Properties>
 */
class PropertiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'=>User::all()->random()->id,
            'catigorey_id'=>Catigories::all()->random()->id,
            'city_id'=>Cities::all()->random()->id,
            'title'=>$this->faker->unique()->word,
            'description'=>fake()->paragraph(4),
            'price'=>fake()->numberBetween(1000,10000),
            'area'=>fake()->numberBetween(100,1000),
            'bedroom'=>fake()->numberBetween(1,5),
            'bathroom'=>fake()->numberBetween(1,5),
            'kitchen'=>fake()->numberBetween(1,5),
            'garage'=>fake()->numberBetween(1,5),
            'address'=>fake()->address(),
            
        ];
    }
}
