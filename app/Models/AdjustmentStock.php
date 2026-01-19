<?php

namespace App\Models;

use App\Enums\MovementStockType;
use Illuminate\Database\Eloquent\Model;

class AdjustmentStock extends Model
{
    protected $fillable = [
        'date',
        'movement_type',
        'motive',
        'notes',
    ];

    protected $casts = [
        'movement_type' => MovementStockType::class,
    ];
    /**
     * 🔗 Un ajuste maestro tiene muchos detalles
     */
    public function details()
    {
        return $this->hasMany(AdjustmentStockDetail::class);
    }
}
