<?php

namespace App\Orchid\Screens\Order;

use App\Models\Order\Order;
use App\Orchid\Layouts\Order\OrderItemsListLayout;
use App\Orchid\Layouts\Product\ProductListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class OrderViewScreen extends Screen
{
    public $order;

    public function query(Order $order): iterable
    {
        $order->load(['point','items','items.product', 'items.product.attachment','employee',]);

        return [
            'order' => $order,
            'orderItems' => $order->items,
        ];
    }

    public function name(): ?string
    {
        return 'Заказ № ' . $this->order->id;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::legend('order', [
                Sight::make('id' , '#'),
                Sight::make('Сотрудник оформивший заказ')
                    ->render(fn (Order $order) => Link::make($order->employee->full_name)->route('platform.employee.show', $order->employee)),
                Sight::make('Пункт выдачи')
                    ->render(fn (Order $order) => Link::make($order->point->name)->route('platform.point.show', $order->point)),
                Sight::make('total_amount', 'Общая стоимость заказа')
            ])->title('Информация о заказе'),

            OrderItemsListLayout::class,
        ];
    }
}
