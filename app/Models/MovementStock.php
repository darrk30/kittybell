<?php

namespace App\Models;

use App\Enums\MovementStockType;
use Illuminate\Database\Eloquent\Model;

class MovementStock extends Model
{
    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'balance',
        'purchase_id',
        'sale_id',
        'adjustment_stock_id', // vínculo a ajuste maestro
    ];

    protected $casts = [
        'movement_type' => MovementStockType::class,
    ];

    /**
     * 🔗 Movimiento pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * 🔗 Movimiento opcional de compra
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * 🔗 Movimiento opcional de venta
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * 🔗 Movimiento opcional relacionado a un ajuste maestro
     */
    public function adjustment()
    {
        return $this->belongsTo(MovementStock::class, 'adjustment_stock_id');
    }

    /**
     * 🔗 Movimientos hijos que pertenecen a este ajuste maestro
     */
    public function adjustmentChildren()
    {
        return $this->hasMany(MovementStock::class, 'adjustment_stock_id');
    }

}
