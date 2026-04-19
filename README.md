# Medipac — Sistema de Gestión de Recetas Médicas

Sistema web desarrollado en Laravel 11 para la gestión electrónica de recetas médicas en un contexto de farmacia hospitalaria. Incluye un módulo de Machine Learning (K-Means Clustering) que clasifica automáticamente las recetas en módulos de dispensación (A, B o C) según las características del paciente.

## Tabla de contenidos

- [Descripción general](#descripción-general)
- [Requisitos](#requisitos)
- [Instalación rápida](#instalación-rápida)
- [Tipos de usuario](#tipos-de-usuario)
- [Documentación técnica](#documentación-técnica)

---

## Descripción general

Medipac digitaliza el flujo completo de una receta médica:

```
Médico crea receta → Paciente la selecciona → ML asigna módulo → Farmacéutico la entrega
```

El sistema cuenta con cuatro roles de usuario con accesos independientes:

| Rol | Autenticación |
|---|---|
| Administrador | Usuario + contraseña (tabla `users`) |
| Médico | ID de médico (tabla `Medico`) |
| Paciente | RUT + últimos 3 dígitos del número de serie |
| Farmacéutico | ID de farmacéutico (tabla `Farmaceutico`) |

---

## Requisitos

| Componente | Versión mínima |
|---|---|
| PHP | 8.3+ (con extensiones `pdo_sqlsrv`, `sqlsrv`) |
| Composer | 2.x |
| Node.js | 18+ |
| npm | 9+ |
| SQL Server | LocalDB / SQL Server Express |
| Python | 3.x |
| Python libs | `numpy`, `scikit-learn` |

---

## Instalación rápida

Ver la [Guía de Despliegue](docs/GUIA_DESPLIEGUE.md) completa.

```bash
# 1. Clonar repositorio
git clone https://github.com/DepSS21/Proyecto-de-Titulo-Medipac.git
cd Proyecto-de-Titulo-Medipac

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Configurar entorno
cp .env.example .env
# Editar .env con tus credenciales de SQL Server

# 5. Crear tablas e insertar datos de prueba
# Ejecutar database/scripts/setup.sql en tu instancia SQL Server

# 6. Instalar dependencias Python
python -m pip install numpy scikit-learn

# 7. Iniciar servidores (2 terminales)
php artisan serve          # Terminal 1
npm run dev                # Terminal 2
```

Acceder en: `http://127.0.0.1:8000`
Credenciales admin por defecto: usuario `admin` / contraseña `admin123`

---

## Tipos de usuario

### Administrador
- Login en `/`
- Puede crear médicos, farmacéuticos y ver informes

### Médico
- Login en `/medico/login` con su ID
- Busca pacientes por RUT y crea recetas

### Paciente
- Login en `/paciente` con RUT + últimos 3 dígitos del número de serie
- Selecciona recetas para retiro (el ML asigna el módulo)

### Farmacéutico
- Login en `/farmaceutico/login` con su ID
- Ve recetas pendientes por módulo (A, B, C) y registra entregas

---

## Documentación técnica

| Documento | Descripción |
|---|---|
| [Arquitectura](docs/ARQUITECTURA.md) | Estructura del proyecto, stack tecnológico y decisiones de diseño |
| [Base de Datos](docs/BASE_DE_DATOS.md) | Esquema completo de tablas y relaciones |
| [Flujos de Usuario](docs/FLUJOS_USUARIO.md) | Flujos detallados por cada tipo de usuario |
| [Rutas](docs/RUTAS.md) | Listado completo de rutas HTTP |
| [Machine Learning](docs/MACHINE_LEARNING.md) | Documentación del modelo K-Means |
| [Guía de Despliegue](docs/GUIA_DESPLIEGUE.md) | Instalación paso a paso en entorno nuevo |
