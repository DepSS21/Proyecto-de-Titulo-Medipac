<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="{{ asset('css/fondo.css') }}" rel="stylesheet">
  <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
  <div class="container-fluid p-5 bg-primary text-white text-center">
    <h1>Bienvenido a Medipac</h1>
    <p>Ingrese sus credenciales de Administrador para iniciar el programa</p>
  </div>

  <div class="container mt-3">
    <h2>Ingrese su credencial de Administrador</h2>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="mb-3 mt-3">
        <label for="user">User:</label>
        <input type="text" class="form-control" id="user" placeholder="Enter User" name="user" value="{{ old('user') }}">
        @if ($errors->has('user'))
        <div class="alert alert-danger mt-2">
          {{ $errors->first('user') }}
        </div>
        @endif
      </div>
      <div class="mb-3">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

  <footer class="footer text-center">
    <div class="container">
      <span>&copy; 2024 Medipac. Todos los derechos reservados.</span>
    </div>
  </footer>
</body>
</html>
