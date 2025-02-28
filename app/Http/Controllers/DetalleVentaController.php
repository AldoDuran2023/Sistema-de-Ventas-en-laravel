<?php

namespace App\Http\Controllers;

use App\Models\Detalle_Venta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Iniciar transacción
        DB::beginTransaction();

        try {
            // Bloquear el producto para actualización (FOR UPDATE)
            $producto = Producto::select('*')
                        ->where('id', $request->producto)
                        ->lockForUpdate()
                        ->first();
            
            if (!$producto) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            // Verificar stock
            if ($producto->stock < $request->cantidad) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No hay suficiente stock disponible'
                ]);
            }

            // Crear detalle de venta
            $detalle = Detalle_Venta::create([
                'id_venta' => $request->idventa,
                'id_producto' => $request->producto,
                'cantidad' => $request->cantidad,
                'precio_unitario' => $request->precio,
            ]);

            // Actualizar el stock del producto
            $producto->stock -= $request->cantidad;
            
            // Si el stock llega a cero, cambiar estado a inactivo
            if ($producto->stock <= 0) {
                $producto->estado = 'inactivo';
            }
            
            $producto->save();
            
            // Actualizar el total de la venta
            $venta = Venta::findOrFail($request->idventa);
            $venta->total = $venta->detalles->sum(function($detalle) {
                return $detalle->cantidad * $detalle->precio_unitario;
            });
            $venta->save();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Se añadió correctamente el producto',
                'total' => $venta->total
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
        DB::beginTransaction();

        try {
            $detalle = Detalle_Venta::findOrFail($id);
            $idVenta = $detalle->id_venta;
            
            // Bloquear el producto para actualización
            $producto = Producto::select('*')
                        ->where('id', $detalle->id_producto)
                        ->lockForUpdate()
                        ->first();
            
            if (!$producto) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            
            // Restaurar el stock
            $producto->stock += $detalle->cantidad;
            
            // Si el producto estaba inactivo por falta de stock y ahora tiene stock, activarlo
            if ($producto->estado == 'inactivo' && $producto->stock > 0) {
                $producto->estado = 'activo';
            }
            
            $producto->save();
            
            // Eliminar el detalle
            $detalle->delete();
            
            // Actualizar el total de la venta
            $venta = Venta::findOrFail($idVenta);
            $venta->total = $venta->detalles->sum(function($detalle) {
                return $detalle->cantidad * $detalle->precio_unitario;
            });
            $venta->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado de la venta',
                'total' => $venta->total
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}