<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'document_number',
        'email',
        'phone',
        'address',
        'is_active',
        'document_type_id',
    ];

    /**
     * 🔗 Un cliente pertenece a un tipo de documento
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * 🔗 Un cliente puede tener muchas ventas
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
