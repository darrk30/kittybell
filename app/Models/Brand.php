<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * 🔗 Una marca tiene muchos productos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
