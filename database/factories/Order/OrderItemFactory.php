<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'quantity' => $this->faker->randomNumber(),
            'price' => random_int(1000,99999),
        ];
    }
}
