<?php

namespace App\Http\Controllers;

use App\Models\marca;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Asegurar que se importa esta clase
use Illuminate\Support\Facades\Auth;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('templades.marca');
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
    public function DevolverMarcas()
    {
        //
        $marca = Marca::all();
        return response()->json([
            'success'=> true,
            'message' => 'recuperacion con exito',
            'data' => $marca
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validamos los datos
        $request->validate([
            'nombre_marca'=>'required|string|max:50|unique:marcas,nombre_marca',
        ]);

        $marca = Marca::create([
            'nombre_marca'=>$request->nombre_marca
        ]);

        return response()->json([
            'success'=> true,
            'message' => 'Marca creada con exito',
            'data' => $marca
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(marca $marca)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validamos los datos
        $request->validate([
            'nombre_marca' => 'required|string|max:50|unique:marcas,nombre_marca,'.$id,
        ]);

        // Buscamos la marca
        $marca = Marca::findOrFail($id);

        // Actualizamos los datos
        $marca->update([
            'nombre_marca' => $request->nombre_marca
        ]);

        // Retornamos la respuesta
        return response()->json([
            'success' => true,
            'message' => 'Marca actualizada con éxito',
            'data' => $marca
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $marca =  Marca::findOrFail($id);
        $marca->delete();
        return response()->json([
            'success'=> true,
            'message'=> 'Marca eliminada con exito'
        ]);
    }
}
