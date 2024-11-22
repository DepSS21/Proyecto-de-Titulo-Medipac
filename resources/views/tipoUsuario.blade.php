<!DOCTYPE html>
<html lang="en">
<head>
  <title>Tipo Usuario - Medipac</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="{{ asset('css/tipoUsuario.css') }}" rel="stylesheet">
</head>
<body>



<div class="header text-center">
  <h1 class="display-4">Bienvenido a Medipac</h1>
  <p class="lead">Por favor elija el usuario que va a utilizar el sistema</p> 
</div>
  
<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-3 mb-4">
        <div class="card text-center h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Médico</h5>
            <p class="card-text flex-grow-1">Acceso para profesionales médicos. Gestione recetas y consulte información de pacientes.</p>
            <a href="/medico" class="btn btn-custom">Seleccionar</a>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card text-center h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Paciente</h5>
            <p class="card-text flex-grow-1">Portal para pacientes. Acceda a sus recetas y gestione su información médica personal.</p>
            <a href="/paciente" class="btn btn-custom">Seleccionar</a>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card text-center h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Farmacéutico</h5>
            <p class="card-text flex-grow-1">Área de farmacéuticos. Verifique y dispense recetas médicas electrónicas.</p>
            <a href="{{ route('farmaceutico.login.form') }}" class="btn btn-custom">Seleccionar</a>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card text-center h-100">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Admin</h5>
            <p class="card-text flex-grow-1">Acceso para administradores. Gestione usuarios y configuraciones del sistema.</p>
            <a href="{{ route('admin.funciones') }}" class="btn btn-custom">Seleccionar</a>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="container mt-4 text-center">
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">Cerrar sesión</button>
  </form>
</div>

<footer class="footer text-center">
  <div class="container">
    <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
  </div>
</footer>

</body>
</html>