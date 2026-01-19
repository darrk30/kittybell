<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjustmentStockDetail extends Model
{
    protected $fillable = [
        'adjustment_stock_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * 🔗 Detalle pertenece a un ajuste maestro
     */
    public function adjustment()
    {
        return $this->belongsTo(AdjustmentStock::class, 'adjustment_stock_id');
    }

    /**
     * 🔗 Detalle pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
