# Pruebas y validación

## Entorno obligatorio

Las pruebas usan exclusivamente MySQL mediante la base independiente `tup_utn_alumnos_test`. No se utiliza SQLite ni la base principal `tup_utn_alumnos`.

Crear la base de pruebas en el servidor local, si todavía no existe:

```sql
CREATE DATABASE IF NOT EXISTS tup_utn_alumnos_test
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

`phpunit.xml` fuerza esta configuración para PHPUnit:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tup_utn_alumnos_test
DB_USERNAME=root
DB_PASSWORD=
```

También fuerza `APP_ENV=testing` y vacía `DB_URL` para evitar que otra URL de conexión sustituya estos parámetros. El usuario root sin contraseña corresponde al entorno local verificado; adaptar las credenciales de testing en otra PC sin cambiar la base normal del `.env`.

No es necesario cambiar temporalmente `.env` ni crear `.env.testing`: la configuración específica ya está en `phpunit.xml`.

## Verificación previa y suite completa

Desde PowerShell, en la raíz del proyecto, comprobar la configuración efectiva de Laravel cargando las mismas variables que PHPUnit:

```powershell
@'
<?php
require 'vendor/autoload.php';
foreach (simplexml_load_file('phpunit.xml')->php->env as $variable) {
    $nombre = (string) $variable['name'];
    $valor = (string) $variable['value'];
    putenv($nombre.'='.$valor);
    $_ENV[$nombre] = $valor;
    $_SERVER[$nombre] = $valor;
}
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$conexion = config('database.default');
$base = config('database.connections.mysql.database');
$sql = Illuminate\Support\Facades\DB::selectOne('SELECT DATABASE() AS nombre')->nombre;
echo "DB_CONNECTION=$conexion\nDB_DATABASE=$base\nSELECT DATABASE()=$sql\n";
if ($conexion !== 'mysql' || $base !== 'tup_utn_alumnos_test' || $sql !== 'tup_utn_alumnos_test') {
    exit(1);
}
'@ | php
if ($LASTEXITCODE -ne 0) { throw 'No ejecutar pruebas: revisar la conexion de testing.' }
php artisan test
```

La salida obligatoria antes de ejecutar es:

```text
DB_CONNECTION=mysql
DB_DATABASE=tup_utn_alumnos_test
SELECT DATABASE()=tup_utn_alumnos_test
```

Si no coincide, detenerse y corregir el entorno. Una configuración cacheada puede impedir que se apliquen las variables de PHPUnit: revisar y ejecutar `php artisan config:clear` antes de repetir la comprobación.

`tests/TestCase.php` incorpora otra barrera: después de arrancar Laravel y antes de las preparaciones de `RefreshDatabase`, comprueba controlador, nombre configurado y `SELECT DATABASE()`. Bloquea las pruebas si no apuntan exactamente a MySQL de testing.

La suite utiliza `RefreshDatabase` y puede reconstruir el esquema de la base de pruebas. Esta base es descartable y no debe contener información real.

## Cobertura principal

- Autenticación, cierre de sesión, recuperación, confirmación y cambio de contraseña.
- Registro público alumno y rechazo de privilegios enviados manualmente.
- Gmail obligatorio, formato, unicidad y mensajes en español.
- Perfil propio, teléfono, red profesional y cambio de fotografía.
- Imágenes JPG/JPEG/PNG/WEBP, límite de 2 MB, rechazo de archivos inválidos y nombres no confiables.
- Roles, Policies, middleware y accesos directos protegidos.
- Consulta docente exclusivamente de alumnos y separación de listados del bedel.
- Altas, edición y bajas del bedel sin conversión de roles.
- Autoeliminación bloqueada y protección del último bedel.
- Limpieza de fotos al reemplazar o eliminar usuarios.
- WhatsApp con signos, espacios, guiones y teléfono vacío.
- Red profesional válida, vacía o inválida, con enlaces externos protegidos.
- CSRF efectivo, métodos HTTP, rutas obsoletas ausentes y asignación masiva protegida.
- Menús de escritorio/móvil, tarjetas y enlaces del dashboard.
- Seeders demo idempotentes y credenciales de demostración.

## Resultado verificado

La Etapa 11 finalizó con **128 tests aprobados, 826 assertions y 0 fallos**, en aproximadamente 15 segundos, sobre MySQL `tup_utn_alumnos_test`.

La validación final de **Etapa 12** repitió la suite completa: **128 tests aprobados, 826 assertions, 0 fallos y 15,96 segundos**, exclusivamente sobre MySQL `tup_utn_alumnos_test`. El build `npm.cmd run build` también terminó correctamente. Los números corresponden a la suite completa, no solamente a RolesTest.

## Revisión responsive en navegador

La Etapa 11 completó **84 comprobaciones**: 21 vistas renderizadas con datos de testing, en anchos de 320, 360, 768 y 1440 px. No detectó desbordamientos de página, imágenes rotas ni errores JavaScript. Verificó la apertura del menú móvil y conservó el desplazamiento horizontal de las tablas.

Para reproducirla, ejecutar primero la suite PHP y luego:

```powershell
node tests/Browser/auditoria-responsive.mjs
```

El script usa Microsoft Edge instalado en Windows, Node.js 24 en el entorno verificado y los puertos locales 8011 y uno automático para controlar el navegador. No instala paquetes externos ni inicia sesión contra la base principal: sirve HTML generado por pruebas y recursos públicos estáticos.

La ruta predeterminada de Edge está definida en el script. Si es diferente, configurar la variable de proceso `AUDITORIA_NAVEGADOR` con la ruta del ejecutable antes de lanzarlo.

Las capturas, HTML, resultados y perfil temporal del navegador se generan en `storage/framework/testing/auditoria/`. No son necesarios para ejecutar la aplicación ni se incluyen en la entrega limpia. Se pueden regenerar; el directorio está ignorado por Git.

## Build y comprobaciones manuales

```powershell
npm run build
```

Debe finalizar sin errores. En PowerShell puede usarse `npm.cmd run build`.

Para evaluación manual revisar login, registro, menús de cada rol, perfil, WhatsApp, enlaces y formularios del bedel. Las operaciones de prueba que creen o eliminen usuarios deben realizarse en una instalación aislada con datos descartables, no sobre usuarios reales.
