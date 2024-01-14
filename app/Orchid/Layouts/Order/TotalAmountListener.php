<?php

namespace App\Orchid\Layouts\Order;

use App\Models\Product\Product;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

class TotalAmountListener extends Listener
{
    protected $targets = [
        'order_items.',
    ];


    /**
     * @throws BindingResolutionException
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([
                Relation::make('order_items[]')
                    ->fromModel(Product::class, 'title')
                    ->multiple()
                    ->title('Товары')
                    ->help('Общая сумма заказа: ' . $this->query->get('total_amount_result') . ' руб.'),

                Input::make('order.total_amount')
                    ->hidden()
                    ->value($this->query->get('total_amount_result'))
            ])
        ];
    }

    public function handle(Repository $repository, Request $request): Repository
    {
        $orderItemsIds = $request->input('order_items');

        $products = Product::query()->findMany($orderItemsIds);

        $totalAmount = $products->pluck('price', 'id')->values()->sum();

        return $repository
            ->set('order_items[]', $orderItemsIds)
            ->set('total_amount_result', $totalAmount);
    }
}
