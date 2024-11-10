<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Funciones de Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Funciones de Admin</h1>
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Crear Médico</h5>
                    <p class="card-text flex-grow-1">Acceso para crear nuevos médicos en el sistema.</p>
                    <a href="{{ route('medico.create') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Crear Farmacéutico</h5>
                    <p class="card-text flex-grow-1">Acceso para crear nuevos farmacéuticos en el sistema.</p>
                    <a href="{{ route('farmaceutico.create') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Informes</h5>
                    <p class="card-text flex-grow-1">Acceso para generar informes y consultas gráficas.</p>
                    <a href="{{ route('informes.show') }}" class="btn btn-primary">Seleccionar</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Botón de volver -->
    <div class="mt-4 text-center">
        <a href="{{ route('tipoUsuario') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>
</body>
</html>