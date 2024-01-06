<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class ProductCategory extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'parent_id',
        'name'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class,'parent_id');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
