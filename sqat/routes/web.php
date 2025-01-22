<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Models\Activo;
use App\Http\Controllers\ActivoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\TablaPersonasController;
use App\Http\Controllers\TablaActivosController;

Route :: get ('/login' , function () {
    return view ('login');
})->name('login');

Route::get('/', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

// Ruta protegida para el registro
Route::middleware('auth')->get('/register', function () {
    if (Auth::check() && Auth::user()->esAdministrador) {
        return view('register');
    } else {
        return response()->json(['message' => 'No tienes permisos'], 403);
    }
});

Route::middleware('auth')->post('/register', [AuthController::class, 'register']);
Route::middleware('auth')->post('/persona', [PersonaController::class, 'store']);

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/activo', [ActivoController::class, 'index']);
    Route::get('/persona', [PersonaController::class,'index']);
    Route::get('/tablaPersonas', [TablaPersonasController::class, 'index']);
    Route::get('/tablaActivos', [TablaActivosController::class, 'index']);
});


Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout(); // Cerrar la sesión
    session()->invalidate(); // Invalida la sesión
    session()->regenerateToken(); // Regenera el token CSRF
    return redirect('/login')->with('message', 'Sesión cerrada correctamente');
});

Route::middleware('auth')->get('/profile', function () {
    return view('profile');
});

Route::post('/activos', [ActivoController::class, 'store']);
