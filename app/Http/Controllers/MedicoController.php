<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'rut' => 'required|string|max:255|unique:Medico',
            'especialidad' => 'required|string|max:255'
        ]);

        // Crear un nuevo médico
        Medico::create($validatedData);

        // Redirigir con un mensaje de éxito
        return redirect()->route('medico.create')->with('success', 'Médico creado exitosamente.');
    }
}