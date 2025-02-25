<?php

namespace App\Http\Controllers;

use App\Models\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProveedoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Recuperar datos en tipo json.
     */
    public function flecht()
    {
        //
        $proveedor = Proveedore::all();
        return response()->json([
            'success', true,
            'message'=>'recuperacion exitosa',
            'data'=>$proveedor
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre'=> 'required|string|max:255|',
            'imagen'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'telefono'=> 'required|string|max:9|unique:Proveedores,telefono',
            'direccion'=> 'required|string|max:255',
            'correo'=> 'required|string|max:255|unique:Proveedores,correo'
        ]);

        // Preparar los datos del proveedor
        $proveedorData = [
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'correo' => $request->correo
        ];

        // Manejo de imagen
        if($imagen = $request->file('imagen')){
            $rutaGuardarImagen = 'imagen/';
            $imagenProveedor = date('YmdHis') . "_" . uniqid() . "." . $imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImagen, $imagenProveedor);
            $proveedorData['imagen'] = $imagenProveedor;
        }

        $proveedor = Proveedore::create($proveedorData);

        return response()->json([
            'success'=> true,
            'message'=> 'creacion de proveedor con exito',
            'data'=> $proveedor
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
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        //
        $request->validate([
            'nombre' => 'required|string|max:255,'.$id,
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048,'.$id,
            'telefono' => 'required|string|max:9,'.$id,
            'direccion' => 'required|string|max:255,'.$id,
            'correo' => 'required|email|max:255,'.$id,
        ]);

        $proveedore = Proveedore::findOrFail($id);

        if ($request->hasFile('imagen')) {
            if ($proveedore->imagen && $proveedore->imagen !== 'default.png') {
                $oldImagePath = public_path('imagen/' . $proveedore->imagen);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            $imagen = $request->file('imagen');
            $rutaGuardarImagen = 'imagen/';
            $imagenProveedor = date('YmdHis') . "_" . uniqid() . "." . $imagen->getClientOriginalExtension();
            $imagen->move(public_path($rutaGuardarImagen), $imagenProveedor);
            $proveedore->imagen = $imagenProveedor;
        }

        $proveedore->update($request->only(['nombre', 'telefono', 'direccion', 'correo']));

        return response()->json([
            'success' => true,
            'message' => 'Actualización de proveedor con éxito',
            'data' => $proveedore
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        // Buscar el proveedor por ID
        $proveedore = Proveedore::findOrFail($id);

        if ($proveedore->imagen && $proveedore->imagen !== 'default.png') {
            $imagePath = public_path('imagen/' . $proveedore->imagen);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $proveedore->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado con éxito'
        ]);
    }
}
