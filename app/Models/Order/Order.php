<?php

namespace App\Models\Order;

use App\Models\Shop\Employee;
use App\Models\Shop\Point;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'point_id',
        'employee_id',
        'total_amount',
        'created_at',
        'updated_at'
    ];

    public function point(): BelongsTo
    {
        return $this->belongsTo(Point::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
