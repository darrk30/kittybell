<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'presentation_id',
        'quantity',
        'price',
        'discount',
        'subtotal',
    ];

    /**
     * 🔗 Un detalle pertenece a una venta
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * 🔗 Un detalle pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 🔗 Un producto puede estar en muchas ventas a través de detalles
     */
    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_details')
            ->withPivot('quantity', 'price', 'discount', 'subtotal')
            ->withTimestamps();
    }
}
