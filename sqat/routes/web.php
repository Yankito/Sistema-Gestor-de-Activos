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
use App\Http\Controllers\TablaDatosController;
use App\Http\Controllers\DashboardUbicacionController;
use App\Http\Controllers\DashboardTipoController;
use App\Http\Controllers\DashboardFiltrosController;
use App\Http\Controllers\ImportarActivosController;
use App\Http\Controllers\ImportarPersonasController;
use App\Http\Controllers\ExportarController;
use App\Http\Controllers\TablaUbicacionesController;
use App\Http\Controllers\CrearTipoActivoController;
use App\Http\Controllers\HistorialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

// Rutas públicas
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    return view('login');
});

// Ruta para mostrar el formulario de registro (solo para administradores)
Route::middleware('auth')->get('/register', function () {
    if (Auth::check() && Auth::user()->es_administrador) {
        return view('register');
    } else {
        return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
    }
});

// Rutas para gestionar Tipos de Activo
Route::middleware(['auth'])->group(function(){
    Route::get('/tipos-activo', [CrearTipoActivoController::class, 'index'])->name('tipos-activo.index');
    Route::post('/tipos-activo', [CrearTipoActivoController::class, 'store'])->name('tipos-activo.store');
    Route::delete('/tipos-activo/{id}', [CrearTipoActivoController::class, 'destroy'])->name('tipos-activo.destroy');
});


// Ruta para procesar el registro (solo para administradores)
Route::middleware('auth')->post('/register', [AuthController::class, 'register']);

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/registrarActivo', [ActivoController::class, 'registro']);
    Route::get('/registrarPersona', [PersonaController::class,'registro']);
    Route::get('/registrarUbicacion', [UbicacionController::class, 'registro']);
    Route::get('/modificarUbicacion', [UbicacionController::class, 'modificar'])->name('ubicaciones.modificar');
    Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones');
    Route::get('/tablaPersonas', [TablaPersonasController::class, 'index']);
    Route::get('/tablaActivos', [TablaActivosController::class, 'index']);
    Route::get('/importar', [ImportarController::class, 'index']);
    Route::get('/dashboardUbicacion', [DashboardUbicacionController::class, 'index']);
    Route::get('/dashboardTipo', [DashboardTipoController::class, 'index'])->name('dashboard.tipo');
    Route::get('/dashboardFiltros', [DashboardFiltrosController::class, 'index'])->name('dashboard.filtros');
    Route::get('/activos/{id}/editar', [ActivoController::class, 'editar'])->name('activos.update');
    Route::get('/importarActivos', [ImportarActivosController::class, 'index']);
    Route::get('/importarPersonas', [ImportarPersonasController::class, 'index']);
    Route::get('/exportar', [ExportarController::class, 'index']);
    Route::get('/exportar/{tabla}/{formato}', [ExportarController::class, 'exportar']);
    Route::get('/crearTipoActivo', [CrearTipoActivoController::class, 'index']);
    Route::get('/historial', [HistorialController::class, 'index'])->name('historial');
});

Route::delete('/ubicaciones/eliminar/{hashed_id}', [UbicacionController::class, 'eliminar'])->name('ubicaciones.eliminar');


Route::get('/desplegable', function () {
    return view('desplegable');
});


// Ruta para procesar el inicio de sesión
Route::post('/login', [AuthController::class, 'login']);

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login')->with('success', 'Sesión cerrada correctamente.');
});

// Ruta para el perfil del usuario
Route::middleware('auth')->get('/profile', function () {
    return view('profile');
});

// Rutas para almacenar datos
Route::post('/activos', [ActivoController::class, 'store']);
Route::post('/ubicaciones', [UbicacionController::class, 'store']);
Route::post('/ubicacionesUpdate', [UbicacionController::class, 'update']);
Route::post('/personas', [PersonaController::class, 'store']);

// Rutas para verificar datos
Route::get('/personas/{rut}', [PersonaController::class, 'checkRut']);
Route::get('/register/{correo}', [AuthController::class, 'checkCorreo']);

// Rutas para descargar plantillas Excel
Route::get('/descargarExcel', function () {
    $filePath = public_path('excel/PlantillaAsignacion.xlsx');
    return Response::download($filePath, 'PlantillaAsignacion.xlsx');
})->name('descargar.excel');

Route::get('/descargarActivosExcel', function () {
    $filePath = public_path('excel/PlantillaActivos.xlsx');
    return Response::download($filePath, 'PlantillaActivos.xlsx');
})->name('descargarActivos.excel');

Route::get('/descargarPersonasExcel', function () {
    $filePath = public_path('excel/PlantillaPersonas.xlsx');
    return Response::download($filePath, 'PlantillaPersonas.xlsx');
})->name('descargarPersonas.excel');

// Rutas para importar datos
Route::post('/importar', [ImportarController::class, 'importExcel'])->name('importar.excel');
Route::post('/importarActivos', [ImportarActivosController::class, 'importExcel'])->name('importar.excel.activos');
Route::post('/importarPersonas', [ImportarPersonasController::class, 'importExcel'])->name('importar.excel.personas');

// Rutas para la tabla de datos
Route::get('/tablaDatos', [TablaDatosController::class, 'index']);
Route::post('/activos/editar/{id}', [ActivoController::class, 'update'])->name('activos.update');
Route::post('/activos/deshabilitar/{id}', [ActivoController::class, 'deshabilitar'])->name('activos.deshabilitar');
Route::post('/activos/cambiarEstado', [ActivoController::class, 'cambiarEstado'])->name('activos.cambiarEstado');

// Ruta para confirmar importación
Route::get('/confirmarImportacion', [ImportarController::class, 'confirmarImportacion'])->name('confirmar.importacion');

// Rutas para actualizar dashboards
Route::post('/actualizarDashboardUbicacion', [DashboardUbicacionController::class, 'actualizarUbicacion'])->name('actualizar.dashboardUbicacion');
Route::post('/actualizarDashboardTipo', [DashboardTipoController::class, 'actualizarTipo'])->name('actualizar.dashboardTipo');
