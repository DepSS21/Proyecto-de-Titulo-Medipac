<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Farmacéutico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Bienvenido, {{ Session::get('farmaceutico')->nombre }}</h1>
    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Módulo A</h5>
                    <p class="card-text flex-grow-1">Descripción del Módulo A.</p>
                    <a href="{{ route('modulo.a') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Módulo B</h5>
                    <p class="card-text flex-grow-1">Descripción del Módulo B.</p>
                    <a href="{{ route('modulo.b') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Módulo C</h5>
                    <p class="card-text flex-grow-1">Descripción del Módulo C.</p>
                    <a href="{{ route('modulo.c') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Botón de cerrar sesión -->
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('farmaceutico.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>
</div>
</body>
</html>