<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecetaController extends Controller
{
    public function showRecetaForm()
    {
        $medico = Session::get('medico'); // Obtener el médico autenticado de la sesión
        if (!$medico) {
            return redirect()->route('medico.login.form')->with('error', 'Debe autenticarse como médico.');
        }
        $nextRecetaId = Receta::max('id_receta') + 1; // Obtener el próximo ID de la receta
        $paciente = null; // Inicialmente, no hay paciente seleccionado

        return view('medico', compact('medico', 'nextRecetaId', 'paciente'));
    }

    public function buscarPaciente(Request $request)
    {
        $rut = $request->query('rut');
        $paciente = Paciente::where('rut_paciente', $rut)->first();
        $medico = Session::get('medico'); // Obtener el médico autenticado de la sesión
        if (!$medico) {
            return redirect()->route('medico.login.form')->with('error', 'Debe autenticarse como médico.');
        }
        $nextRecetaId = Receta::max('id_receta') + 1; // Obtener el próximo ID de la receta

        if ($paciente) {
            return view('medico', compact('paciente', 'medico', 'nextRecetaId', 'rut'));
        } else {
            return redirect()->back()->with('error', 'Paciente no encontrado. Por favor, ingrese un RUT válido.');
        }
    }

    public function store(Request $request)
{
    try {
        // Validar solo los campos necesarios
        $validatedData = $request->validate([
            'diagnostico' => 'required|string|max:20',
            'comentario' => 'required|string|max:255',
            'rutPaciente' => 'required|string'
        ]);

        // Obtener el médico de la sesión
        $medico = Session::get('medico');
        if (!$medico) {
            return redirect()->route('medico.login.form')
                ->with('error', 'Sesión de médico no encontrada.');
        }

        // Buscar el paciente por RUT
        $paciente = Paciente::where('rut_paciente', $validatedData['rutPaciente'])->first();
        if (!$paciente) {
            return redirect()->back()
                ->with('error', 'Paciente no encontrado. Por favor, verifique el RUT.');
        }

        // Crear la receta
        $receta = new Receta();
        $receta->fecha_creacion = now(); // Fecha actual
        $receta->Diagnostico = $validatedData['diagnostico'];
        $receta->comentarios = $validatedData['comentario'];
        $receta->id_medico = $medico->id_medico; // Usar el ID del médico en sesión
        $receta->id_paciente = $paciente->id_paciente; // Usar el ID del paciente encontrado
        $receta->save();

        return redirect()->route('receta.form')
            ->with('success', 'Receta generada exitosamente.');
    } catch (\Exception $e) {
        return redirect()->route('receta.form')
            ->with('error', 'Error al generar la receta: ' . $e->getMessage());
    }
}
}