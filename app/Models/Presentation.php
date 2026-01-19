<?php

namespace App\Models;

use App\Enums\StateProduct;
use App\Observers\PresentationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PresentationObserver::class])]
class Presentation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => StateProduct::class,
    ];
    /**
     * 🔗 Presentación pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   
}
