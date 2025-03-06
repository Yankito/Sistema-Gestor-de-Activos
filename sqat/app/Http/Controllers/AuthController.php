<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Función para registrar un usuario
    public function register(Request $request)
    {
        try {
            // Verificar si el usuario autenticado es administrador
            if (!Auth::check() || !Auth::user()->es_administrador) {
                return redirect('/dashboard')->with('error', 'Solo los administradores pueden registrar nuevos usuarios.');
            }

            // Validar los datos del formulario
            $request->validate([
                'correo' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@iansa\.cl$/', 'unique:usuarios'],
                'nombres' => 'required|string|max:255',
                'primer_apellido' => 'required|string|max:255',
                'segundo_apellido' => 'nullable|string|max:255',
                'contrasena' => 'required|string|min:6',
                'es_administrador' => 'nullable|boolean',
            ], [
                'correo.regex' => 'Solo se pueden registrar correos con dominio @iansa.cl.',
                'correo.unique' => 'El correo ya está registrado.',
                'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres.',
            ]);

            // Crear el usuario
            $usuario = Usuario::create([
                'correo' => $request->correo,
                'nombres' => $request->nombres,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'contrasena' => Hash::make($request->contrasena),
                'es_administrador' => $request->es_administrador ?? false,
            ]);

            // Redirigir al dashboard con mensaje de éxito
            return redirect('/dashboard')->with('success', 'Usuario registrado correctamente.');
        } catch (\Exception $e) {
            // Si ocurre un error, redirigir con mensaje de error
            return back()->withInput()->with('error', 'Hubo un problema al registrar el usuario: ' . $e->getMessage());
        }
    }

    // Función para iniciar sesión
    public function login(Request $request)
    {
        // Validar las credenciales
        $credentials = $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['contrasena']])) {
            return redirect()->intended('/dashboard'); // Redirigir al dashboard si el login es exitoso
        }

        // Si la autenticación falla, devolver con un mensaje de error
        return back()->with('error', 'Las credenciales son incorrectas.');
    }

    // Función para cerrar sesión
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    
        // Limpiar el caché del navegador
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    
        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    }

    // Función para verificar si un correo ya está registrado
    public function checkCorreo($correo)
    {
        $usuario = Usuario::where('correo', $correo)->first();
        return response()->json(['exists' => $usuario !== null]);
    }
}