<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'point_id' => Point::query()->inRandomOrder()->value('id'),
            'employee_id' => Employee::query()->inRandomOrder()->value('id'),
            'total_amount' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
