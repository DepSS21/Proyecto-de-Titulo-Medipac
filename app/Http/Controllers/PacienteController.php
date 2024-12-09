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

    // Obtener los id_receta que ya han sido entregadas
    $recetasEntregadas = DB::table('registro_receta_entregada')
        ->pluck('id_receta')
        ->toArray();

    // Obtener los id_receta que están pendientes
    $recetasPendientes = DB::table('registro_receta_pendiente')
        ->where('estado_receta', 'Pendiente')
        ->pluck('id_receta')
        ->toArray();

    // Combinar las recetas entregadas y pendientes
    $recetasExcluidas = array_merge($recetasEntregadas, $recetasPendientes);
    // Obtener las recetas generadas excluyendo las que están en pendiente o entregadas
    $recetas = DB::table('Receta')
    ->join('Medico', 'Receta.id_medico', '=', 'Medico.id_medico')
    ->join('registro_receta_generada', 'Receta.id_receta', '=', 'registro_receta_generada.id_receta')
    ->whereNotIn('Receta.id_receta', $recetasExcluidas) 
    ->where('Receta.id_paciente', $paciente_id)
    ->select(
        'Receta.*',
        'Medico.nombre as nombre_medico',
        'registro_receta_generada.estado_receta as estado_receta'
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
        $registroExistente = DB::table('registro_receta_pendiente')
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
            'diabetes con' => 45,
            'hipertensión con' => 1,
            'asma con' => 2,
            'enfermedad cardíaca' => 3,
            'alergia severa' => 4,
            'colesterol alto' => 5,
            'artritis' => 6,
            'enfermedad renal crónica' => 7,
            'cáncer' => 8,
            'migraña' => 9,
            'depresión severa' => 10,
            'ansiedad generalizada' => 11,
            'hepatitis' => 12,
            'alzheimer' => 13,
            'fibromialgia' => 14,
            'esclerosis múltiple' => 15,
            'anemia severa' => 16,
            'obesidad severa' => 17,
            'insuficiencia respiratoria' => 18,
            'epilepsia' => 19,
            'osteoporosis' => 20,
            'cirrosis' => 21,
            'hipotiroidismo' => 22,
            'parkinson' => 23,
            'lupus' => 24,
            'hipertension' => 25,
            'diabetes' => 26
        ];
    
        // Verificar si el diagnóstico está en el diccionario
        if (!array_key_exists($diagnostico, $enfermedades_dummies)) {
            return redirect()->back()->with('error', 'Diagnóstico no reconocido en el sistema.');
        }
    
        // Mapea el diagnóstico a la cadena de texto correspondiente
        $diagnostico_transformado = $enfermedades_dummies[$diagnostico];
    
        // Preparar los datos para el modelo
        $datos = [
            "diagnostico" => $diagnostico_transformado, // Mapeo de diagnóstico
            "edad" => (int)$paciente->edad, // Edad del paciente
            "sexo" => ($paciente->sexo == 'F' ? 2 : 1) // Sexo del paciente (1 para M, 2 para F)
        ];
    
        // Convertir a JSON
        $datosJson = json_encode($datos, JSON_UNESCAPED_UNICODE);
    
        // Loguear los datos antes de generar el archivo
        Log::info('Datos para archivo JSON: ' . $datosJson);
    
        // Crear el archivo JSON en la carpeta python_service
        $path = storage_path('app/python_service/receta_datos.json'); 
        file_put_contents($path, $datosJson); 
    
      
        $comando = "python C:/xampp/htdocs/Laravel/proyecto-app/python_service/predict_module.py \"$datosJson\"";
        exec($comando, $output, $returnCode);
    
        Log::info('Salida del script de Python:', ['output' => $output, 'returnCode' => $returnCode]);
    
        if ($returnCode === 0) {
            $modulo = trim($output[0]);
            DB::table('registro_receta_pendiente')->insert([
            'fecha_registro' => now(),
            'estado_receta' => 'Pendiente',
            'id_receta' => $request->receta_id,
            'modulo' => $modulo
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