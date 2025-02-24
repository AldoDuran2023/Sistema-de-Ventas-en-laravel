<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class marca extends Model
{
    //
    use HasFactory;

    protected $table = 'marcas'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre_marca', // Campos permitidos para asignación masiva
    ];
}
