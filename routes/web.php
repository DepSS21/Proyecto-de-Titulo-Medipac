<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoAuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\FarmaceuticoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InformesController;


//Aquí se definen las rutas de la aplicación.

Route::get('/', function () {
    return view('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta para la vista tipoUsuario
Route::get('/tipoUsuario', function () {
    return view('tipoUsuario');
})->name('tipoUsuario');

Route::get('/paciente', function () {
    return view('paciente');
});

// Rutas para el login del médico
Route::get('/medico/login', [MedicoAuthController::class, 'showLoginForm'])->name('medico.login.form');
Route::post('/medico/login', [MedicoAuthController::class, 'login'])->name('medico.login');
Route::post('/medico/logout', [MedicoAuthController::class, 'logout'])->name('medico.logout');

Route::get('/medico', [RecetaController::class, 'showRecetaForm'])->name('medico');

Route::get('/receta/form', [RecetaController::class, 'showRecetaForm'])->name('receta.form');
Route::get('/buscar-paciente', [RecetaController::class, 'buscarPaciente'])->name('buscar.paciente');
// Ruta para almacenar la receta
Route::post('/receta/store', [RecetaController::class, 'store'])->name('receta.store');

Route::post('/validar-rut', [PacienteController::class, 'validarRut'])->name('validar.rut');
Route::get('/recetas', [PacienteController::class, 'mostrarRecetas'])->name('mostrar.recetas');


// Ruta para mostrar las funciones del administrador
Route::get('/admin/funciones', [AdminController::class, 'showFunciones'])->name('admin.funciones');

// Ruta para mostrar el formulario de creación de farmacéuticos
Route::get('/farmaceutico/create', function () {
    return view('crearFarmaceutico');
})->name('farmaceutico.create');

// Ruta para almacenar el farmacéutico
Route::post('/farmaceutico/store', [FarmaceuticoController::class, 'store'])->name('farmaceutico.store');

// Ruta para la página de tipoUsuario
Route::get('/tipo-usuario', function () {
    return view('tipoUsuario');
})->name('tipoUsuario');

// Ruta para mostrar el formulario de creación de médicos
Route::get('/medico/create', function () {
    return view('crearMedico');
})->name('medico.create');

// Ruta para almacenar el médico
Route::post('/medico/store', [MedicoController::class, 'store'])->name('medico.store');

Route::get('/paciente/recetas', [PacienteController::class, 'mostrarRecetas'])->name('paciente.recetas');
Route::post('/paciente/seleccionar-receta', [PacienteController::class, 'seleccionarReceta'])->name('paciente.seleccionar.receta');

// Ruta para mostrar el formulario de inicio de sesión de farmacéuticos
Route::get('/farmaceutico/login', [FarmaceuticoController::class, 'showLoginForm'])->name('farmaceutico.login.form');

// Ruta para manejar el inicio de sesión de farmacéuticos
Route::post('/farmaceutico/login', [FarmaceuticoController::class, 'login'])->name('farmaceutico.login');

// Ruta para cerrar sesión de farmacéuticos
Route::post('/farmaceutico/logout', [FarmaceuticoController::class, 'logout'])->name('farmaceutico.logout');


// Ruta para el dashboard del farmacéutico
Route::get('/farmaceutico/dashboard', [FarmaceuticoController::class, 'dashboard'])->name('farmaceutico.dashboard');


// Rutas para los módulos
Route::get('/modulo/a', [FarmaceuticoController::class, 'moduloA'])->name('modulo.a');
Route::get('/modulo/b', [FarmaceuticoController::class, 'moduloB'])->name('modulo.b');
Route::get('/modulo/c', [FarmaceuticoController::class, 'moduloC'])->name('modulo.c');

// Ruta para mostrar los datos de una receta pendiente
Route::get('/receta/{id}', [FarmaceuticoController::class, 'mostrarReceta'])->name('mostrar.receta');

// Ruta para entregar una receta
Route::post('/entregar-receta/{id}', [FarmaceuticoController::class, 'entregarReceta'])->name('entregar.receta');


// Ruta para mostrar la vista de informes
Route::get('/admin/informes', [InformesController::class, 'show'])->name('informes.show');

// Ruta para generar el informe
Route::get('/admin/informes/generar', [InformesController::class, 'generarInforme'])->name('informes.generar');

// Ruta para consultar las recetas pendientes
Route::get('/admin/informes/pendientes', [InformesController::class, 'pendientes'])->name('informes.pendientes');

// Ruta para buscar una receta por ID
Route::get('/admin/informes/buscar-receta', [InformesController::class, 'buscarReceta'])->name('informes.buscarReceta');


// Ruta para consultar las recetas entregadas por fecha
Route::get('/admin/informes/entregadas-por-fecha', [InformesController::class, 'entregadasPorFecha'])->name('informes.entregadasPorFecha');
