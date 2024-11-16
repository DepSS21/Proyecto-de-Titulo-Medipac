<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Informes</h1>

    <h1 class="text-center mt-5">Consulta de Recetas Entregadas</h1>
    <form method="GET" action="{{ route('informes.entregadasPorFecha') }}" class="mt-3">
        <div class="mb-3">
            <label for="fecha_entrega" class="form-label">Fecha de Entrega:</label>
            <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" required>
        </div>
        <button type="submit" class="btn btn-success">Consultar Recetas Entregadas</button>
    </form>

    @if(isset($recetasPorFecha))
        <div class="mt-5">
            <h2 class="text-center">Recetas Entregadas el {{ $fechaEntrega }}</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Receta</th>
                        <th>Paciente</th>
                        <th>RUT</th>
                        <th>Fecha de Entrega</th>
                        <th>Estado</th>
                        <th>Diagnóstico</th>
                        <th>Comentario</th>
                        <th>Farmacéutico</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recetasPorFecha as $receta)
                        <tr>
                            <td>{{ $receta->id_receta }}</td>
                            <td>{{ $receta->nombre }} {{ $receta->apellido }}</td>
                            <td>{{ $receta->rut_paciente }}</td>
                            <td>{{ $receta->fecha_registro }}</td>
                            <td>{{ $receta->estado_receta }}</td>
                            <td>{{ $receta->diagnostico }}</td>
                            <td>{{ $receta->comentarios }}</td>
                            <td>{{ $receta->nombre_farmaceutico }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Botón de volver -->
    <div class="mt-4 text-center">
        <a href="{{ route('admin.funciones') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
</body>
</html>