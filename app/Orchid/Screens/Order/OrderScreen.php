<?php

namespace App\Orchid\Screens\Order;

use App\Http\Requests\admin\Order\OrderRequest;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\Product;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use App\Orchid\Layouts\Order\OrderListLayout;
use App\Orchid\Layouts\Order\TotalAmountListener;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\RedirectResponse;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

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
        return [
            ModalToggle::make('Создать заказ')
                ->modal('createOrderModal')
                ->method('createOrder')
                ->icon('bs.plus-circle'),
        ];
    }

    /**
     * @throws BindingResolutionException
     */
    public function layout(): iterable
    {
        return [
            OrderListLayout::class,

            Layout::modal('createOrderModal', [
                Layout::rows([
                    Relation::make('order.point_id')
                        ->fromModel(Point::class, 'name')
                        ->title('Выберите пункт выдачи')
                        ->help('Пункт выдачи на которой будет осуществлена доставка заказа'),
                    Relation::make('order.employee_id')
                        ->fromModel(Employee::class, 'full_name')
                        ->title('Выберите сотрудника')
                        ->help('Сотрудник, который оформляет заказ'),
                ]),

                TotalAmountListener::class,
            ])->title('Создание заказа')
                ->applyButton('Создать'),
        ];
    }

    public function createOrder(OrderRequest $request): RedirectResponse
    {
        $products = Product::query()->findMany($request->validated('order_items'));
        $orderItems = [];

        $order = Order::create([
            'employee_id' => $request->validated('order.employee_id'),
            'point_id' => $request->validated('order.point_id'),
            'total_amount' => $request->validated('order.total_amount'),
        ]);

        foreach ($products as $product) {
            $orderItems[] = new OrderItem([
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => 1,
            ]);
        }

        $order->items()->saveMany($orderItems);

        Toast::success('Заказ создан успешно!');

        return redirect()->back();
    }
}
