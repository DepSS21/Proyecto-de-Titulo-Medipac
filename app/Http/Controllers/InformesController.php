<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformesController extends Controller
{
    public function show()
    {
        return view('informes');
    }

    public function generarInforme(Request $request)
    {
        $consulta = $request->input('consulta');
        $fecha = $request->input('fecha');

        if ($consulta == 'entregadas') {
            // Consultar la cantidad de recetas entregadas en la fecha especificada
            $recetasEntregadas = DB::table('registro_receta_entregada')
                ->whereDate('fecha_registro', $fecha)
                ->count();

            return view('informes', compact('recetasEntregadas', 'fecha'));
        } elseif ($consulta == 'pendientes') {
            // Consultar la cantidad de recetas pendientes por entregar
            $recetasPendientes = DB::table('registro_receta')
                ->where('estado_receta', 'pendiente')
                ->count();

            return view('informes', compact('recetasPendientes', 'fecha'));
        }

        return view('informes');
    }

    public function buscarReceta(Request $request)
    {
        $idReceta = $request->input('id_receta');

        // Buscar la receta por ID
        $receta = DB::table('registro_receta')
            ->where('id_receta', $idReceta)
            ->first();

        if ($receta) {
            // Obtener el historial de estados de la receta
            $historialEstados = DB::table('registro_receta')
                ->where('id_receta', $idReceta)
                ->orderBy('fecha_registro', 'asc')
                ->get();

            return view('informes', compact('receta', 'historialEstados'));
        } else {
            $mensaje = "No se encontró ninguna receta con el ID proporcionado.";
            return view('informes', compact('mensaje'));
        }
    }

    public function entregadasPorFecha(Request $request)
    {
        $fechaEntrega = $request->input('fecha_entrega');

        // Consultar las recetas entregadas en la fecha especificada
        $recetasPorFecha = DB::table('registro_receta_entregada')
            ->join('Receta', 'registro_receta_entregada.id_receta', '=', 'Receta.id_receta')
            ->join('Paciente', 'Receta.id_paciente', '=', 'Paciente.id_paciente')
            ->whereDate('registro_receta_entregada.fecha_registro', $fechaEntrega)
            ->select('Receta.id_receta', 'Paciente.nombre as nombre_paciente', 'registro_receta_entregada.fecha_registro', 'registro_receta_entregada.estado_receta')
            ->get();

        return view('informes', compact('recetasPorFecha', 'fechaEntrega'));
    }
}