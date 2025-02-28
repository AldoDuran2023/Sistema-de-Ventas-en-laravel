<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Producto;
use App\Models\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $proveedores = Proveedore::all();
        $productos = Producto::all();
        return view('templades.compra', compact('proveedores','productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * retorna los datos enlazados en formato json
     */
    public function flecht()
    {
        //
        try {
            $compras = Compra::with(['proveedor'])->get();
            return response()->json($compras,200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $compraData = $this->validateCompra($request);

        $compra = compra::create($compraData);

        return response()->json(['compra_id' => $compra->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(compra $compra)
    {
        //
    }

    public function reporteCompra($id)
    {
        $compra = Compra::with('proveedor', 'detalles.producto')->findOrFail($id);

        return view('compras.reporte', compact('compra'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validación (ajusta según tus campos)
            $validatedData = $request->validate([
                'id_proveedor' => 'required|exists:proveedores,id',
            ]);
    
            // Buscar la compra por ID
            $compra = Compra::findOrFail($id);
            
            // Actualizar con los datos validados
            $compra->update($validatedData);
            
            return response()->json(['success' => true, 'data' => $compra]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            $compra = Compra::findOrFail($id);
            // Llamamos al procedimiento almacenado
            DB::statement("CALL actualizar_stock_eliminar_compra(?)", [$compra->id]);
            $compra->delete();
    
            return response()->json(['message' => 'Compra eliminada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * validacion y preparacion de los datos.
     */
    public function validateCompra(Request $request, $id = null)
    {
        //
        $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id'. ($id ? ",$id":""),
        ]);

        $compraData = [
            'id_proveedor' => $request->id_proveedor,
            'fecha' => now(),
            'total' => 0
        ];

        return $compraData;
    }
}
