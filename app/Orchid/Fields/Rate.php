<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Field;

class Rate extends Field
{
    protected $view = 'admin.fields.rate';

    protected $attributes = [
        'count' => 5,
        'step' => 1,
        'readonly' => false,
        'disabled' => false,
    ];

    protected $inlineAttributes = [
        'title',
        'name',
        'haveRated'
    ];
}
