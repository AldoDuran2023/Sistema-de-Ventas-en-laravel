<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    //
    use HasFactory;

    protected $table = 'Ventas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_cliente',
        'fecha',
        'total'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($venta) {
            // Obtener el último id_cliente generado
            $ultimoCliente = Venta::latest('id')->value('id_cliente');

            if ($ultimoCliente) {
                // Extraer el número y sumarle 1
                $numero = (int) substr($ultimoCliente, 1) + 1;
            } else {
                $numero = 1; // Si no hay registros previos
            }

            // Formatear el nuevo id_cliente
            $venta->id_cliente = 'N°' . str_pad($numero, 11, '0', STR_PAD_LEFT);
        });
    }

    public function detalles()
    {
        return $this->hasMany(Detalle_Venta::class, 'id_venta', 'id');
    }
}
