<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Order\Order;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    protected $target = 'orders';

    protected $title = 'Список заказов';

    protected function compact(): bool
    {
        return true;
    }

    protected function columns(): iterable
    {
        return [
            TD::make('id', '#'),
            TD::make('point.name', 'Пункт выдачи'),
            TD::make('employee.full_name', 'Кто оформил'),
            TD::make('total_amount', 'Общая стоимость, руб'),
            TD::make('created_at', 'Дата оформления'),
            TD::make('Действия')->render(fn(Order$order) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.order.show', $order),

//                    ModalToggle::make(('Изменить'))
//                        ->modal('editOrderModal')
//                        ->method('updateOrder')
//                        ->asyncParameters([
//                            'order' => $order->id
//                        ])
//                        ->icon('pencil'),
                ])),
        ];
    }
}
