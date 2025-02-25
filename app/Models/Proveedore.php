<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedore extends Model
{
    //
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'imagen',
        'telefono',
        'direccion',
        'correo'
    ];

    // Accesor para obtener la imagen con la ruta completa
    public function getImagenUrlAttribute()
    {
        return asset('imagen/' . ($this->imagen ?? 'default.png'));
    }
}
