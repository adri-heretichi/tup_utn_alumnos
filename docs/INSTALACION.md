# Instalación en Windows con XAMPP

Esta guía prepara una instalación nueva del Sistema de Gestión de Alumnos - TUP UTN. No reemplazar la configuración ni recrear la base de una instalación existente.

## 1. Preparar herramientas

Instalar XAMPP con PHP compatible con `^8.2`, Composer 2, Node.js y npm. El entorno verificado usa PHP 8.2.12 y MariaDB 10.4.32. Vite 6 admite Node.js `^18.0.0 || ^20.0.0 || >=22.0.0`; durante la entrega se utilizó Node.js 24.16.0.

Iniciar MySQL desde el panel de XAMPP. PHP debe estar disponible en la terminal. Verificar:

```powershell
php --version
composer --version
node --version
npm --version
```

Laravel requiere, entre otras, las extensiones PHP `ctype`, `filter`, `hash`, `mbstring`, `openssl`, `session` y `tokenizer`. Este proyecto necesita además `pdo_mysql` y `fileinfo`; las pruebas de imágenes necesitan `gd`, y PHPUnit requiere sus extensiones, como DOM/XML. Si falta alguna, habilitarla en el `php.ini` usado por la terminal. Comprobar su ubicación con `php --ini`.

Visual Studio Code fue el editor utilizado, pero no es obligatorio.

## 2. Copiar el proyecto y abrir una terminal

Copiar los archivos entregados, por ejemplo, a:

```text
C:\xampp\htdocs\tup_utn_alumnos
```

Si se recibe un repositorio, también se puede clonar desde la ubicación proporcionada por quien entrega el proyecto. No existe una URL pública obligatoria.

Abrir PowerShell:

```powershell
Set-Location C:\xampp\htdocs\tup_utn_alumnos
```

Conservar `composer.lock`, `package-lock.json`, `.env.example`, las migraciones y el logo local. No copiar el `.env` privado ni evidencias temporales de otra instalación. `vendor/` y `node_modules/` se reconstruyen con los pasos siguientes.

## 3. Instalar dependencias

```powershell
composer install
composer check-platform-reqs
npm install
```

Estos comandos requieren acceso a los repositorios de dependencias. No es necesario reinstalar Laravel ni Breeze. Para una instalación reproducible sin modificar el archivo de bloqueo de npm también puede utilizarse `npm ci` cuando se dispone de `package-lock.json`.

## 4. Crear configuración y clave propias

Solo si todavía no existe `.env`:

```powershell
copy .env.example .env
php artisan key:generate
```

No compartir `APP_KEY` ni cambiarla arbitrariamente después de poner una instalación en uso.

Comprobar en `.env`:

```dotenv
APP_NAME="Sistema de Gestión de Alumnos - TUP"
APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=en_US
PHP_CLI_SERVER_WORKERS=1

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tup_utn_alumnos
DB_USERNAME=root
DB_PASSWORD=
```

La contraseña vacía corresponde exclusivamente a la configuración local típica de XAMPP utilizada en este proyecto. Host, puerto, usuario y contraseña pueden variar en otra computadora.

Configurar `APP_URL` con la dirección que se utilizará. `APP_DEBUG=true` es para desarrollo local; en una instalación expuesta usar `APP_DEBUG=false` y credenciales propias. La plantilla utiliza sesiones, caché y cola en base de datos.

## 5. Crear la base principal

Desde phpMyAdmin de XAMPP o un cliente MySQL, crear únicamente la base de esta instalación:

```sql
CREATE DATABASE IF NOT EXISTS tup_utn_alumnos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

No sustituir una base con datos existentes. Para restaurar una instalación usada se necesita su respaldo autorizado y sus fotografías, no solamente seeders.

## 6. Aplicar migraciones

```powershell
php artisan config:clear
php artisan about
php artisan migrate
php artisan migrate:status
```

Antes de migrar, comprobar que el entorno normal apunta a `mysql` y `tup_utn_alumnos`. Las migraciones crean las tablas y agregan `rol`; la migración de compatibilidad conserva usuarios antiguos asignando alumno cuando `is_admin=false` y docente cuando `is_admin=true`.

## 7. Crear usuarios de demostración

```powershell
php artisan db:seed --class=DocenteSeeder
php artisan db:seed --class=BedelSeeder
```

| Rol | Correo | Contraseña |
| --- | --- | --- |
| Docente | docente1@gmail.com | Docente123! |
| Bedel | bedel1@gmail.com | Bedel123! |

Estas credenciales son exclusivamente de demostración/desarrollo y deberían modificarse en una instalación real. Los seeders no duplican esos correos, pero al repetirlos vuelven a establecer los datos y contraseñas demo. No ejecutarlos como una tarea de mantenimiento habitual.

## 8. Habilitar fotografías

El proyecto guarda las fotos en `storage/app/public/perfiles/` y las muestra mediante `/storage/`. Necesita el enlace configurado entre `public/storage` y `storage/app/public`:

```powershell
php artisan storage:link
```

Si el enlace ya existe y apunta correctamente, no es necesario recrearlo. En Windows pueden requerirse permisos de administrador o el modo de desarrollador para crear enlaces. La aplicación necesita escritura en `storage/` y `bootstrap/cache/`.

No eliminar `storage/app/public` ni sus fotografías. El logo institucional es independiente y permanece en `public/imagenes/logo-utn.png`.

## 9. Compilar e iniciar

```powershell
npm run build
php artisan serve
```

La terminal mostrará la dirección disponible. Normalmente comienza en `http://127.0.0.1:8000`; si el puerto está ocupado puede utilizar otro. Mantener esa terminal abierta y detener el servidor con `Ctrl+C` cuando corresponda.

En PowerShell, si `npm.ps1` está bloqueado, usar `npm.cmd run build`. Para trabajar con recarga de recursos frontend, ejecutar `npm run dev` en otra terminal; para la entrega compilada alcanza con el build.

Con `artisan serve` basta con MySQL activo en XAMPP; Apache no es necesario para ese servidor. Si se configura Apache, su raíz pública debe apuntar al directorio `public/` del proyecto, nunca exponer toda la raíz Laravel.

## 10. Comprobar la instalación

Abrir el inicio público, iniciar sesión con los usuarios demo y revisar los menús correspondientes. Crear alumnos mediante registro público o desde Bedel según el caso. Consultar [GUIA_DE_USO.md](GUIA_DE_USO.md).

Para ejecutar pruebas, crear primero una base independiente `tup_utn_alumnos_test` y seguir [PRUEBAS.md](PRUEBAS.md). Nunca usar la base principal para las pruebas automáticas.

La configuración de correo de ejemplo usa `MAIL_MAILER=log`: los correos se registran localmente, no se envían por SMTP. Para envío real se debe configurar un servicio de correo con credenciales propias. No incluir logs con enlaces de recuperación en la entrega.
