<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSalesSummary extends Model
{
    protected $fillable = [
        'product_id',
        'total_sold',
        'total_revenue',
    ];

    /**
     * 🔗 Un resumen pertenece a un producto
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
