<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryType extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'extra_price',
    ];

    /**
     * 🔗 Un tipo de delivery puede estar en muchas ventas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
