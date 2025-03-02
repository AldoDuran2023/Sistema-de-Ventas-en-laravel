<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('templades.usuarios');
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

    public function list_user()
    {
        try {
            $usuario = User::all();
            return response()->json($usuario, 200);
        } catch (\Exception $e) {
            echo($e);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'rol' => 'required|in:admin,empleado',
            ]);
            
            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->rol = $request->rol;

            $user->save();

            return response()->json($user, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validación
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id,
                'rol' => 'required|in:admin,empleado',
            ]);
            
            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            
            // Solo actualiza la contraseña si se proporciona
            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            
            $user->rol = $request->rol;

            $user->save();

            return response()->json($user, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'success' => true,
                'message'=> 'usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            echo($e);
        }
    }
}