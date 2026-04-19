# Rutas HTTP — Medipac

Todas las rutas están definidas en `routes/web.php`.

---

## Autenticación — Administrador

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/` | `AuthController@showLoginForm` | `login.form` | Formulario login admin |
| GET | `/login` | `AuthController@showLoginForm` | — | Alias de `/` |
| POST | `/login` | `AuthController@login` | `login` | Procesar login admin |
| POST | `/logout` | `AuthController@logout` | `logout` | Cerrar sesión admin |

---

## Navegación central

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/tipo-usuario` | — | `tipoUsuario` | Selección de tipo de usuario |
| GET | `/dashboard` | — | `dashboard` | Dashboard (protegido con middleware `auth`) |
| GET | `/admin/funciones` | `AdminController@showFunciones` | `admin.funciones` | Panel de funciones admin |

---

## Médico — Autenticación

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/medico/login` | `MedicoAuthController@showLoginForm` | `medico.login.form` | Formulario login médico |
| POST | `/medico/login` | `MedicoAuthController@login` | `medico.login` | Procesar login médico |
| POST | `/medico/logout` | `MedicoAuthController@logout` | `medico.logout` | Cerrar sesión médico |

## Médico — Gestión

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/medico` | `RecetaController@showRecetaForm` | `medico` | Formulario crear receta |
| GET | `/receta/form` | `RecetaController@showRecetaForm` | `receta.form` | Alias formulario receta |
| GET | `/buscar-paciente` | `RecetaController@buscarPaciente` | `buscar.paciente` | Buscar paciente por RUT |
| POST | `/receta/store` | `RecetaController@store` | `receta.store` | Guardar nueva receta |
| GET | `/medico/create` | — | `medico.create` | Formulario crear médico (admin) |
| POST | `/medico/store` | `MedicoController@store` | `medico.store` | Guardar nuevo médico |

---

## Paciente

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/paciente` | — | — | Formulario ingreso RUT |
| POST | `/validar-rut` | `PacienteController@validarRut` | `validar.rut` | Validar RUT + número de serie |
| GET | `/paciente/recetas` | `PacienteController@mostrarRecetas` | `paciente.recetas` | Listar recetas del paciente |
| POST | `/paciente/seleccionar-receta` | `PacienteController@seleccionarReceta` | `paciente.seleccionar.receta` | Seleccionar receta (activa ML) |

---

## Farmacéutico — Autenticación

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/farmaceutico/login` | `FarmaceuticoController@showLoginForm` | `farmaceutico.login.form` | Formulario login farmacéutico |
| POST | `/farmaceutico/login` | `FarmaceuticoController@login` | `farmaceutico.login` | Procesar login farmacéutico |
| POST | `/farmaceutico/logout` | `FarmaceuticoController@logout` | `farmaceutico.logout` | Cerrar sesión farmacéutico |

## Farmacéutico — Gestión

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/farmaceutico/dashboard` | `FarmaceuticoController@dashboard` | `farmaceutico.dashboard` | Dashboard farmacéutico |
| GET | `/farmaceutico/create` | — | `farmaceutico.create` | Formulario crear farmacéutico (admin) |
| POST | `/farmaceutico/store` | `FarmaceuticoController@store` | `farmaceutico.store` | Guardar nuevo farmacéutico |
| GET | `/modulo/a` | `FarmaceuticoController@moduloA` | `farmaceutico.modulo.A` | Recetas pendientes módulo A |
| GET | `/modulo/b` | `FarmaceuticoController@moduloB` | `farmaceutico.modulo.B` | Recetas pendientes módulo B |
| GET | `/modulo/c` | `FarmaceuticoController@moduloC` | `farmaceutico.modulo.C` | Recetas pendientes módulo C |
| GET | `/receta/{id}` | `FarmaceuticoController@mostrarReceta` | `mostrar.receta` | Detalle de receta |
| POST | `/entregar-receta/{id}` | `FarmaceuticoController@entregarReceta` | `entregar.receta` | Marcar receta como entregada |

---

## Informes (Admin)

| Método | URI | Controlador | Nombre | Descripción |
|---|---|---|---|---|
| GET | `/admin/informes` | `InformesController@show` | `informes.show` | Vista de informes |
| GET | `/admin/informes/generar` | `InformesController@generarInforme` | `informes.generar` | Generar informe por tipo y fecha |
| GET | `/admin/informes/buscar-receta` | `InformesController@buscarReceta` | `informes.buscarReceta` | Buscar receta por ID |
| GET | `/admin/informes/entregadas-por-fecha` | `InformesController@entregadasPorFecha` | `informes.entregadasPorFecha` | Recetas entregadas por fecha |

---

## Parámetros de consulta frecuentes

| Ruta | Parámetro | Tipo | Descripción |
|---|---|---|---|
| `/buscar-paciente` | `rut` | string | RUT del paciente (sin puntos, con guión) |
| `/admin/informes/generar` | `consulta` | string | `entregadas` o `pendientes` |
| `/admin/informes/generar` | `fecha` | date (YYYY-MM-DD) | Fecha a consultar |
| `/admin/informes/buscar-receta` | `id_receta` | int | ID de la receta |
| `/admin/informes/entregadas-por-fecha` | `fecha_entrega` | date (YYYY-MM-DD) | Fecha de entrega |
