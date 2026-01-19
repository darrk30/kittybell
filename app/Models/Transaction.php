<?php

namespace App\Models;

use App\Enums\StateTypeTransaction;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'amount',
        'transaction_type',
        'description',
        'cash_summary_id',
        'transaction_code',
        'transactionable_type', // 🔹 Tipo del modelo (Sale, Purchase, Spent)
        'transactionable_id',   // 🔹 ID del registro relacionado
    ];

    protected $casts = [
        'transaction_type' => StateTypeTransaction::class,
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function cash_summary()
    {
        return $this->belongsTo(CashSummary::class);
    }
}
