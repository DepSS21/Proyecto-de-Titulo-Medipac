<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Módulo A</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Módulo A - Recetas Pendientes</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Receta</th>
                <th>Paciente</th>
                <th>Fecha de Creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($recetas as $receta)
                <tr>
                    <td>{{ $receta->id_receta }}</td>
                    <td>{{ $receta->nombre_paciente }}</td>
                    <td>{{ $receta->fecha_creacion }}</td>
                    <td>
                        <a href="{{ route('mostrar.receta', $receta->id_receta) }}" class="btn btn-info">Ver Detalles</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Botón de volver -->
    <div class="mt-4 text-center">
        <a href="{{ route('farmaceutico.dashboard') }}" class="btn btn-secondary">Volver a Selección de Módulos</a>
    </div>
</div>
</body>
</html>