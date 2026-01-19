<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashSummary extends Model
{
    protected $fillable = ['current_balance', 'code', 'name'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

     public function sale()
    {
        return $this->hasMany(Sale::class);
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    public function spent()
    {
        return $this->hasMany(Spent::class);
    }
}
