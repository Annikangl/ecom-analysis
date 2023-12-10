<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Point;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PointFactory extends Factory
{
    protected $model = Point::class;

    public function definition(): array
    {
        return [
            'shop_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'schedule' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'is_open' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
