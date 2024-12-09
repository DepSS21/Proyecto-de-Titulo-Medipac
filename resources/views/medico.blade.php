<!DOCTYPE html>
<html lang="en">
<head>
  <title>Generar Receta</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
  <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Configurar la fecha de creación para que sea la fecha de hoy
      const fechaCreacionInput = document.getElementById('fechaCreacion');
      const today = new Date().toISOString().split('T')[0];
      fechaCreacionInput.value = today;
    });

    function buscarPaciente() {
      const rutPaciente = document.getElementById('rutPaciente').value;
      if (!rutPaciente) {
        alert('Por favor, ingrese el RUT del paciente.');
        return;
      }
      window.location.href = `/buscar-paciente?rut=${rutPaciente}`;
    }
  </script>
</head>
<body>

<div class="container mt-5">
  <h1 class="text-center">Generar Receta</h1>
  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif
  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  
  <form method="POST" action="{{ route('medico.logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
  </form>

  <form method="POST" action="{{ route('receta.store') }}">
    @csrf
    <div class="mb-3">
      <label for="idReceta" class="form-label">Id Receta:</label>
      <input type="text" class="form-control" id="idReceta" name="idReceta" value="{{ $nextRecetaId }}" readonly>
    </div>
    <h3>Información del Médico</h3>
    <div class="mb-3">
      <label for="nombreMedico" class="form-label">Nombre Médico:</label>
      <input type="text" class="form-control" id="nombreMedico" name="nombreMedico" value="{{ $medico->nombre }}" readonly>
    </div>
    <div class="mb-3">
      <label for="rutMedico" class="form-label">RUT Médico:</label>
      <input type="text" class="form-control" id="rutMedico" name="rutMedico" value="{{ $medico->rut }}" readonly>
    </div>
    <div class="mb-3">
      <label for="especialidadMedico" class="form-label">Especialidad Médico:</label>
      <input type="text" class="form-control" id="especialidadMedico" name="especialidadMedico" value="{{ $medico->especialidad }}" readonly>
    </div>
    <h3>Información del Paciente</h3>
    <div class="mb-3">
      <label for="rutPaciente" class="form-label">Rut Paciente:</label>
      <input type="text" class="form-control" id="rutPaciente" name="rutPaciente" placeholder="Ingrese el Rut del Paciente" value="{{ $rut ?? '' }}">
      <button type="button" class="btn btn-secondary mt-2" onclick="buscarPaciente()">Buscar Paciente</button>
    </div>
    <div class="mb-3">
      <label for="nombrePaciente" class="form-label">Nombre:</label>
      <input type="text" class="form-control" id="nombrePaciente" name="nombrePaciente" value="{{ $paciente->nombre ?? '' }}" readonly>
    </div>
    <div class="mb-3">
      <label for="edadPaciente" class="form-label">Edad:</label>
      <input type="text" class="form-control" id="edadPaciente" name="edadPaciente" value="{{ $paciente->edad ?? '' }}" readonly>
    </div>
    <div class="mb-3">
      <label for="sexoPaciente" class="form-label">Sexo:</label>
      <input type="text" class="form-control" id="sexoPaciente" name="sexoPaciente" value="{{ $paciente->sexo ?? '' }}" readonly>
    </div>
    <div class="mb-3">
      <label for="fechaNacimientoPaciente" class="form-label">Fecha Nacimiento:</label>
      <input type="text" class="form-control" id="fechaNacimientoPaciente" name="fechaNacimientoPaciente" value="{{ $paciente->fecha_nacimiento ?? '' }}" readonly>
    </div>
    <div class="mb-3">
      <label for="condicionMedicaPaciente" class="form-label">Condición Médica:</label>
      <input type="text" class="form-control" id="condicionMedicaPaciente" name="condicionMedicaPaciente" value="{{ $paciente->condicion_medica ?? '' }}" readonly>
    </div>
    <h3>Información de la Receta</h3>
    <div class="mb-3">
      <label for="fechaCreacion" class="form-label">Fecha de Creación:</label>
      <input type="date" class="form-control" id="fechaCreacion" name="fechaCreacion" readonly>
    </div>
    <div class="mb-3">
      <label for="diagnostico" class="form-label">Diagnóstico:</label>
      <input type="text" class="form-control" id="diagnostico" name="diagnostico" placeholder="Ingrese el Diagnóstico">
    </div>
    <div class="mb-3">
      <label for="comentario" class="form-label">Comentario:</label>
      <textarea class="form-control" id="comentario" name="comentario" rows="3" placeholder="Ingrese el Medicamento y la Dosis"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Generar Receta</button> 
  </form>
</div>
<footer class="footer text-center">
  <div class="container">
    <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
  </div>
</footer>


</body>
</html>