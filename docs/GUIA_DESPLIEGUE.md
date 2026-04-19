# Guía de Despliegue — Medipac

Esta guía cubre la instalación completa del sistema en un equipo Windows desde cero.

---

## Requisitos previos

Instalar los siguientes componentes antes de continuar:

| Componente | Versión | Descarga |
|---|---|---|
| PHP | 8.3+ | XAMPP o PHP standalone |
| Composer | 2.x | https://getcomposer.org |
| Node.js + npm | 18+ | https://nodejs.org |
| Python | 3.x | https://python.org |
| SQL Server Express o LocalDB | Cualquiera | Microsoft |
| Git | Cualquiera | https://git-scm.com |

### Extensiones PHP requeridas

Verificar que estén habilitadas en `php.ini`:

```ini
extension=pdo_sqlsrv
extension=sqlsrv
extension=mbstring
extension=openssl
extension=tokenizer
extension=xml
extension=ctype
extension=fileinfo
extension=bcmath
```

Si usas XAMPP, el archivo `php.ini` está en `C:\xampp\php\php.ini`.

---

## Paso 1: Clonar el repositorio

```powershell
git clone https://github.com/DepSS21/Proyecto-de-Titulo-Medipac.git
cd Proyecto-de-Titulo-Medipac
```

---

## Paso 2: Instalar dependencias PHP

```powershell
composer install
```

> Si aparece el error `Your Composer dependencies require a PHP version >= 8.4.0` al usar PHP 8.3, editar `vendor/composer/platform_check.php` y cambiar `80400` por `80300`.

---

## Paso 3: Instalar dependencias Node.js

```powershell
npm install
```

> Si en PowerShell aparece el error de ejecución de scripts (`npm.ps1 no se puede cargar`), ejecutar primero:
> ```powershell
> Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
> ```

---

## Paso 4: Configurar el entorno

Copiar el archivo de ejemplo y editarlo:

```powershell
copy .env.example .env
```

Abrir `.env` y configurar los siguientes valores:

```env
APP_URL=http://127.0.0.1:8000

# Conexión SQL Server LocalDB
DB_CONNECTION=medipac
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=proyecto
DB_USERNAME=sa
DB_PASSWORD=TU_CONTRASEÑA

# Si usas LocalDB con autenticación de Windows (sin usuario/contraseña)
DB_HOST_SQLSERVER=localhost\SQLEXPRESS
DB_PORT_SQLSERVER=1433
DB_DATABASE_SQLSERVER=proyecto
DB_USERNAME_SQLSERVER=
DB_PASSWORD_SQLSERVER=
```

> La conexión `medipac` en `config/database.php` usa `(localdb)\MSSQLLocalDB` con autenticación de Windows (sin usuario ni contraseña). Si tu instancia es diferente, editar `config/database.php`.

---

## Paso 5: Generar clave de aplicación

```powershell
php artisan key:generate
```

---

## Paso 6: Crear la base de datos

### Opción A: SQL Server LocalDB (recomendado para desarrollo)

1. Abrir **SQL Server Management Studio** o **Azure Data Studio**
2. Conectar a `(localdb)\MSSQLLocalDB`
3. Crear la base de datos `proyecto`:
   ```sql
   CREATE DATABASE proyecto;
   ```
4. Seleccionar la base de datos `proyecto` y ejecutar el script completo:
   ```
   database/scripts/setup.sql
   ```

### Opción B: SQL Server Express

1. Conectar a tu instancia (ej: `localhost\SQLEXPRESS`)
2. Crear y seleccionar base de datos `proyecto`
3. Ejecutar `database/scripts/setup.sql`
4. Actualizar `config/database.php` con el host correcto

### Paso 6b: Ejecutar migraciones de Laravel

```powershell
php artisan migrate
```

Esto crea las tablas de Laravel: `users`, `sessions`, `cache`, `jobs`, etc.

---

## Paso 7: Crear directorio para el servicio Python

```powershell
php artisan storage:link
mkdir storage\app\python_service
```

---

## Paso 8: Instalar dependencias Python

Primero verificar dónde está instalado Python:

```powershell
where.exe python
```

Luego instalar las librerías:

```powershell
python -m pip install numpy scikit-learn
```

---

## Paso 9: Configurar la ruta de Python en el controlador

Abrir `app/Http/Controllers/PacienteController.php` y verificar que la ruta de Python sea correcta para tu sistema:

```php
$pythonPath = 'C:/Users/TU_USUARIO/AppData/Local/Python/bin/python.exe';
```

Reemplazar con la ruta que devolvió `where.exe python` en el paso anterior.

---

## Paso 10: Compilar assets

Para **desarrollo** (con hot reload):
```powershell
npm run dev
```

Para **producción** (genera archivos estáticos en `public/build/`):
```powershell
npm run build
```

---

## Paso 11: Iniciar el servidor

```powershell
php artisan serve
```

La aplicación estará disponible en: **http://127.0.0.1:8000**

---

## Credenciales por defecto

| Rol | Credencial |
|---|---|
| **Admin** | user: `admin` / password: `admin123` |
| **Médico** | ID: `1` (Dr. Roberto Silva) |
| **Farmacéutico** | ID: `1` (Luis Farma) |
| **Paciente** | RUT: `12345678-9` / Serie: `123` |

---

## Estructura de servidores en desarrollo

Se necesitan **2 terminales** corriendo simultáneamente:

| Terminal | Comando | Puerto |
|---|---|---|
| 1 | `php artisan serve` | 8000 |
| 2 | `npm run dev` | 5173 (Vite HMR) |

---

## Verificación de la instalación

Ejecutar los siguientes comandos para verificar que todo esté correcto:

```powershell
# Verificar conexión a base de datos
php artisan db:show

# Verificar tablas de la aplicación
php artisan tinker --execute="echo DB::table('Paciente')->count() . ' pacientes';"

# Verificar que Python funciona con el modelo ML
echo '{"diagnostico":26,"edad":45,"sexo":1}' > storage\app\python_service\receta_datos.json
python python_service\predict_module.py
# Debe imprimir: A, B o C
```

---

## Despliegue en producción

Para un entorno de producción, considerar además:

### 1. Variables de entorno
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
```

### 2. Optimizaciones de Laravel
```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 3. Assets
```powershell
npm run build
```

### 4. Permisos de carpetas
Asegurarse que el servidor web tenga permisos de escritura en:
- `storage/`
- `bootstrap/cache/`

### 5. Servidor web
Configurar **Nginx** o **Apache** apuntando al directorio `public/` como raíz.

Ejemplo de configuración Nginx:
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /ruta/al/proyecto/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 6. Cola de trabajos (opcional)
Si se habilita la cola para procesar predicciones ML de forma asíncrona:
```powershell
php artisan queue:work
```

---

## Solución de problemas frecuentes

### Error: "Composer dependencies require PHP >= 8.4.0"
El archivo `vendor/composer/platform_check.php` fue generado con PHP 8.4. Editar la línea:
```php
// Cambiar
if (!(PHP_VERSION_ID >= 80400)) {
// Por
if (!(PHP_VERSION_ID >= 80300)) {
```

### Error: "Invalid object name 'Paciente'"
Las tablas de la aplicación no existen. Ejecutar `database/scripts/setup.sql` en la base de datos.

### Error: "El script de Python falló"
1. Verificar que Python esté instalado: `where.exe python`
2. Verificar que las librerías estén instaladas: `python -m pip list`
3. Verificar que `storage/app/python_service/` exista y sea escribible
4. Verificar que la ruta en `PacienteController.php` sea correcta

### Error: "npm.ps1 no se puede cargar"
PowerShell bloquea la ejecución de scripts. Ejecutar:
```powershell
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
```

### Error: "RUT o número de serie incorrectos"
El RUT debe coincidir exactamente con el almacenado en la base de datos (sin puntos, con guión: `12345678-9`). El número de serie son los **últimos 3 caracteres** del campo `numero_serie`.
