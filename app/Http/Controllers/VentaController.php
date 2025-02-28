<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('templades.venta');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function ventasDelDia()
    {
        $hoy = now()->setTimezone('America/Lima')->toDateString(); // Asegurar la zona horaria correcta

        $ventas = Venta::with('detalles.producto')
                    ->where(DB::raw("DATE(fecha)"), $hoy) // Extrae solo la fecha de la columna 'fecha'
                    ->get();

        $totalVentas = $ventas->sum('total');

        return view('ventas.VentasDelDia', compact('ventas', 'totalVentas', 'hoy'));
    }


    public function boleta($id)
    {
        $venta = Venta::with('detalles.producto')->findOrFail($id);
        return view('ventas.boleta', compact('venta'));
    }


    public function flecht()
    {
        try {
            $venta = Venta::all();
            return response()->json([
                'seccess'=>true,
                'message'=>'json exitoso',
                'data'=>$venta
            ]);
        
        } catch (\Exception $e) {
            echo($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Crear nueva venta 
        $venta = Venta::create([
            'total' => 0
        ]);
        
        // Redireccionar a la página de nueva venta con el ID
        return redirect()->route('boleta', ['id' => $venta->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        //
    }

    
    public function finalizarVenta(Request $request, $id)
    {
        try {
            $venta = Venta::findOrFail($id);
            $venta->total = $request->total;
            $venta->save();
            
            // Si la solicitud espera JSON (es una solicitud AJAX)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Venta finalizada correctamente',
                    'redirect' => route('ventas')
                ]);
            }
            
            // Si es una solicitud normal, redirigir directamente
            return redirect()->route('ventas')->with('success', 'Venta finalizada correctamente');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al finalizar la venta: ' . $e->getMessage());
        }
    }
}
