<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RecetamodeloController extends Controller
{
    //


public function seleccionarReceta(Request $request)
{
    // Obtener la receta seleccionada
    $receta = Receta::findOrFail($request->receta_id);
    $paciente = $receta->paciente;

    // Prepara los datos para el modelo
    $datos = json_encode([
        "edad" => $paciente->edad,
        "sexo" => $paciente->sexo,
        "diagnostico" => $receta->diagnostico,
    ]);

    // Ejecutar el script de Python y capturar la predicción
    $output = [];
    $returnCode = 0;
    exec("python3 /ruta/a/tu/proyecto/predict_module.py " . escapeshellarg($datos), $output, $returnCode);

    if ($returnCode === 0) {
        $modulo = $output[0];  
        Session::flash('success', "Por favor, dirígete al Módulo $modulo para retirar tu receta.");
    } else {
        Session::flash('error', "Hubo un problema al procesar tu solicitud.");
    }

    return redirect()->back();
}
}
