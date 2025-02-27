<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function dashboard()
    {
        $totalMarcas = \App\Models\Marca::count();
        $totalCategorias = \App\Models\Categoria::count();
        $totalProductos = \App\Models\Producto::count();
        
        return view('templades.inicio', compact('totalMarcas', 'totalCategorias','totalProductos'));

    }

}

