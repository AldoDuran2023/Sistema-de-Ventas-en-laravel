<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos'; // Nombre de la tabla en la BD

    protected $fillable = [
        'nombre', 
        'descripcion', 
        'id_marca', 
        'id_categoria', 
        'precio_venta',
        'estado', 
        'imagen'
    ];

    // Relación con la tabla marcas
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca', 'id'); 
    }

    // Relación con la tabla categorias
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id');
    }
}
