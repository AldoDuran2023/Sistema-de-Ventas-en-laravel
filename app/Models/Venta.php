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

    public function detalles()
    {
        return $this->hasMany(Detalle_Venta::class, 'id_venta', 'id');
    }
}
