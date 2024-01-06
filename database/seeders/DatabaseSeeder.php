<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        Point::factory(30)->create();
//        Employee::factory(50)->create();
//        ProductCategory::factory(15)->create();
//        Product::factory(30)->create();
        Order::factory(20)->create();
        OrderItem::factory(50)->create();
    }
}
