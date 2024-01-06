<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'parent_id' => $this->faker->randomElement([
                ProductCategory::query()->inRandomOrder()->value('id'),
                null,
            ]),
            'name' => $this->faker->name(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
