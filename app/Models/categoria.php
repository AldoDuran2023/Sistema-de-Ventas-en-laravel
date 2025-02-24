<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    //
    use HasFactory;

    protected $table = 'categorias'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre_categoria', // Campos permitidos para asignación masiva
    ];
}
