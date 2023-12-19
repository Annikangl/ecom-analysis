<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2),
            'website' => $this->faker->url(),
            'company_name' => $this->faker->words(),
            'countries' => $this->faker->country(),
        ];
    }
}
