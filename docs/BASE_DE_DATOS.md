# Base de datos

## Motor y separación de entornos

El sistema utiliza el controlador Laravel `mysql`. El entorno verificado es MariaDB 10.4.32 de XAMPP.

| Uso | Base |
| --- | --- |
| Aplicación normal | `tup_utn_alumnos` |
| Pruebas automáticas | `tup_utn_alumnos_test` |

Ambas se crean con `utf8mb4` y `utf8mb4_unicode_ci`. No se utiliza SQLite. Antes de ejecutar pruebas se debe verificar configuración efectiva y `SELECT DATABASE()`; nunca permitir que `RefreshDatabase` opere sobre la base principal.

## Tabla users

Concentra las tres clases de usuario. No hay tablas separadas de alumnos, docentes o bedeles.

| Campo | Tipo definido en migraciones | Uso |
| --- | --- | --- |
| `id` | Entero grande sin signo, autoincremental | Clave primaria |
| `name` | Cadena | Nombre completo |
| `email` | Cadena con índice único | Correo Gmail de acceso |
| `email_verified_at` | Marca de tiempo, nullable | Estado de verificación del correo |
| `password` | Cadena | Contraseña almacenada mediante hash |
| `is_admin` | Booleano, predeterminado false | Compatibilidad temporal |
| `rol` | Cadena indexada, predeterminado alumno | Rol utilizado por la autorización |
| `phone` | Cadena, nullable | Teléfono opcional |
| `professional_url` | Cadena, nullable | Red profesional opcional |
| `photo_path` | Cadena, nullable | Ruta relativa de fotografía en disco público |
| `remember_token` | Cadena, nullable | Soporte de sesión recordada |
| `created_at` | Marca de tiempo | Fecha de registro |
| `updated_at` | Marca de tiempo | Última actualización |

Los roles utilizados por la aplicación son `alumno`, `docente` y `bedel`, definidos mediante constantes del modelo User. El campo es una cadena; la migración no crea un enum ni una restricción CHECK de roles. El backend limita las asignaciones desde sus flujos autorizados.

`is_admin` se conserva para compatibilidad. Las altas actuales asignan false para alumnos y true para docentes/bedeles, pero los permisos dependen de `rol`, no de ese booleano.

Las fotografías no se almacenan como binarios en la base. `photo_path` apunta a un archivo de `storage/app/public/perfiles/`. El enlace de WhatsApp se calcula desde el teléfono y la configuración; no es otra columna.

## Tablas de infraestructura

| Tabla | Finalidad y campos principales |
| --- | --- |
| `password_reset_tokens` | Recuperación de contraseña: email, token y created_at |
| `sessions` | Sesiones: id, user_id opcional indexado, ip_address, user_agent, payload y last_activity |
| `cache` | Caché: key, value y expiration |
| `cache_locks` | Bloqueos de caché: key, owner y expiration |
| `jobs` | Cola de trabajos: id, queue, payload, attempts y marcas de disponibilidad/reserva |
| `job_batches` | Lotes de trabajos: identificación, contadores, opciones y fechas |
| `failed_jobs` | Trabajos fallidos: identificación, conexión, cola, payload, excepción y fecha |
| `migrations` | Registro de migraciones aplicadas, administrado por Laravel |

La plantilla utiliza sesiones, caché y cola con driver database. Las tablas de cola pertenecen a la infraestructura Laravel; no representan funciones académicas nuevas. En testing se utilizan sesiones/caché en memoria del proceso y cola síncrona, mientras la base sigue siendo MySQL.

## Migraciones existentes

1. `0001_01_01_000000_create_users_table.php`: users, tokens de recuperación y sesiones.
2. `0001_01_01_000001_create_cache_table.php`: caché y sus bloqueos.
3. `0001_01_01_000002_create_jobs_table.php`: trabajos, lotes y fallos.
4. `2026_10_07_213505_add_fields_to_users_table.php`: is_admin, teléfono, red profesional y ruta de fotografía.
5. `2026_10_08_000001_agregar_rol_a_users.php`: rol predeterminado alumno e índice; convierte los usuarios antiguos con is_admin=true a docente.

La incorporación de rol conserva los usuarios existentes. El bedel inicial se crea posteriormente mediante su seeder.

## Operación y respaldo

Usar `php artisan migrate:status` para revisar el estado y `php artisan migrate` para aplicar migraciones pendientes autorizadas. Antes de actualizar una instalación con datos, conservar un respaldo de MySQL y de `storage/app/public`, además de proteger la configuración privada.

No ejecutar procedimientos que reinicien tablas sobre la base principal. Los seeders demo no son respaldos de alumnos reales y no se deben repetir para recuperar información.
