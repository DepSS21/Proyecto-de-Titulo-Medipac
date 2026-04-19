# Flujos de Usuario — Medipac

## Resumen de roles

| Rol | URL de entrada | Credencial |
|---|---|---|
| Administrador | `/` | user: `admin` / password: `admin123` |
| Médico | `/medico/login` | ID numérico del médico |
| Paciente | `/paciente` | RUT + últimos 3 dígitos del número de serie |
| Farmacéutico | `/farmaceutico/login` | ID numérico del farmacéutico |

---

## Flujo 1: Administrador

```
GET /
└── login.blade.php (formulario user + password)
    │
    POST /login
    └── AuthController@login
        ├── Auth::attempt() contra tabla users
        ├── FALLO → vuelve a / con mensaje de error
        └── ÉXITO → redirect /tipo-usuario
                    tipoUsuario.blade.php (4 opciones)
                    │
                    ├── Médico ──────────────────────► /medico/login
                    ├── Paciente ───────────────────► /paciente
                    ├── Farmacéutico ───────────────► /farmaceutico/login
                    └── Admin ──────────────────────► /admin/funciones
                                                       adminFunciones.blade.php
                                                       │
                                                       ├── Crear Médico ──► /medico/create
                                                       ├── Crear Farmacéutico ► /farmaceutico/create
                                                       └── Informes ──────► /admin/informes
```

### Crear médico (admin)
```
GET /medico/create → crearMedico.blade.php
└── POST /medico/store (nombre, rut, especialidad)
    └── MedicoController@store
        ├── Valida campos (rut único en tabla Medico)
        └── Medico::create() → redirect /medico/create con éxito
```

### Crear farmacéutico (admin)
```
GET /farmaceutico/create → crearFarmaceutico.blade.php
└── POST /farmaceutico/store (nombre, rut_farmaceutico)
    └── FarmaceuticoController@store
        ├── Valida campos (rut_farmaceutico único)
        └── DB::table('Farmaceutico')->insert() → redirect con éxito
```

### Ver informes (admin)
```
GET /admin/informes → informes.blade.php
└── Opciones disponibles:
    ├── GET /admin/informes/entregadas-por-fecha?fecha_entrega=YYYY-MM-DD
    │   └── InformesController@entregadasPorFecha
    │       └── Lista recetas entregadas en esa fecha con datos completos
    └── GET /admin/informes/generar?consulta=entregadas&fecha=YYYY-MM-DD
        └── InformesController@generarInforme
            └── Retorna conteo de recetas
```

---

## Flujo 2: Médico

```
GET /medico/login → loginMedico.blade.php (campo ID médico)
│
POST /medico/login (medicoId)
└── MedicoAuthController@login
    ├── Busca Medico::where('id_medico', $medicoId)
    ├── NO ENCONTRADO → vuelve a /medico/login con error
    └── ENCONTRADO → Session::put('medico', $medico)
                     redirect /receta/form

GET /receta/form → RecetaController@showRecetaForm
└── medico.blade.php
    ├── Muestra datos del médico (readonly)
    ├── Campo búsqueda RUT paciente
    └── [Buscar Paciente]
        │
        GET /buscar-paciente?rut=XXXXXXXX-X
        └── RecetaController@buscarPaciente
            ├── NO ENCONTRADO → vuelve con error "Paciente no encontrado"
            └── ENCONTRADO → recarga vista con datos del paciente

    Médico completa: diagnóstico (max 20 chars) + comentario
    │
    POST /receta/store (diagnostico, comentario, rutPaciente)
    └── RecetaController@store
        ├── Valida campos
        ├── Obtiene médico de sesión
        ├── Busca paciente por RUT
        ├── Crea Receta: fecha=now(), Diagnostico, comentarios, id_medico, id_paciente
        ├── NOTA: Se debe insertar manualmente en registro_receta_generada
        └── redirect /receta/form con éxito

POST /medico/logout → Session::forget('medico') → redirect /medico/login
```

> **Nota:** Actualmente al crear una receta no se inserta automáticamente en `registro_receta_generada`. Esto debe hacerse manualmente o agregar la inserción en `RecetaController@store`.

---

## Flujo 3: Paciente

```
GET /paciente → paciente.blade.php
└── Teclado numérico en pantalla
    ├── Campo RUT (se formatea automáticamente: 12.345.678-9)
    └── Campo Número de Serie (exactamente 3 caracteres)

POST /validar-rut (rut, numeroSerie)
└── PacienteController@validarRut
    ├── Limpia RUT: quita puntos, mayúsculas → "12345678-9"
    ├── Busca Paciente::where('rut_paciente', $rut)
    ├── Verifica: substr($paciente->numero_serie, -3) === $numeroSerie
    ├── FALLO → vuelve a /paciente con error
    └── ÉXITO → session(['paciente_id' => $id]) → redirect /paciente/recetas

GET /paciente/recetas → PacienteController@mostrarRecetas
└── mostrarRecetas.blade.php
    ├── Lista recetas que:
    │   ✓ Están en registro_receta_generada
    │   ✗ NO están en registro_receta_pendiente (estado='Pendiente')
    │   ✗ NO están en registro_receta_entregada
    └── Botón "Seleccionar para Retiro" por cada receta

POST /paciente/seleccionar-receta (receta_id)
└── PacienteController@seleccionarReceta
    ├── Valida que receta_id exista en tabla Receta
    ├── Verifica que receta no esté ya en estado Pendiente
    ├── Obtiene: receta (Diagnostico), paciente (edad, sexo)
    ├── Mapea diagnóstico texto → número (26 opciones)
    ├── Si diagnóstico no reconocido → error "Diagnóstico no reconocido"
    ├── Prepara JSON: {diagnostico: N, edad: N, sexo: 1|2}
    ├── Guarda en storage/app/python_service/receta_datos.json
    ├── exec("python.exe -W ignore predict_module.py")
    │   ├── Python predice → imprime "A", "B" o "C"
    │   └── PHP captura stdout
    ├── INSERT registro_receta_pendiente (modulo=A|B|C, estado='Pendiente')
    └── redirect /paciente/recetas con "Dirígete al Módulo A"

Paciente se dirige físicamente al módulo indicado y espera.
```

---

## Flujo 4: Farmacéutico

```
GET /farmaceutico/login → loginFarmaceutico.blade.php (campo ID farmacéutico)
│
POST /farmaceutico/login (farmaceuticoId)
└── FarmaceuticoController@login
    ├── DB::table('Farmaceutico')->where('id_farmaceutico', $id)
    ├── NO ENCONTRADO → vuelve con error
    └── ENCONTRADO → Session::put('farmaceutico', $farmaceutico)
                     redirect /farmaceutico/dashboard

GET /farmaceutico/dashboard → dashboardFarmaceutico.blade.php
└── 3 tarjetas: Módulo A | Módulo B | Módulo C

GET /modulo/a → FarmaceuticoController@moduloA
└── moduloA.blade.php
    ├── Consulta:
    │   registro_receta_pendiente (modulo='A', estado='Pendiente')
    │   JOIN Receta JOIN Paciente
    │   LEFT JOIN registro_receta_entregada (excluye ya entregadas)
    └── Lista recetas con: nombre paciente, diagnóstico
        └── Botón "Ver Detalles" por receta

GET /receta/{id} → FarmaceuticoController@mostrarReceta
└── mostrarReceta.blade.php
    ├── Datos completos: paciente, diagnóstico, comentarios, fecha
    └── Botón "Entregar Receta"

POST /entregar-receta/{id} → FarmaceuticoController@entregarReceta
├── INSERT registro_receta_entregada:
│   fecha_registro=now(), estado='Entregada',
│   id_receta={id}, id_farmaceutico=session.farmaceutico.id
├── Obtiene módulo de registro_receta_pendiente
└── redirect /modulo/a (o b o c) con éxito

POST /farmaceutico/logout → Session::forget('farmaceutico') → redirect /farmaceutico/login
```

---

## Pantalla de selección de tipo de usuario

La vista `tipoUsuario.blade.php` en `/tipo-usuario` es el hub central post-login del administrador. Permite al administrador navegar hacia cualquier sección del sistema.

Los demás usuarios (médico, paciente, farmacéutico) acceden directamente a sus URLs sin pasar por esta pantalla.
