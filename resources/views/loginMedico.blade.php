<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login Médico</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
  <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>

<div class="container-fluid p-5 bg-primary text-white text-center">
  <h1>Login Médico</h1>
  <p>Ingrese su ID de Médico para autenticarse</p> 
</div>
  
<div class="container mt-3">
  <h2>Ingrese su ID de Médico</h2>
  <form method="POST" action="{{ route('medico.login') }}">
    @csrf
    <div class="mb-3 mt-3">
      <label for="medicoId">ID Médico:</label>
      <input type="text" class="form-control" id="medicoId" name="medicoId" placeholder="Ingrese su ID de Médico">
      @if ($errors->has('medicoId'))
        <div class="alert alert-danger mt-2">
          {{ $errors->first('medicoId') }}
        </div>
      @endif
    </div>
    <button type="submit" class="btn btn-primary">Ingresar</button>
  </form>

  <!-- Botón de volver -->
  <a href="{{ route('tipoUsuario') }}" class="btn btn-secondary mt-3">Volver</a>
</div>

<footer class="footer text-center">
  <div class="container">
    <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
  </div>
</footer>


</body>
</html>