<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Farmacéutico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Login Farmacéutico</h1>
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form method="POST" action="{{ route('farmaceutico.login') }}">
        @csrf
        <div class="mb-3 mt-3">
            <label for="farmaceuticoId">ID Farmacéutico:</label>
            <input type="text" class="form-control" id="farmaceuticoId" name="farmaceuticoId" placeholder="Ingrese su ID de Farmacéutico">
            @if ($errors->has('farmaceuticoId'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('farmaceuticoId') }}
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