<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardActivosController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesplegableController;
use App\Models\Activo;
use App\Http\Controllers\ActivoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\TablaPersonasController;
use App\Http\Controllers\TablaActivosController;
use App\Http\Controllers\ImportarController;
use App\Http\Controllers\TablaDatosController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\SubDashboardController;
use App\Http\Controllers\DashboardTipoController;

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
    if (Auth::check() && Auth::user()->es_administrador) {
        return view('register');
    } else {
        return response()->json(['message' => 'No tienes permisos'], 403);
    }
});

Route::middleware('auth')->post('/register', [AuthController::class, 'register']);
Route::middleware('auth')->post('/personas', [PersonaController::class, 'store']);

Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrarActivo', [ActivoController::class, 'registro']);
    Route::get('/registrarPersona', [PersonaController::class,'registro']);
    Route::get('/registrarUbicacion', [UbicacionController::class, 'registro']);
    Route::get('/tablaPersonas', [TablaPersonasController::class, 'index']);
    Route::get('/tablaActivos', [TablaActivosController::class, 'index']);
    Route::get('/importar', [ImportarController::class, 'index']);
    Route::get('/subdashboard', [SubDashboardController::class, 'index']);
    Route::get('/dashboardTipo/{tipoDeActivo}', [DashboardTipoController::class, 'index']);
    Route::get('/activos/{id}/editar', [ActivoController::class, 'editar'])->name('activos.update');
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

Route::post('/activos', [ActivoController::class, 'store']);
Route::post('/ubicaciones', [UbicacionController::class, 'store']);
Route::get('/personas/{rut}', [PersonaController::class, 'checkRut']);
Route::get('/register/{correo}', [AuthController::class, 'checkCorreo']);


Route::get('/descargarExcel', function () {
    $filePath = public_path('excel/ImportarDatos.xlsx');
    return Response::download($filePath, 'ImportarDatos.xlsx');
})->name('descargar.excel');

Route::post('/importar', [ImportarController::class, 'importExcel'])->name('importar.excel');

Route::get('/tablaDatos', [TablaDatosController::class, 'index']);
Route::post('/activos/editar/{id}', [ActivoController::class, 'update'])->name('activos.update');
Route::post('/activos/deshabilitar/{id}', [ActivoController::class, 'deshabilitar'])->name('activos.deshabilitar');
Route::post('/activos/cambiarEstado', [ActivoController::class, 'cambiarEstado'])->name('activos.cambiarEstado');
//ruta para confirmar importacion
Route::get('/confirmarImportacion', [ImportarController::class, 'confirmarImportacion'])->name('confirmar.importacion');
Route::post('/actualizarSubDashboard', [SubDashboardController::class, 'actualizarUbicacion'])->name('actualizar.dashboard');
