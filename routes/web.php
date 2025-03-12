<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\TablaPersonasController;
use App\Http\Controllers\TablaActivosController;
use App\Http\Controllers\ImportarController;
use App\Http\Controllers\ImportarActivosController;
use App\Http\Controllers\ImportarPersonasController;
use App\Http\Controllers\ExportarController;
use App\Http\Controllers\GestionarTipoActivoController;
use App\Http\Controllers\HistorialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Middleware\AdminMiddleware;

// Rutas públicas
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

// Ruta para mostrar el formulario de registro (solo para administradores)
Route::middleware('auth')->get('/register', function () {
    if (Auth::check() && Auth::user()->es_administrador) {
        return view('register');
    } else {
        return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
    }
});

Route::middleware([AdminMiddleware::class])->group(function(){
    // Rutas para verificar datos
    Route::get('/register/{correo}', [AuthController::class, 'checkCorreo']);
    Route::post('/register', [AuthController::class, 'register']);

    // Rutas para gestionar Tipos de Activo (solo para administradores)
    Route::get('/tipos-activo', [GestionarTipoActivoController::class, 'index'])->name('tipos-activo.index');
    Route::post('/tipos-activo', [GestionarTipoActivoController::class, 'store'])->name('tipos-activo.store');
    Route::delete('/tipos-activo/{hashed_id}', [GestionarTipoActivoController::class, 'destroy'])->name('tipos-activo.destroy');
    Route::post('/agregarCaracteristicas', [GestionarTipoActivoController::class, 'nuevasCaracteristicas']);
    Route::delete('/caracteristicaAdicional/{hashed_id}', [GestionarTipoActivoController::class, 'destroyCaracteristicaAdicional'])->name('caracteristicaAdicional.destroy');

    Route::post('/activos', [ActivoController::class, 'store']);
    Route::get('/registrarActivo', [ActivoController::class, 'registro']);

    Route::get('/registrarPersona', [PersonaController::class,'registro']);
    Route::post('/personas', [PersonaController::class, 'store']);
    Route::get('/personas/{rut}', [PersonaController::class, 'checkRut']);


    // Rutas para ubicaciones
    Route::get('/registrarUbicacion', [UbicacionController::class, 'registro']);
    Route::post('/ubicaciones', [UbicacionController::class, 'store']);
    Route::post('/ubicacionesUpdate', [UbicacionController::class, 'update']);
    Route::get('/modificarUbicacion', [UbicacionController::class, 'modificar'])->name('ubicaciones.modificar');
    Route::delete('/ubicaciones/eliminar/{hashed_id}', [UbicacionController::class, 'eliminar'])->name('ubicaciones.eliminar');

    //importar
    Route::get('/importar', [ImportarController::class, 'index']);
    Route::get('/importarActivos', [ImportarActivosController::class, 'index']);
    Route::get('/importarPersonas', [ImportarPersonasController::class, 'index']);
    // Rutas para importar datos
    Route::post('/importar', [ImportarController::class, 'importExcel'])->name('importar.excel');
    Route::post('/importarActivos', [ImportarActivosController::class, 'importExcel'])->name('importar.excel.activos');
    Route::post('/importarPersonas', [ImportarPersonasController::class, 'importExcel'])->name('importar.excel.personas');
    // Ruta para confirmar importación
    Route::get('/confirmarImportacion', [ImportarController::class, 'confirmarImportacion'])->name('confirmar.importacion');
});

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones');
    Route::get('/tablaPersonas', [TablaPersonasController::class, 'index']);
    Route::get('/tablaActivos', [TablaActivosController::class, 'index']);

    Route::get('/exportar', [ExportarController::class, 'index']);
    Route::get('/exportar/{tabla}/{formato}', [ExportarController::class, 'exportar']);
    Route::get('/historial', [HistorialController::class, 'index'])->name('historial');

    // Rutas para descargar plantillas Excel
    Route::get('/descargarExcel', function () {
        $filePath = public_path('excel/PlantillaAsignacion.xlsx');
        return Response::download($filePath, 'PlantillaAsignacion.xlsx');
    })->name('descargar.excel');

    Route::get('/descargar-plantilla-activos', [ImportarActivosController::class, 'generarPlantilla'])
        ->name('descargarActivos.excel');

    Route::get('/descargarPersonasExcel', function () {
        $filePath = public_path('excel/PlantillaPersonas.xlsx');
        return Response::download($filePath, 'PlantillaPersonas.xlsx');
    })->name('descargarPersonas.excel');

    // Ruta para cerrar sesión
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
    });
});








