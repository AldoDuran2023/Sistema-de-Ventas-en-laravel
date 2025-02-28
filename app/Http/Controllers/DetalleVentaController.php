<?php

namespace App\Http\Controllers;

use App\Models\Detalle_Venta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;

class DetalleVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        //
        if ($id) {
            $venta = Venta::findOrFail($id);
            $productos = Producto::where('estado', 'activo')->get();
            return view('templades.NuevaVenta', compact('venta', 'productos'));
        }
        return redirect()->route('ventas')->with('error', 'No se especificó una venta');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function flecht($id)
    {
        try {
            // Obtener solo los detalles de la venta específica
            $lista = Detalle_Venta::with('producto')
                        ->where('id_venta', $id)
                        ->get();
            return response()->json($lista, 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'idventa' => 'required|integer|exists:ventas,id',
            'producto' => 'required|integer|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        // Verificar stock
        $producto = Producto::find($request->producto);
        if ($producto->stock < $request->cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'No hay suficiente stock disponible'
            ]);
        }

        $detalle = Detalle_Venta::create([
            'id_venta' => $request->idventa,
            'id_producto' => $request->producto,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $request->precio,
        ]);

        // Actualizar el stock del producto
        $producto->stock -= $request->cantidad;
        $producto->save();

        return response()->json([
            'success' => true,
            'message' => 'Se añadió correctamente el producto'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Detalle_Venta $detalle_Venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Detalle_Venta $detalle_Venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Detalle_Venta $detalle_Venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $detalle = Detalle_Venta::findOrFail($id);
            
            // Restaurar el stock
            $producto = Producto::find($detalle->id_producto);
            $producto->stock += $detalle->cantidad;
            $producto->save();
            
            $detalle->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado de la venta'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
