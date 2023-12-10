<?php

namespace App\Models\Shop;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'website',
        'company_name',
        'countries',
    ];

    protected $casts = [
        'countries' => 'array',
    ];

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
