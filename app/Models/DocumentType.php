<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'max_length',
        'is_active',
    ];

    /**
     * 🔗 Un tipo de documento puede tener muchos clientes
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * 🔗 Un tipo de documento puede tener muchos proveedores
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
