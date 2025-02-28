<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use App\Models\marca;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('templades.producto', compact('marcas','categorias'));
    }

    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $user = Auth::user(); 

            if (!$user || $user->rol !== 'admin') {
                return redirect()->route('home')->with('error', 'Acceso no autorizado');
            }

            return $next($request);
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registro()
    {
        try {
            $productos = Producto::with(['marca', 'categoria'])->get();
            return response()->json($productos, 200);
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
        $productoData = $this->validarYPrepararDatos($request);

        $producto = Producto::create($productoData);

        return response()->json([
            'success' => true,
            'message' => 'Producto creado con éxito',
            'data' => $producto
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $productoData = $this->validarYPrepararDatos($request, $id, $producto);

        $producto->update($productoData);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado con éxito',
            'data' => $producto
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $producto = Producto::findOrFail($id);

        $this->eliminarImagen($producto->imagen);

        $producto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ]);
    }

    /**
     * Valida y prepara los datos del producto.
     */
    private function validarYPrepararDatos(Request $request, $id = null, $producto = null)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:productos,nombre' . ($id ? ",$id" : ""),
            'descripcion' => 'nullable|string',
            'precio_venta' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'marca' => 'required|exists:marcas,id|integer',
            'categoria' => 'required|integer|exists:categorias,id'
        ]);

        $productoData = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio_venta' => $request->precio_venta,
            'id_marca' => $request->marca,
            'id_categoria' => $request->categoria,
        ];

        if ($request->hasFile('imagen')) {
            if ($producto) {
                $this->eliminarImagen($producto->imagen);
            }
            $productoData['imagen'] = $this->guardarImagen($request->file('imagen'));
        } elseif (!$producto) {
            $productoData['imagen'] = '';
        }

        return $productoData;
    }

    /**
     * Elimina una imagen si existe y no es la predeterminada.
     */
    private function eliminarImagen($imagen)
    {
        if ($imagen && $imagen !== 'producto.png') {
            $imagePath = public_path('imagen/productos/' . $imagen);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
    }

    /**
     * Guarda la nueva imagen y devuelve su nombre.
     */
    private function guardarImagen($imagen)
    {
        $rutaGuardarImagen = 'imagen/productos/';
        $nombreImagen = date('YmdHis') . "_" . uniqid() . "." . $imagen->getClientOriginalExtension();
        $imagen->move(public_path($rutaGuardarImagen), $nombreImagen);

        return $nombreImagen;
    }
}