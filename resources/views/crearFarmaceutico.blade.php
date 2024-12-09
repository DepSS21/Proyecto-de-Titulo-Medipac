<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Farmacéutico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Crear Farmacéutico</h1>
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

    <form method="POST" action="{{ route('farmaceutico.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del farmacéutico" required>
        </div>
        <div class="mb-3">
            <label for="rut_farmaceutico" class="form-label">RUT:</label>
            <input type="text" class="form-control" id="rut_farmaceutico" name="rut_farmaceutico" placeholder="Ingrese el RUT del farmacéutico" required>
        </div>
        <button type="submit" class="btn btn-primary">Crear Farmacéutico</button>
    </form>

   
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