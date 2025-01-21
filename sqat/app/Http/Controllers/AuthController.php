<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // funcion para registrar un usuario
    public function register(Request $request)
    {
        if (!Auth::user()->esAdministrador) {
            return redirect('/')->with('error', 'Solo los administradores pueden registrar nuevos usuarios.');
        }
        // validamos los datos
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email|unique:usuarios',
            'nombres' => 'required|string',
            'primerApellido' => 'required|string',
            'segundoApellido' => 'string',
            'contrasena' => 'required|string|min:6',
            'esAdministrador' => 'boolean',
        ]);

        // si la validacion falla, retornamos un error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // creamos el usuario
        $usuario = Usuario::create([
            'correo' => $request->correo,
            'nombres' => $request->nombres,
            'primerApellido' => $request->primerApellido,
            'segundoApellido' => $request->segundoApellido,
            'contrasena' => Hash::make($request->contrasena),
            'esAdministrador' => $request->esAdministrador ?? false,
        ]);

        // Redirigimos al administrador a la página de usuarios registrados
        return redirect('/register')->with('success', 'Usuario registrado correctamente.');
    }

    // funcion para loguear un usuario
    public function login(Request $request)
    {
        // Agregar dd() para depurar
        //dd($request->all()); // Esto imprimirá todos los datos del formulario y detendrá la ejecución

        //ver todos los usuarios de la base de datos
        //dd(Usuario::all());

        // Obtener las credenciales
        $credentials = [
            'correo' => $request->correo,
            'password' => $request->contrasena, // Laravel siempre busca 'password'
        ];

        // Intentar autenticar al usuario
        if (Auth::attempt(credentials: $credentials)) {
            return redirect()->intended('/dashboard'); // Redirigir a la página de perfil si el login es exitoso
        }

        // Si la autenticación falla, devolver con un mensaje de error
        return back()->with('error', 'Las credenciales son incorrectas.');
    }

    // funcion para cerrar sesion
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'sesion cerrada'], 200);
    }
}
