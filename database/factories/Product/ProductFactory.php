<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Shop\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'shop_id' => Shop::query()->inRandomOrder()->value('id'),
            'product_category_id' => ProductCategory::query()->inRandomOrder()->value('id'),
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomNumber(),
            'sale_price' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
