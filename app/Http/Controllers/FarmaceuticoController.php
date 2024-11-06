<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // Importar la clase Session

class FarmaceuticoController extends Controller
{
    public function showLoginForm()
    {
        return view('loginFarmaceutico');
    }

    public function login(Request $request)
    {
        $request->validate([
            'farmaceuticoId' => 'required|string',
        ]);

        // Consultar directamente a la base de datos
        $farmaceutico = DB::table('Farmaceutico')->where('id_farmaceutico', $request->farmaceuticoId)->first();

        if ($farmaceutico) {
            Session::put('farmaceutico', $farmaceutico);
            return redirect()->route('farmaceutico.dashboard')->with('success', 'Inicio de sesión exitoso.');
        } else {
            return redirect()->back()->with('error', 'ID de Farmacéutico no válido.');
        }
    }

    public function logout()
    {
        Session::forget('farmaceutico');
        return redirect()->route('farmaceutico.login.form')->with('success', 'Sesión cerrada exitosamente.');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut_farmaceutico' => 'required|string|max:255|unique:Farmaceutico',
        ]);

        // Crear un nuevo farmacéutico directamente en la base de datos
        DB::table('Farmaceutico')->insert([
            'nombre' => $validatedData['nombre'],
            'rut_farmaceutico' => $validatedData['rut_farmaceutico'],
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('farmaceutico.create')->with('success', 'Farmacéutico creado exitosamente.');
    }

    public function dashboard()
    {
        return view('dashboardFarmaceutico');
    }

    public function moduloA()
    {
        $recetas = DB::table('Receta')
            ->join('Paciente', 'Receta.id_paciente', '=', 'Paciente.id_paciente')
            ->join('registro_receta', 'Receta.id_receta', '=', 'registro_receta.id_receta')
            ->leftJoin('registro_receta_entregada', 'Receta.id_receta', '=', 'registro_receta_entregada.id_receta')
            ->select('Receta.*', 'Paciente.nombre as nombre_paciente')
            ->where('registro_receta.estado_receta', 'pendiente')
            ->whereNull('registro_receta_entregada.id_receta')
            ->get();
        return view('moduloA', compact('recetas'));
    }

    public function moduloB()
    {
        $recetas = DB::table('Receta')
            ->join('Paciente', 'Receta.id_paciente', '=', 'Paciente.id_paciente')
            ->join('registro_receta', 'Receta.id_receta', '=', 'registro_receta.id_receta')
            ->leftJoin('registro_receta_entregada', 'Receta.id_receta', '=', 'registro_receta_entregada.id_receta')
            ->select('Receta.*', 'Paciente.nombre as nombre_paciente')
            ->where('registro_receta.estado_receta', 'pendiente')
            ->whereNull('registro_receta_entregada.id_receta')
            ->get();
        return view('moduloB', compact('recetas'));
    }

    public function moduloC()
    {
        $recetas = DB::table('Receta')
            ->join('Paciente', 'Receta.id_paciente', '=', 'Paciente.id_paciente')
            ->join('registro_receta', 'Receta.id_receta', '=', 'registro_receta.id_receta')
            ->leftJoin('registro_receta_entregada', 'Receta.id_receta', '=', 'registro_receta_entregada.id_receta')
            ->select('Receta.*', 'Paciente.nombre as nombre_paciente')
            ->where('registro_receta.estado_receta', 'pendiente')
            ->whereNull('registro_receta_entregada.id_receta')
            ->get();
        return view('moduloC', compact('recetas'));
    }

    public function mostrarReceta($id)
    {
        $receta = DB::table('Receta')
            ->join('Paciente', 'Receta.id_paciente', '=', 'Paciente.id_paciente')
            ->select('Receta.*', 'Paciente.nombre as nombre_paciente')
            ->where('Receta.id_receta', $id)
            ->first();

        return view('mostrarReceta', compact('receta'));
    }

    public function entregarReceta($id)
    {
        // Registrar la entrega de la receta en la tabla registro_receta_entregada
        DB::table('registro_receta_entregada')->insert([
            'fecha_registro' => now(),
            'estado_receta' => 'Entregada',
            'id_receta' => $id,
            'id_farmaceutico' => Session::get('farmaceutico')->id_farmaceutico,
        ]);

        return redirect()->route('modulo.a')->with('success', 'Receta entregada exitosamente.');
    }
}