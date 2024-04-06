<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\ProductCategory;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use App\Models\Shop\Shop;
use App\Orchid\Layouts\Charts\OrdersByDateLineChart;
use App\Orchid\Layouts\Shop\EmployeeListLayout;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'ordersDynamic' => [
                Order::query()->countByDays(
                    Carbon::now()->subDays(30),
                    Carbon::now(),
                    'created_at'
                )->toChart('Заказов')
            ],

            'averageByDays' => [
                Order::query()->averageByDays('total_amount')->toChart('Средняя стоимость заказа'),
                Order::query()->minByDays('total_amount')->toChart('Минимальная стоимость заказа'),
                Order::query()->maxByDays('total_amount')->toChart('Максимальная стоимость заказа'),
            ],

            'bestEmployeesByWeek' => Employee::query()
                ->with(['attachment', 'point'])
                ->withCount(['orders' => fn($query) => $query->whereBetween('created_at', [
                    Carbon::now()->subDays(30),
                    Carbon::now()->now()->subDay()
                ])])
                ->orderBy('orders_count', 'desc')
                ->take(5)
                ->get(),

            'bestPointsByWeek' => Point::query()
                ->with(['shop'])
                ->withCount(['orders' => fn($query) => $query->whereBetween('created_at', [
                    Carbon::now()->subDays(30),
                    Carbon::now()->now()->subDay()
                ])])
                ->orderBy('orders_count', 'desc')
                ->take(5)
                ->get(),

            'bestProductCategories' => $this->bestProductCategories()
        ];
    }

    /**
     * Get order items where product category max count
     */

    public function bestProductCategories()
    {
        // Получаем количество заказанных товаров по категориям
        $categoryCounts = OrderItem::query()
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->select('product_categories.name', DB::raw('COUNT(order_items.id) as order_count'))
            ->groupBy('product_categories.name')
            ->orderByDesc('order_count')
            ->get();

        return $categoryCounts->take(5);
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Добро пожаловать';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Панель аналитики сервисов электронной коммерции';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('admin.valueMetric', [
                'title' => 'Общие показатели',
                'subtitle' => 'кол-во объектов анализа',
                'items' => [
                    [
                        'title' => 'Количество объектов анализа',
                        'value' => array_sum([
                            Shop::count(),
                            Point::count(),
                            Employee::count(),
                        ]),
                    ],
                    [
                        'title' => 'Магазинов',
                        'value' => Shop::count(),
                    ],
                    [
                        'title' => 'Пунктов выдачи',
                        'value' => Point::count(),
                    ],
                    [
                        'title' => 'Сотрудников',
                        'value' => Employee::count(),
                    ],
                ]
            ]),

            Layout::columns([
                OrdersByDateLineChart::make('ordersDynamic', 'Динамика заказов за последние 30 дней'),
                OrdersByDateLineChart::make('averageByDays', 'Динамика стоимости заказов за последние 30 дней'),
            ]),

            Layout::table('bestEmployeesByWeek', [
                TD::make('Фотография')
                    ->width('100')
                    ->render(fn(Employee $employee) => view('admin.thumbnail', [
                        'image' => $employee->attachment->first()?->url,
                        'id' => $employee->id,
                        'width' => 50
                    ])
                    ),
                TD::make('id', '№')->sort(),
                TD::make('full_name', 'ФИО'),
                TD::make('orders_count', 'Выполнено заказов'),
            ])->title('Лучшие сотрудники месяца'),


            Layout::table('bestPointsByWeek', [
                TD::make('Логотип')
                    ->width('100')
                    ->render(fn(Point $point) => view('admin.thumbnail', [
                        'image' => $point->load('shop')->shop->attachment->first()->url ?? '',
                        'id' => $point->id])
                    ),
                TD::make('shop.name', 'Магазин'),
                TD::make('name', 'Пункт'),
                TD::make('orders_count', 'Выполнено заказов'),
            ])->title('Лучшие пункты выдачи месяца'),

            Layout::table('bestProductCategories', [
                TD::make('name', 'Категория'),
                TD::make('order_count', 'Кол-во заказов'),
            ])->title('Топ 5 популярных категории товаров')
        ];
    }
}
