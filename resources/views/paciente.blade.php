<!DOCTYPE html>
<html lang="en">
<head>
  <title>Ingresar RUT</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let focusedInput = null;

    document.addEventListener('DOMContentLoaded', function() {
      const rutInput = document.getElementById('rut');
      const numeroSerieInput = document.getElementById('numeroSerie');

      rutInput.addEventListener('focus', function() {
        focusedInput = rutInput;
      });

      numeroSerieInput.addEventListener('focus', function() {
        focusedInput = numeroSerieInput;
      });

      rutInput.addEventListener('input', function() {
        this.value = formatearRut(this.value);
      });

      numeroSerieInput.addEventListener('input', function() {
        if (this.value.length > 3) {
          this.value = this.value.slice(0, 3);
        }
      });
    });

    function validarRut() {
      let rut = document.getElementById('rut').value;
      rut = rut.replace(/\./g, '').replace(/-/g, '');
      const rutRegex = /^[0-9]+[0-9kK]{1}$/;
      if (!rut) {
        alert('Por favor, ingrese su RUT.');
        return false;
      }
      if (!rutRegex.test(rut)) {
        alert('RUT inválido. Por favor, ingrese un RUT válido.');
        return false;
      }
      return true;
    }

    function formatearRut(rut) {
      rut = rut.replace(/\./g, '').replace(/-/g, '');
      if (rut.length > 1) {
        rut = rut.slice(0, -1) + '-' + rut.slice(-1);
      }
      return rut.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function agregarCaracter(caracter) {
      if (focusedInput) {
        focusedInput.value += caracter;
        if (focusedInput.id === 'rut') {
          focusedInput.value = formatearRut(focusedInput.value);
        }
        if (focusedInput.id === 'numeroSerie' && focusedInput.value.length > 3) {
          focusedInput.value = focusedInput.value.slice(0, 3);
        }
      }
    }

    function borrarCaracter() {
      if (focusedInput) {
        focusedInput.value = focusedInput.value.slice(0, -1);
        if (focusedInput.id === 'rut') {
          focusedInput.value = formatearRut(focusedInput.value);
        }
      }
    }
  </script>
  <style>
    .keypad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      max-width: 300px;
      margin: 20px auto;
    }
    .keypad button {
      padding: 20px;
      font-size: 1.5rem;
    }
  </style>
</head>
<body>

  <div class="container mt-5">
    <h1 class="text-center">Ingresar RUT</h1>
    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif
    <form method="POST" action="{{ route('validar.rut') }}" onsubmit="return validarRut()">
      @csrf
      <div class="mb-3">
        <label for="rut" class="form-label">RUT:</label>
        <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese su RUT (ej. 12.345.678-9)" readonly>
      </div>
      <div class="mb-3">
        <label for="numeroSerie" class="form-label">Número de Serie:</label>
        <input type="text" class="form-control" id="numeroSerie" name="numeroSerie" placeholder="Ingrese los últimos 3 dígitos de su Número de Serie" value="{{ old('numeroSerie') }}" maxlength="3">
      </div>
      <div class="keypad">
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('1')">1</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('2')">2</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('3')">3</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('4')">4</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('5')">5</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('6')">6</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('7')">7</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('8')">8</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('9')">9</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('0')">0</button>
        <button type="button" class="btn btn-secondary" onclick="agregarCaracter('k')">K</button>
        <button type="button" class="btn btn-secondary" onclick="borrarCaracter()">←</button>
      </div>
      <button type="submit" class="btn btn-primary">Siguiente</button>
    </form>
  </div>
  
</body>
</html>