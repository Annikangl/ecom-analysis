<?php

namespace App\Models\Shop;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_id',
        'full_name',
        'birthdate',
        'passport_series',
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
