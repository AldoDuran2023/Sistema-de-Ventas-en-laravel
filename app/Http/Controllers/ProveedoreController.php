<?php

namespace App\Http\Controllers;

use App\Models\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProveedoreController extends Controller
{
    /**
     * Muestra la vista principal del proveedor.
     */
    public function index()
    {
        return view('templades.proveedor');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Recuperar datos en tipo JSON.
     */
    public function flecht()
    {
        $proveedor = Proveedore::all();
        return response()->json([
            'success' => true,
            'message' => 'Recuperación exitosa',
            'data' => $proveedor
        ]);
    }

    /**
     * Almacena un nuevo proveedor en la base de datos.
     */
    public function store(Request $request)
    {
        $proveedorData = $this->validarYPrepararDatos($request);

        $proveedor = Proveedore::create($proveedorData);

        return response()->json([
            'success' => true,
            'message' => 'Creación de proveedor con éxito',
            'data' => $proveedor
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedore $proveedore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedore $proveedore)
    {
        //
    }

    /**
     * Actualiza un proveedor existente.
     */
    public function update(Request $request, $id)
    {
        $proveedore = Proveedore::findOrFail($id);
        $proveedorData = $this->validarYPrepararDatos($request, $id, $proveedore);

        $proveedore->update($proveedorData);

        return response()->json([
            'success' => true,
            'message' => 'Actualización de proveedor con éxito',
            'data' => $proveedore
        ]);
    }

    /**
     * Elimina un proveedor y su imagen si existe.
     */
    public function destroy($id)
    {
        $proveedore = Proveedore::findOrFail($id);

        $this->eliminarImagen($proveedore->imagen);

        $proveedore->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado con éxito'
        ]);
    }

    /**
     * Valida y prepara los datos del proveedor.
     */
    private function validarYPrepararDatos(Request $request, $id = null, $proveedore = null)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'telefono' => 'required|string|max:9|unique:proveedores,telefono' . ($id ? ",$id" : ""),
            'direccion' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:proveedores,correo' . ($id ? ",$id" : "")
        ]);

        $proveedorData = $request->only(['nombre', 'telefono', 'direccion', 'correo']);

        if ($request->hasFile('imagen')) {
            if ($proveedore) {
                $this->eliminarImagen($proveedore->imagen);
            }
            $proveedorData['imagen'] = $this->guardarImagen($request->file('imagen'));
        }

        return $proveedorData;
    }

    /**
     * Guarda la imagen y devuelve su nombre.
     */
    private function guardarImagen($imagen)
    {
        $rutaGuardarImagen = 'imagen/';
        $nombreImagen = date('YmdHis') . "_" . uniqid() . "." . $imagen->getClientOriginalExtension();
        $imagen->move(public_path($rutaGuardarImagen), $nombreImagen);

        return $nombreImagen;
    }

    /**
     * Elimina una imagen si existe y no es la predeterminada.
     */
    private function eliminarImagen($imagen)
    {
        if ($imagen && $imagen !== 'default.png') {
            $imagePath = public_path('imagen/' . $imagen);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
    }
}
