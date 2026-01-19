<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\StateSale;
use App\Observers\SaleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([SaleObserver::class])]
class Sale extends Model
{
    protected $fillable = [
        'client_id',
        'delivery_type_id',
        'cash_summary_id',
        'transaction_code',
        'document_type',
        'total_amount',
        'discount',
        'status',
        'series',
        'correlative',
        'notes',
    ];

    protected $casts = [
        'document_type' => DocumentType::class,
        'status' => StateSale::class,
    ];

    public function getSeriesCorrelativeAttribute(): string
    {
        return "{$this->series}-{$this->correlative}";
    }

    /**
     * 🔗 Una venta pertenece a un cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * 🔗 Una venta tiene un tipo de delivery
     */
    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class);
    }

    /**
     * 🔗 Una venta tiene muchos detalles
     */
    public function details()
    {
        return $this->hasMany(SaleDetail::class);
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
