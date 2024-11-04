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
}