<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardActivosController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesplegableController;
use App\Models\Activo;
use App\Http\Controllers\RegistrarActivoController;
use App\Http\Controllers\RegistrarPersonaController;
use App\Http\Controllers\RegistrarUbicacionController;
use App\Http\Controllers\TablaPersonasController;
use App\Http\Controllers\TablaActivosController;
use App\Http\Controllers\ImportarController;
use Illuminate\Support\Facades\Response;

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
Route::middleware('auth')->post('/personas', [RegistrarPersonaController::class, 'store']);

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrarActivo', [RegistrarActivoController::class, 'index']);
    Route::get('/registrarPersona', [RegistrarPersonaController::class,'index']);
    Route::get('/registrarUbicacion', [RegistrarUbicacionController::class, 'index']);
    Route::get('/tablaPersonas', [TablaPersonasController::class, 'index']);
    Route::get('/tablaActivos', [TablaActivosController::class, 'index']);
    Route::get('/importar', [ImportarController::class, 'index']);
});


Route::get('/desplegable', function () {
    return view('desplegable');
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

Route::post('/activos', [RegistrarActivoController::class, 'store']);
Route::post('/ubicaciones', [RegistrarUbicacionController::class, 'store']);
Route::get('/personas/{rut}', [RegistrarPersonaController::class, 'checkRut']);


Route::get('/descargarExcel', function () {
    $filePath = public_path('excel/ImportarDatos.xlsx');
    return Response::download($filePath, 'ImportarDatos.xlsx');
})->name('descargar.excel');

Route::post('/importar', [ImportarController::class, 'importExcel'])->name('importar.excel');
