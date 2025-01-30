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
        /**
         * Validates the incoming request data for user authentication.
         *
         * Validation rules:
         * - 'correo': Required, must be a valid email address with the domain @iansa.cl.
         * - 'nombres': Required, must be a string with a maximum length of 255 characters.
         * - 'primerApellido': Required, must be a string with a maximum length of 255 characters.
         * - 'segundoApellido': Optional, must be a string with a maximum length of 255 characters.
         * - 'contrasena': Required, must be at least 6 characters long.
         *
         * Custom error messages:
         * - 'correo.regex': 'Solo se pueden registrar con dominio @iansa.cl'
         *
         * @param \Illuminate\Http\Request $request The incoming request instance.
         * @return void
         */
        $request->validate([
            'correo' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@iansa\.cl$/'],
            'nombres' => 'required|string|max:255',
            'primerApellido' => 'required|string|max:255',
            'segundoApellido' => 'nullable|string|max:255',
            'contrasena' => 'required|min:6',
        ], [
            'correo.regex' => 'Solo se pueden registrarcon dominio @iansa.cl',
        ]);

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
        return redirect('/dashboard')->with('success', 'Usuario registrado correctamente.');
    }

    // funcion para loguear un usuario
    public function login(Request $request)
    {
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
