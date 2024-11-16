<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

use Illuminate\Support\Facades\Log;

class PacienteController extends Controller
{
    public function validarRut(Request $request)
    {
        $request->validate([
            'rut' => 'required|string',
            'numeroSerie' => 'required|string|size:3',
        ]);

        $rut = $request->input('rut');
        $numeroSerie = $request->input('numeroSerie');

        $paciente = Paciente::where('rut_paciente', $rut)->first();

        if ($paciente && substr($paciente->numero_serie, -3) === $numeroSerie) {
            session(['paciente_id' => $paciente->id_paciente]);
            return redirect()->route('paciente.recetas');
        } else {
            return redirect()->back()->with('error', 'RUT o número de serie incorrectos. Por favor, intente nuevamente.');
        }
    }

    public function mostrarRecetas()
    {
        $paciente_id = session('paciente_id');
        if (!$paciente_id) {
            return redirect('/')->with('error', 'Por favor, ingrese su RUT y número de serie.');
        }
    
        // Obtener las recetas con su estado más reciente
        $recetas = DB::table('Receta')
            ->join('Medico', 'Receta.id_medico', '=', 'Medico.id_medico')
            ->leftJoin(DB::raw('(
                SELECT id_receta, estado_receta
                FROM registro_receta r1
                WHERE fecha_registro = (
                    SELECT MAX(fecha_registro)
                    FROM registro_receta r2
                    WHERE r2.id_receta = r1.id_receta
                )
            ) as ultimo_estado'), 'Receta.id_receta', '=', 'ultimo_estado.id_receta')
            ->where('Receta.id_paciente', $paciente_id)
            ->select(
                'Receta.*',
                'Medico.nombre as nombre_medico',
                DB::raw('COALESCE(ultimo_estado.estado_receta, \'Generado\') as estado_receta')
            )
            ->orderBy('Receta.fecha_creacion', 'desc')
            ->get();
    
        $paciente = Paciente::find($paciente_id);
    
        return view('mostrarRecetas', compact('recetas', 'paciente'));
    }
    public function seleccionarReceta(Request $request)
{
    try {
        $request->validate([
            'receta_id' => 'required|exists:Receta,id_receta'
        ]);

        // Verificar si la receta ya fue seleccionada
        $registroExistente = DB::table('registro_receta')
            ->where('id_receta', $request->receta_id)
            ->where('estado_receta', 'Pendiente')
            ->first();

        if ($registroExistente) {
            return redirect()->back()
                ->with('error', 'Esta receta ya ha sido seleccionada para retiro.');
        }

        // Obtener la información del paciente y la receta
        $receta = DB::table('Receta')->where('id_receta', $request->receta_id)->first();
        $paciente = DB::table('Paciente')->where('id_paciente', $receta->id_paciente)->first();

        // Validar y mapear el diagnóstico
        $diagnostico = strtolower($receta->Diagnostico);
        $enfermedades_dummies = [
            // Lista de enfermedades como en el script de Python...
        ];

        if (!array_key_exists($diagnostico, $enfermedades_dummies)) {
            return redirect()->back()->with('error', 'Diagnóstico no reconocido en el sistema.');
        }

        $diagnostico_numerico = $enfermedades_dummies[$diagnostico];

        // Preparar los datos para el modelo
        $datos = [
            "edad" => $paciente->edad,
            "sexo" => $paciente->sexo,
            "diagnostico" => $diagnostico
        ];

        // Convertir a JSON
        $datosJson = json_encode($datos, JSON_UNESCAPED_UNICODE);
        Log::info('Datos enviados al script de Python: ' . $datosJson);

        // Ejecutar el script
        $comando = "python C:\\xampp\\htdocs\\Laravel\\proyecto-app\\python_service\\predict_module.py " . escapeshellarg($datosJson);
        exec($comando, $output, $returnCode);

        Log::info('Salida del script de Python:', ['output' => $output, 'returnCode' => $returnCode]);

        if ($returnCode === 0) {
            $modulo = trim($output[0]);
            DB::table('registro_receta')->insert([
                'fecha_registro' => now(),
                'estado_receta' => 'Pendiente',
                'id_receta' => $request->receta_id
            ]);
            return redirect()->back()
                ->with('success', "Receta seleccionada correctamente. Dirígete al Módulo $modulo.");
        } else {
            return redirect()->back()->with('error', 'El script de Python falló.');
        }
    } catch (\Exception $e) {
        Log::error('Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Error al seleccionar la receta.');
    }
}


    
}   
    



