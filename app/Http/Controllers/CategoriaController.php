<?php

namespace App\Http\Controllers;

use App\Models\categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('templades.categoria');
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
    public function Fletch()
    {
        //
        $categoria = Categoria::all();
        return response()->json([
            'success'=> true,
            'message'=>'datos recuperados con exito',
            'data'=> $categoria
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre_categoria'=> 'required|string|max:50|unique:categorias,nombre_categoria'
        ]);

        $categoria = Categoria::create([
            'nombre_categoria'=>$request->nombre_categoria
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Categoria creado con exito',
            'data'=>$categoria
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'nombre_categoria' => 'required|string|max:50|unique:categorias,nombre_categoria,' . $id
        ]);

        $categoria = Categoria::findOrFail($id);
        $categoria->update([
            'nombre_categoria'=>$request->nombre_categoria
        ]);

        return response()->json([
            'success'=>true,
            'message'=> 'Categoria actualziada con exito',
            'data'=> $categoria
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Categoria eliminada correctamente',
            'data'=> $categoria
        ]);
    }
}
