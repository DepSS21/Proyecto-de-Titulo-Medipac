# Base de Datos — Medipac

Motor: **SQL Server LocalDB** (instancia `(localdb)\MSSQLLocalDB`)
Base de datos: `proyecto`

El script de creación completo está en `database/scripts/setup.sql`.

---

## Diagrama de relaciones

```
┌─────────────┐         ┌──────────────┐         ┌───────────────┐
│   Paciente  │◄────┐   │    Receta    │   ┌────►│    Medico     │
│─────────────│     │   │──────────────│   │     │───────────────│
│ id_paciente │     └───│ id_paciente  │   │     │ id_medico     │
│ rut_paciente│         │ id_medico    │───┘     │ rut           │
│ nombre      │         │ fecha_creacion│         │ nombre        │
│ apellido    │         │ Diagnostico  │         │ especialidad  │
│ edad        │         │ comentarios  │         └───────────────┘
│ sexo        │         └──────┬───────┘
│ numero_serie│                │ id_receta
└─────────────┘                │
                    ┌──────────┼──────────────────────────┐
                    │          │                          │
          ┌─────────▼──────┐  ┌▼────────────────┐  ┌─────▼──────────────┐
          │ reg_generada   │  │ reg_pendiente   │  │ reg_entregada      │
          │────────────────│  │─────────────────│  │────────────────────│
          │ id_receta      │  │ id_receta       │  │ id_receta          │
          │ estado_receta  │  │ fecha_registro  │  │ fecha_registro     │
          │ ('Generada')   │  │ estado_receta   │  │ estado_receta      │
          └────────────────┘  │ modulo (A/B/C)  │  │ id_farmaceutico ──►│ Farmaceutico
                              └─────────────────┘  └────────────────────┘
```

---

## Tablas de la aplicación

### `Paciente`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id_paciente` | INT IDENTITY | PK | Identificador único |
| `rut_paciente` | VARCHAR(20) | NO | RUT sin puntos, con guión (ej: `12345678-9`) |
| `nombre` | VARCHAR(100) | NO | Nombre del paciente |
| `apellido` | VARCHAR(100) | NO | Apellido del paciente |
| `edad` | INT | NO | Edad en años (usada por el modelo ML) |
| `fecha_nacimiento` | DATE | SI | Fecha de nacimiento |
| `condicion_medica` | VARCHAR(255) | SI | Diagnóstico previo general |
| `sexo` | CHAR(1) | NO | `M` o `F` (usado por el modelo ML) |
| `numero_serie` | VARCHAR(20) | NO | Código de verificación; los **últimos 3 caracteres** son el PIN |

---

### `Medico`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id_medico` | INT IDENTITY | PK | ID que usa el médico para autenticarse |
| `rut` | VARCHAR(20) | NO | RUT del médico |
| `nombre` | VARCHAR(100) | NO | Nombre completo |
| `especialidad` | VARCHAR(100) | NO | Especialidad médica |

---

### `Farmaceutico`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id_farmaceutico` | INT IDENTITY | PK | ID que usa el farmacéutico para autenticarse |
| `nombre` | VARCHAR(100) | NO | Nombre completo |
| `rut_farmaceutico` | VARCHAR(20) | NO | RUT del farmacéutico |

---

### `Receta`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id_receta` | INT IDENTITY | PK | Identificador único |
| `fecha_creacion` | DATETIME | NO | Fecha y hora de creación |
| `Diagnostico` | VARCHAR(100) | NO | Diagnóstico en texto (debe coincidir exactamente con el mapeo ML) |
| `comentarios` | VARCHAR(500) | SI | Indicaciones adicionales del médico |
| `id_medico` | INT | NO | FK → `Medico.id_medico` |
| `id_paciente` | INT | NO | FK → `Paciente.id_paciente` |

> **Importante:** El campo `Diagnostico` debe contener exactamente uno de los 26 diagnósticos reconocidos por el modelo ML. Ver [Machine Learning](MACHINE_LEARNING.md) para la lista completa.

---

### `registro_receta_generada`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id` | INT IDENTITY | PK | Identificador |
| `id_receta` | INT | NO | FK → `Receta.id_receta` |
| `estado_receta` | VARCHAR(50) | NO | Siempre `'Generada'` |

Esta tabla actúa como habilitador: una receta solo es visible para el paciente si tiene un registro aquí. Debe crearse manualmente al insertar una receta (no hay trigger automático).

---

### `registro_receta_pendiente`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id` | INT IDENTITY | PK | Identificador |
| `id_receta` | INT | NO | FK → `Receta.id_receta` |
| `fecha_registro` | DATETIME | NO | Momento en que el paciente seleccionó la receta |
| `estado_receta` | VARCHAR(50) | NO | `'Pendiente'` |
| `modulo` | CHAR(1) | NO | Módulo asignado por el modelo ML: `A`, `B` o `C` |

---

### `registro_receta_entregada`

| Columna | Tipo | Nulo | Descripción |
|---|---|---|---|
| `id` | INT IDENTITY | PK | Identificador |
| `id_receta` | INT | NO | FK → `Receta.id_receta` |
| `fecha_registro` | DATETIME | NO | Momento de entrega |
| `estado_receta` | VARCHAR(50) | NO | `'Entregada'` |
| `id_farmaceutico` | INT | NO | FK → `Farmaceutico.id_farmaceutico` |

---

## Tablas de Laravel

Gestionadas por las migraciones de Laravel (`php artisan migrate`):

| Tabla | Descripción |
|---|---|
| `users` | Usuarios administradores |
| `sessions` | Sesiones de usuario (driver: database) |
| `cache` | Caché del sistema (driver: database) |
| `cache_locks` | Locks para caché |
| `jobs` | Cola de trabajos |
| `job_batches` | Lotes de trabajos |
| `failed_jobs` | Trabajos fallidos |
| `migrations` | Historial de migraciones |
| `password_reset_tokens` | Tokens de recuperación de contraseña |

---

## Datos de prueba

El archivo `database/scripts/setup.sql` incluye los siguientes datos de prueba:

### Pacientes

| RUT | Nombre | Sexo | Edad | Diagnóstico | PIN (últimos 3 de serie) |
|---|---|---|---|---|---|
| `12345678-9` | Juan Pérez | M | 45 | hipertension | `123` |
| `98765432-1` | María González | F | 62 | diabetes | `456` |
| `11111111-1` | Carlos López | M | 35 | asma | `789` |
| `22222222-2` | Ana Martínez | F | 70 | artritis | `012` |
| `33333333-3` | Pedro Soto | M | 55 | colesterol alto | `345` |

### Médicos

| ID | Nombre | Especialidad |
|---|---|---|
| 1 | Dr. Roberto Silva | Medicina General |
| 2 | Dra. Carmen Rojas | Cardiología |
| 3 | Dr. Andrés Muñoz | Endocrinología |

### Farmacéuticos

| ID | Nombre |
|---|---|
| 1 | Luis Farma |
| 2 | Paula Campos |

### Usuario administrador

| Campo | Valor |
|---|---|
| user | `admin` |
| password | `admin123` |
