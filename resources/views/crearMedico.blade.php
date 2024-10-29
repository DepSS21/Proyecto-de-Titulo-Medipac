<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Crear Médico</h1>
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

    <form method="POST" action="{{ route('medico.store') }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese el nombre del médico" required>
        </div>
        <div class="mb-3">
            <label for="rut" class="form-label">RUT:</label>
            <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese el RUT del médico" required>
        </div>
        <div class="mb-3">
            <label for="especialidad" class="form-label">Especialidad:</label>
            <input type="text" class="form-control" id="especialidad" name="especialidad" placeholder="Ingrese la especialidad del médico" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Crear Médico</button>
    </form>

    <!-- Botón para volver a la página de tipoUsuario -->
    <div class="mt-4 text-center">
        <a href="{{ route('tipoUsuario') }}" class="btn btn-secondary">Volver a Tipo de Usuario</a>
    </div>
</div>
</body>
</html>