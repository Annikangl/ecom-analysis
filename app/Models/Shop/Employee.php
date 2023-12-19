<?php

namespace App\Models\Shop;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Employee extends Model
{
    use HasFactory, AsSource, Attachable, Filterable;

    protected $fillable = [
        'point_id',
        'full_name',
        'birthdate',
        'passport_series',
        'phone',
        'address',
        'employment_date',
        'dismissal_date',
        'created_at',
        'updated_at',
    ];

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
