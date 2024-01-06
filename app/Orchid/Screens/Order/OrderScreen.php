<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order\Order;
use App\Orchid\Layouts\Order\OrderListLayout;
use Orchid\Screen\Screen;

class OrderScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'orders' => Order::query()
                ->with(['point', 'point.shop', 'employee', 'items'])
                ->latest()
                ->paginate(25),
        ];
    }

    public function name(): ?string
    {
        return 'Список заказов';
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            OrderListLayout::class,
        ];
    }
}
