<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoAuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\FarmaceuticoController;
use App\Http\Controllers\AdminController;

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
Route::get('/modulo/a', function () {
    return 'Módulo A';
})->name('modulo.a');

Route::get('/modulo/b', function () {
    return 'Módulo B';
})->name('modulo.b');

Route::get('/modulo/c', function () {
    return 'Módulo C';
})->name('modulo.c');