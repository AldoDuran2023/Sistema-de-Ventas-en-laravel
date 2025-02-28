<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalle_compra extends Model
{
    //
    use HasFactory;
    
    protected $table = 'detalle_compras';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_compra',
        'id_producto',
        'cantidad',
        'precio_compra'
    ];
    
    // Relación con la compra
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'id_compra', 'id');
    }
    
    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }
    
    // Calcular subtotal
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio_compra;
    }
}
