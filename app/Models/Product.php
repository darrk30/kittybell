<?php

namespace App\Models;

use App\Enums\StateProduct;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'price',
        'cost',
        'stock',
        'category_id',
        'brand_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => StateProduct::class,
    ];

    /**
     * 🔗 Producto puede tener muchas presentaciones
     */
    public function presentations()
    {
        return $this->hasMany(Presentation::class);
    }

    /**
     * 🔗 Un producto puede pertenecer a muchas categorías
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    /**
     * 🔗 Un producto pertenece a una marca
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * 🔗 Presentación puede estar en muchos detalles de venta
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    /**
     * 🔗 Presentación puede estar en muchos detalles de compra
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * 🔗 Presentación puede estar en muchos ajustes de stock
     */
    public function adjustmentStockDetails()
    {
        return $this->hasMany(AdjustmentStockDetail::class);
    }

    /**
     * 🔗 Movimientos de stock relacionados a esta presentación
     */
    public function movements()
    {
        return $this->hasMany(MovementStock::class, 'product_id'); // aquí product_id apunta a la presentación
    }
}
