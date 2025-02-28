<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Venta extends Model
{
    //
    use HasFactory;
    
    protected $table = 'detalle_ventas';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_unitario'
    ];
    
    // Relación con la venta
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta', 'id');
    }
    
    // Relación con el producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id');
    }
    
    // Calcular subtotal
    public function getSubtotalAttribute()
    {
        return $this->cantidad * $this->precio_unitario;
    }
}
