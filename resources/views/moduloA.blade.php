<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Módulo C</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Módulo A - Recetas Pendientes</h1>
        
 
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">¡Receta Entregada!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Receta entregada exitosamente.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>

      
        @if ($recetas->isEmpty())
            <p class="text-center">No hay recetas pendientes en este módulo.</p>
        @else
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
        @endif

        <div class="mt-4 text-center">
            <a href="{{ route('farmaceutico.dashboard') }}" class="btn btn-secondary">Volver a Selección de Módulos</a>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
          <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
        </div>
    </footer>

    <script>
      
        @if (session('success'))
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        @endif
    </script>
</body>
</html>