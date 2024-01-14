<?php

namespace App\Orchid\Layouts\Shop;

use App\Models\Shop\Employee;
use Illuminate\Support\Facades\Route;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EmployeeListLayout extends Table
{
    protected $target = 'employees';

    protected $title = 'Список сотрудников';

    protected function compact(): bool
    {
        return Route::is('platform.point.show');
    }

    protected function columns(): iterable
    {
        return [
            TD::make('Фотография')
                ->width('100')
                ->render(fn(Employee $employee) =>  view('admin.thumbnail', [
                    'image' => $employee->attachment->first()?->url,
                    'id' => $employee->id])
                ),
            TD::make('id', 'Идентификатор сотрудника')->sort(),
            TD::make('full_name', 'ФИО'),
            TD::make('point.name', 'Пункт выдачи'),
            TD::make('orders_count', 'Кол-во выполненных заказов')->sort(),

            TD::make('Действия')->render(fn(Employee $employee) => DropDown::make()
                ->icon('bs.three-dots-vertical')
                ->list([
                    Link::make('Подробнее')
                        ->icon('eye')
                        ->route('platform.employee.show', $employee),

                    ModalToggle::make(('Изменить'))
                        ->modal('editEmployeeModal')
                        ->method('updateEmployee')
                        ->asyncParameters([
                            'employee' => $employee->id
                        ])
                        ->icon('pencil')->canSee(Route::is('platform.employee.index')),

                    Button::make(('Delete'))
                        ->icon('trash')
                        ->confirm(('Вы действительно хотите удалить: ' . $employee->full_name))
                        ->canSee(Route::is('platform.employee.index'))
                        ->method('deleteEmployee', [
                            'employee' => $employee->id,
                        ])
                ])),
        ];
    }
}
