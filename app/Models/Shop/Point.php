<?php

namespace App\Models\Shop;

use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Point extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'address',
        'schedule',
        'phone',
        'is_open',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function employee(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
