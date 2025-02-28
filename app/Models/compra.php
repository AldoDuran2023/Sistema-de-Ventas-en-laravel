<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compra extends Model
{
    //
    use HasFactory;
    
    protected $table = 'compras';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_proveedor',
        'fecha',
        'total'
    ];
    
    // Relación con el proveedor
    public function proveedor()
    {
        return $this->belongsTo(Proveedore::class, 'id_proveedor', 'id');
    }
    
    // Relación con detalles de compra
    public function detalles()
    {
        return $this->hasMany(Detalle_compra::class, 'id_compra', 'id');
    }
}
