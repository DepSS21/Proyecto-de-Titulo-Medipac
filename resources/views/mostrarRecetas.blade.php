<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mis Recetas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Mis Recetas</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Información del Paciente</h3>
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $paciente->nombre }} {{ $paciente->apellido }}</p>
            <p><strong>RUT:</strong> {{ $paciente->rut_paciente }}</p>
        </div>
    </div>

    @if($paciente->recetas->isEmpty())
        <div class="alert alert-info">
            No tienes recetas registradas.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Médico</th>
                        <th>Diagnóstico</th>
                        <th>Comentarios</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recetas as $receta)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($receta->fecha_creacion)->format('d/m/Y') }}</td>
                            <td>{{ $receta->nombre_medico }}</td>
                            <td>{{ $receta->Diagnostico }}</td>
                            <td>{{ $receta->comentarios }}</td>
                            <td>{{ $receta->estado_receta }}</td>
                            <td>
                                @if($receta->estado_receta === 'Generado')
                                    <form action="{{ route('paciente.seleccionar.receta') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="receta_id" value="{{ $receta->id_receta }}">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            Seleccionar para Retiro
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-info">Pendiente de Retiro</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ url('/paciente') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>


<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ url('/paciente') }}" class="btn btn-primary">Aceptar</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success') || session('error'))
            let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
            alertModal.show();
        @endif
    });
</script>


<footer class="footer text-center">
    <div class="container">
      <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
    </div>
  </footer>

</body>
</html>
