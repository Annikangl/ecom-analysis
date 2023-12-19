<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'point_id' => Point::inRandomOrder()->value('id'),
            'full_name' => $this->faker->name(),
            'birthdate' => $this->faker->dateTime(),
            'phone' => $this->faker->phoneNumber(),
            'passport_series' => $this->faker->numberBetween(15000, 999999),
            'address' => $this->faker->address(),
            'employment_date' => $emplDate = Carbon::now()->subDays(100),
            'dismissal_date' => $this->faker->randomElement([
                null,
                $emplDate->addDays(30),
            ]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
