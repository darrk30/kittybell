<?php

namespace App\Models;

use App\Observers\SpentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SpentObserver::class])]
class Spent extends Model
{
    protected $fillable = [
        'name',        // Nombre o concepto del gasto
        'amount',      // Monto del gasto
        'date',  // Fecha del gasto
        'description',       // Notas adicionales
        'is_active',   // Activo/Inactivo
        'cash_summary_id',
        'transaction_code',
    ];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function cashSummary()
    {
        return $this->belongsTo(CashSummary::class);
    }
}
