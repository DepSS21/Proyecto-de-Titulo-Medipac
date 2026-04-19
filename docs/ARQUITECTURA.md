# Arquitectura del Sistema — Medipac

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Framework backend | Laravel 11.9 (PHP 8.3+) |
| Base de datos | SQL Server LocalDB (via `pdo_sqlsrv`) |
| Frontend CSS | Tailwind CSS 3 + Bootstrap 5.3 |
| Frontend JS | Alpine.js 3, Axios |
| Build tool | Vite 5 |
| Motor de plantillas | Blade |
| Machine Learning | Python 3 + scikit-learn (K-Means) |
| Autenticación | Laravel Auth (admin) + sesiones PHP (médico/farmacéutico) |

---

## Estructura de carpetas

```
PROYECTODETITULO/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Controladores Breeze (admin)
│   │   │   ├── AdminController.php      # Panel de funciones admin
│   │   │   ├── AuthController.php       # Login/logout admin
│   │   │   ├── FarmaceuticoController.php
│   │   │   ├── InformesController.php
│   │   │   ├── MedicoAuthController.php # Login/logout médico
│   │   │   ├── MedicoController.php     # CRUD médicos
│   │   │   ├── PacienteController.php   # Validación RUT + integración ML
│   │   │   ├── RecetaController.php     # Creación de recetas
│   │   │   └── RecetamodeloController.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── Farmaceutico.php
│   │   ├── Medico.php
│   │   ├── Paciente.php
│   │   ├── Receta.php
│   │   └── User.php
│   └── Providers/
├── database/
│   ├── migrations/                      # Tablas Laravel (users, sessions, cache)
│   ├── scripts/
│   │   └── setup.sql                    # Tablas de la aplicación + datos de prueba
│   └── seeders/
├── docs/                                # Documentación del proyecto
├── python_service/
│   ├── predict_module.py                # Script de predicción ML
│   ├── kmeans.pkl                       # Modelo K-Means serializado
│   └── scaler.pkl                       # StandardScaler serializado
├── resources/
│   ├── css/
│   ├── js/
│   └── views/                           # Plantillas Blade
├── routes/
│   └── web.php                          # Todas las rutas de la aplicación
└── storage/
    └── app/
        └── python_service/
            └── receta_datos.json        # Archivo de comunicación PHP→Python
```

---

## Diagrama de capas

```
┌─────────────────────────────────────────────────────┐
│                    NAVEGADOR                        │
│           Bootstrap 5 + Tailwind + Alpine.js        │
└────────────────────────┬────────────────────────────┘
                         │ HTTP
┌────────────────────────▼────────────────────────────┐
│                 LARAVEL 11 (PHP)                    │
│                                                     │
│  routes/web.php → Controllers → Models → Views      │
│                                                     │
│  Autenticación:                                     │
│  - Admin    → Laravel Auth (tabla users)            │
│  - Médico   → Session::put('medico', ...)           │
│  - Farma    → Session::put('farmaceutico', ...)     │
│  - Paciente → Session::put('paciente_id', ...)      │
└──────────┬──────────────────────────┬───────────────┘
           │ Eloquent / DB::table()   │ exec()
┌──────────▼──────────┐   ┌──────────▼───────────────┐
│  SQL Server LocalDB │   │   Python 3 (ML Service)  │
│                     │   │                          │
│  - Paciente         │   │  predict_module.py       │
│  - Medico           │   │  kmeans.pkl              │
│  - Farmaceutico     │   │  scaler.pkl              │
│  - Receta           │   │                          │
│  - reg_generada     │   │  Input:  JSON (archivo)  │
│  - reg_pendiente    │   │  Output: "A", "B" o "C"  │
│  - reg_entregada    │   │                          │
│  - users            │   └──────────────────────────┘
│  - sessions         │
└─────────────────────┘
```

---

## Comunicación PHP → Python

La integración con el servicio de ML se realiza mediante archivos y `exec()`:

1. PHP escribe los datos del paciente en `storage/app/python_service/receta_datos.json`
2. PHP ejecuta `python.exe predict_module.py` con `exec()`
3. Python lee el JSON, ejecuta la predicción y escribe el resultado en `stdout`
4. PHP captura el `stdout` como resultado (`A`, `B` o `C`)

Este mecanismo es síncrono: el proceso PHP espera a que Python termine antes de continuar.

---

## Sistema de autenticación

El sistema tiene **cuatro mecanismos de autenticación distintos**:

| Rol | Mecanismo | Tabla | Protección de rutas |
|---|---|---|---|
| Admin | `Auth::attempt()` (Laravel) | `users` | Middleware `auth` |
| Médico | `Session::put('medico', ...)` | `Medico` | Verificación manual en controller |
| Farmacéutico | `Session::put('farmaceutico', ...)` | `Farmaceutico` | Verificación manual en controller |
| Paciente | `session(['paciente_id' => ...])` | `Paciente` | Verificación manual en controller |

---

## Estado de una receta

Una receta pasa por los siguientes estados a través de tres tablas distintas:

```
┌──────────────┐    ┌──────────────────────────┐    ┌──────────────────────────┐
│   Receta     │    │  registro_receta_generada │    │  registro_receta_pendiente│
│  (creada por │───▶│  estado: 'Generada'       │───▶│  estado: 'Pendiente'     │
│   médico)    │    │  (visible al paciente)    │    │  modulo: A / B / C (ML)  │
└──────────────┘    └──────────────────────────┘    └──────────┬───────────────┘
                                                                │
                                                   ┌────────────▼───────────────┐
                                                   │  registro_receta_entregada │
                                                   │  estado: 'Entregada'       │
                                                   │  id_farmaceutico           │
                                                   └────────────────────────────┘
```
