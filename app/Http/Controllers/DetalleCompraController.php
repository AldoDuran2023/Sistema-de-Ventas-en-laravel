<?php

namespace App\Http\Controllers;

use App\Models\compra;
use App\Models\detalle_compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
        $compra = Compra::find($request->id_compra);
        if (!$compra) {
            return response()->json(['error' => 'Compra no encontrada'], 404);
        };
        foreach ($request->productos as $producto) {
            Detalle_compra::create([
                'id_compra' => $compra->id,
                'id_producto' => $producto['id'],
                'cantidad' => $producto['cantidad'],
                'precio_compra' => $producto['precio']
            ]);
        };
        
        // Calcular el total de la compra
        $compra->total = detalle_compra::where('id_compra', $compra->id)->sum(DB::raw('cantidad * precio_compra'));
        $compra->save();

        return response()->json(['success' => 'Productos guardados correctamente']);
    }

    /**
     * Display the specified resource.
     */
    public function show(detalle_compra $detalle_compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(detalle_compra $detalle_compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, detalle_compra $detalle_compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(detalle_compra $detalle_compra)
    {
        //
    }
}
