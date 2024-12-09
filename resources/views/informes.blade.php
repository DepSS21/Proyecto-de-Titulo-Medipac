<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Informes</h1>



    <iframe title="Medipac" width="1280" height="720" src="https://app.powerbi.com/view?r=eyJrIjoiNzYwMWFjNDYtZDEyZS00MGYyLWIwNGEtODZmMjg2MmQ3NGEzIiwidCI6IjEwMjU1ZDY4LTZmOGYtNDVlMy1iNzRiLWJmY2QxYWYyODNhMiIsImMiOjR9" frameborder="0" allowFullScreen="true"></iframe>
 
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

<footer class="footer text-center">
    <div class="container">
      <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
    </div>
  </footer>

</body>
</html>