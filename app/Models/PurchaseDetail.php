<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = [
        'purchase_id',
        'product_id',
        'presentation_id',
        'quantity',
        'cost',
        'discount', // opcional
        'subtotal', // opcional
    ];

    /**
     * 🔗 Detalle pertenece a una compra
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * 🔗 Detalle pertenece a un presentacion
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calcular subtotal automáticamente
     */
    public function calculateSubtotal(): float
    {
        $subtotal = ($this->price * $this->quantity);
        if ($this->discount) {
            $subtotal -= $this->discount;
        }
        return $subtotal;
    }
}
