<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mostrar Receta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Detalles de la Receta</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>ID Receta</th>
            <td>{{ $receta->id_receta }}</td>
        </tr>
        <tr>
            <th>Paciente</th>
            <td>{{ $receta->nombre_paciente }}</td>
        </tr>
        <tr>
            <th>Fecha de Creación</th>
            <td>{{ $receta->fecha_creacion }}</td>
        </tr>
        <tr>
            <th>Diagnóstico</th>
            <td>{{ $receta->Diagnostico }}</td>
        </tr>
        <tr>
            <th>Comentarios</th>
            <td>{{ $receta->comentarios }}</td>
        </tr>
    </table>
    <form method="POST" action="{{ route('entregar.receta', $receta->id_receta) }}">
        @csrf
        <button type="submit" class="btn btn-primary">Entregar</button>
    </form>
  
    <div class="mt-4 text-center">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<footer class="footer text-center">
    <div class="container">
      <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
    </div>
  </footer>
</body>
</html>