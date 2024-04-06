<?php

namespace Database\Factories\Shop;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'website' => $this->faker->url(),
            'company_name' => $this->faker->company(),
            'countries' => ['US', 'CA', 'UK', 'AU'],
        ];
    }
}
