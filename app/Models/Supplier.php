<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'document_type_id',
        'document_number',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * 🔗 Proveedor puede tener un tipo de documento
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * 🔗 Proveedor puede tener muchas compras
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
