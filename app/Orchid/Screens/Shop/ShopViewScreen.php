<?php

namespace App\Orchid\Screens\Shop;

use App\Models\Shop\Shop;
use App\Orchid\Layouts\Charts\TopProductPieChart;
use App\Orchid\Layouts\Shop\PointListLayout;
use Carbon\Carbon;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ShopViewScreen extends Screen
{
    /**
     * @var Shop
     */
    public $shop;

    public function query(Shop $shop): iterable
    {
        $topProducts = $shop->products()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return $product->orders_count;
            });

        $labels = $shop->products()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return $product->title;
            })->all();

        $topProductsCharsData = [
            [
                'labels' => $labels,
                'name' => 'Часто приобретаемые продукты',
                'values' => $topProducts,
            ]
        ];

        $rarelyProductsCharsData = [
            [
                'labels' => $labels,
                'name' => 'Редко приобретаемые продукты',
                'values' => $shop->products()
                    ->withCount('orders')
                    ->orderBy('orders_count')
                    ->take(5)
                    ->get()
                    ->map(function ($product) {
                        return $product->orders_count;
                    }),
            ]
        ];

        return [
            'shop' => $shop,
            'points' => $shop->points,
            'topProducts' => $topProductsCharsData,
            'rarelyProducts' => $rarelyProductsCharsData,
        ];
    }

    public function name(): ?string
    {
        return 'Информация о магазине: ' . $this->shop->name;
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::view('admin.valueMetric', [
                'title' => 'Информация о магазине',
                'subtitle' => 'Информация о текущем магазина',
                'items' => $this->getShopStatistic()
            ]),

            Layout::columns([
                TopProductPieChart::make('topProducts', 'Часто покупаемые товары'),
                TopProductPieChart::make('rarelyProducts', 'Редко покупаемые товары'),
            ]),

            Layout::legend('shop', [
                Sight::make('id', '№'),
                Sight::make('name', 'Название магазина'),
                Sight::make('company_name', 'Компания'),
                Sight::make('Страны доставки')->render(fn(Shop $shop) => implode(', ', $shop->countries)),
                Sight::make('Ссылка на сайт')
                    ->render(fn(Shop $shop) => Link::make($shop->website)->href($shop->website)->target('blank')),
                Sight::make('Изображение')
                    ->render(fn(Shop $shop) => view('admin.thumbnail', ['image' => $shop->attachment->first()->url]))
            ]),


            PointListLayout::class,
        ];
    }

    private function getShopStatistic(): array
    {
        return [
            [
                'title' => 'Всего пунктов выдачи',
                'value' => $this->shop->points->count(),
            ],
            [
                'title' => 'Работающих пунктов выдачи',
                'value' => $this->shop->points->where('is_open', true)->count(),
                'statDays' => 7,
                'statValue' => $this->shop
                    ->points()
                    ->where('is_open', true)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDays(7))->count(),
            ],
            [
                'title' => 'Закрытых пунктов выдачи',
                'value' => $this->shop->points->where('is_open', false)->count(),
                'statDays' => 7,
                'statValue' => $this->shop
                    ->points()
                    ->where('is_open', true)
                    ->where('created_at', '>=', Carbon::now()
                        ->subDays(7))->count(),
            ],
        ];
    }


}
