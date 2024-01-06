<?php

declare(strict_types=1);

use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use App\Models\Shop\Shop;
use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Order\OrderScreen;
use App\Orchid\Screens\Order\OrderViewScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Product\CategoryScreen;
use App\Orchid\Screens\Product\CategoryViewScreen;
use App\Orchid\Screens\Product\ProductScreen;
use App\Orchid\Screens\Product\ProductViewScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Shop\EmployeeScreen;
use App\Orchid\Screens\Shop\EmployeeViewScreen;
use App\Orchid\Screens\Shop\PointScreen;
use App\Orchid\Screens\Shop\PointViewScreen;
use App\Orchid\Screens\Shop\ShopScreen;
use App\Orchid\Screens\Shop\ShopViewScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;


// Main


Route::name('platform.')->group(function () {
// Main
    Route::screen('/main', PlatformScreen::class)
        ->name('main');


    // Platform -> Shop

    Route::group(['prefix' => 'shop', 'as' => 'shop.'], function () {
        Route::screen('/', ShopScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Магазины'), route('platform.shop.index')));
        Route::screen('/{shop}', ShopViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, $shop) => $trail
                ->parent('platform.shop.index')
                ->push($shop->name, route('platform.shop.show', $shop)));
    });

    // Platform -> Points

    Route::group(['prefix' => 'point', 'as' => 'point.'], function () {
        Route::screen('/', PointScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Пункты выдачи'), route('platform.point.index')));
        Route::screen('/{point}', PointViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, Point $point) => $trail
                ->parent('platform.point.index')
                ->push($point->name, route('platform.point.show', $point)));
    });

    // Platform - Employee
    Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
        Route::screen('/', EmployeeScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Сотрудники'), route('platform.employee.index')));
        Route::screen('/{employee}', EmployeeViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, Employee $employee) => $trail
                ->parent('platform.employee.index')
                ->push($employee->full_name, route('platform.employee.show', $employee)));
    });

    // Platform > Products > Category

    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::screen('/', CategoryScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Категории товаров'), route('platform.category.index')));

        Route::screen('/{productCategory}', CategoryViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, ProductCategory $category) => $trail
                ->parent('platform.category.index')
                ->push($category->name, route('platform.category.show', $category)));
    });

    // Platform > Products

    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
        Route::screen('/', ProductScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Список товаров'), route('platform.product.index')));

        Route::screen('/{product}', ProductViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, Product $product) => $trail
                ->parent('platform.product.index')
                ->push($product->title, route('platform.product.show', $product)));
    });

    // Platform > Orders

    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        Route::screen('/', OrderScreen::class)->name('index')
            ->breadcrumbs(fn(Trail $trail) => $trail
                ->parent('platform.index')
                ->push(__('Список заказов'), route('platform.order.index')));

        Route::screen('/{order}', OrderViewScreen::class)->name('show')
            ->breadcrumbs(fn(Trail $trail, Order $order) => $trail
                ->parent('platform.order.index')
                ->push($order->id, route('platform.order.show', $order)));
    });
});


// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/form/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/examples/form/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/examples/form/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/examples/form/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/examples/grid', ExampleGridScreen::class)->name('platform.example.grid');
Route::screen('/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');

//Route::screen('idea', Idea::class, 'platform.screens.idea');
