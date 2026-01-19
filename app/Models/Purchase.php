<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\StatePayment;
use App\Enums\StatePurchase;
use App\Enums\StateReceiving;
use App\Observers\PurchaseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([PurchaseObserver::class])]
class Purchase extends Model
{
    protected $fillable = [
        'document_type',
        'supplier_id',
        'purchase_date',
        'total_amount',
        'discount',
        'payment_status',
        'receiving_status',
        'cash_summary_id',
        'transaction_code',
        'series',
        'status',
        'correlative',
        'notes',
    ];

    protected $casts = [
        'payment_status' => StatePayment::class,
        'receiving_status' => StateReceiving::class,
        'status' => StatePurchase::class,
        'document_type' => DocumentType::class,
    ];

    public function getSeriesCorrelativeAttribute(): string
    {
        return "{$this->series}-{$this->correlative}";
    }

    /**
     * 🔗 Compra pertenece a un proveedor
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * 🔗 Compra tiene muchos detalles
     */
    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function cashSummary()
    {
        return $this->belongsTo(CashSummary::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
