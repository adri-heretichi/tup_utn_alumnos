# Documentación técnica

## 1. Nombre y objetivo

**Sistema de Gestión de Alumnos - TUP UTN**. Aplicación para la Tecnicatura Universitaria en Programación de la Universidad Tecnológica Nacional, destinada a administrar perfiles y datos de contacto con responsabilidades diferenciadas entre alumnos, docentes y bedeles.

## 2. Arquitectura general y MVC

Laravel 12 organiza la aplicación siguiendo MVC. Las rutas reciben peticiones HTTP, los middleware limitan acceso, los controladores coordinan Requests/Policies y operaciones del modelo, y las vistas Blade presentan los resultados. MySQL/MariaDB conserva usuarios e infraestructura Laravel.

La autenticación proviene de Laravel Breeze. El frontend utiliza Blade, Tailwind CSS, Alpine.js y recursos compilados por Vite. No hay una API pública separada ni una aplicación frontend independiente.

## 3. Modelo User y roles

`app/Models/User.php` representa a todas las personas mediante la tabla `users`. Define `ROL_ALUMNO`, `ROL_DOCENTE`, `ROL_BEDEL`, los métodos `esAlumno()`, `esDocente()`, `esBedel()` y la etiqueta legible `nombreRol()`.

`$fillable` contiene solamente nombre, correo, contraseña, teléfono, red profesional y ruta de foto. `rol` e `is_admin` se asignan explícitamente desde flujos autorizados. La contraseña utiliza el cast `hashed`, y tanto contraseña como remember_token se ocultan en la serialización del modelo.

La columna `is_admin` es temporal y compatible con la estructura anterior. No participa en la autorización vigente.

### Esquema conceptual

```text
Usuario
├── Alumno
├── Docente
└── Bedel

Registro público
└── Alumno

Bedel
├── administra Alumnos
├── administra Docentes
└── administra Bedeles, con restricciones de eliminación

Docente
└── consulta Alumnos

Alumno
└── administra su propio Perfil
```

Es una clasificación conceptual: los roles no son subclases ni tablas separadas.

## 4. Middleware y Policies

`VerificarRol` se registra con el alias `rol` en `bootstrap/app.php`. Compara el rol autenticado con los roles permitidos de la ruta. Su prioridad está antes de `SubstituteBindings`, de modo que un acceso sin permiso responde 403 sin depender de si existe el usuario solicitado. `auth` atiende el acceso de invitados.

`UsuarioPolicy`, registrada en `AppServiceProvider`, permite:

- Listar: docente o bedel; cada controlador filtra su conjunto de datos.
- Ver: perfil propio, cualquier usuario para bedel, o alumno para docente.
- Actualizar: perfil propio o usuario administrado por bedel.
- Crear: solamente bedel.
- Eliminar: solamente otro usuario por bedel, sin eliminar al último bedel.

Las rutas y controladores añaden límites de contexto: un alumno no puede abrir una ficha administrativa propia; el docente solo consulta alumnos; cada sección del bedel exige que el objetivo pertenezca al rol de esa sección. Una referencia a otro rol desde una sección del bedel se trata como no encontrada.

## 5. Controladores principales

| Controlador | Responsabilidad |
| --- | --- |
| `Auth/RegisteredUserController` | Registro público con foto; fija alumno e is_admin=false |
| `Auth/AuthenticatedSessionController` | Inicio y cierre de sesión de Breeze |
| Otros controladores `Auth/` | Contraseñas y flujo de verificación de correo |
| `ProfileController` | Consulta y edición del perfil propio; sin método de autoeliminación |
| `Docente/AlumnoController` | Listado y detalle de alumnos, sin escrituras administrativas |
| `Bedel/UsuarioController` | Gestión por sección: alumnos, docentes o bedeles |

El controlador Bedel comparte implementación para evitar duplicar tres gestiones equivalentes. Las rutas proporcionan un `rolObjetivo` fijo; no se obtiene de un selector ni del cuerpo de la petición.

## 6. Requests y validaciones

`RegistrarAlumnoRequest` define nombre, Gmail, contraseña confirmada, teléfono, URL profesional y fotografía obligatoria. `ProfileUpdateRequest` valida datos propios y permite foto opcional; la unicidad del correo excluye al usuario autenticado.

`GuardarUsuarioRequest` reutiliza el registro, exige actor bedel, adapta unicidad al usuario editado y requiere foto solo en altas de alumnos. En edición no toma contraseña ni rol como campos administrativos modificables.

`Auth/LoginRequest` valida email/contraseña, autentica y limita intentos fallidos. La regla de contraseña por defecto actual exige al menos 8 caracteres; las altas y cambios requieren confirmación. No hay una regla adicional obligatoria de complejidad configurada en los providers.

Las reglas concretas y límites se detallan en [GUIA_DE_USO.md](GUIA_DE_USO.md). Los mensajes propios y las traducciones de `lang/es/` conservan español.

## 7. Servicio de gestión

`app/Servicios/GestorUsuarios.php` concentra altas, actualizaciones y eliminaciones administrativas. El proyecto usa `Servicios`, no `Services`.

En altas fija el rol recibido de la ruta y el valor compatible de is_admin. En edición conserva ambos privilegios. Guarda fotografías con nombres generados, retira una nueva foto si falla la persistencia y elimina la anterior después de un reemplazo exitoso.

Para eliminar utiliza una transacción MySQL con `lockForUpdate`: bloquea los bedeles, vuelve a comprobar al actor, recupera el objetivo y rechaza autoeliminación o pérdida del último bedel. Después de la eliminación de la fila retira la fotografía local asociada, si existe.

## 8. Base de datos y seeders

La base normal es `tup_utn_alumnos`; la de pruebas es `tup_utn_alumnos_test`. Ambas utilizan MySQL, con MariaDB de XAMPP en el entorno verificado. Consultar [BASE_DE_DATOS.md](BASE_DE_DATOS.md) para tablas y campos.

La migración de rol agrega una cadena indexada con valor alumno y actualiza a docente los usuarios antiguos con is_admin=true, sin borrar usuarios.

`DocenteSeeder` y `BedelSeeder` usan búsquedas por correo y asignación explícita; son idempotentes respecto de la cantidad de cuentas. Establecen nombre, contraseña con hash, rol, is_admin=true y correo verificado. Repetirlos restablece sus datos demo. `DatabaseSeeder` llama a ambos, aunque la instalación documentada los ejecuta por separado.

## Seguridad implementada

- Autenticación y regeneración de sesión mediante los controladores Breeze.
- Middleware por rol y Policies aplicadas en backend, con respuesta 403 ante falta de permiso.
- Protección CSRF en formularios y métodos PATCH/DELETE para escrituras correspondientes.
- Contraseñas con hash; los valores almacenados no se muestran en vistas ni documentación.
- Validación de Gmail, unicidad, URL, tamaño y formato de imágenes.
- Asignación masiva limitada; rol e is_admin no son campos públicos asignables.
- Registro público siempre alumno, y rol de altas administrativas fijado por la sección.
- Edición sin conversión de roles y eliminación propia retirada del perfil, controlador y rutas.
- Protección del último bedel con comprobaciones y bloqueos transaccionales.
- Enlaces profesionales/WhatsApp con `target="_blank"` y `rel="noopener noreferrer"`.
- Base de pruebas independiente y barrera previa a RefreshDatabase.

Estas medidas cubren el alcance implementado; no equivalen a una garantía absoluta de seguridad. Una instalación expuesta requiere credenciales propias, HTTPS, correo configurado, depuración desactivada y gestión de respaldos.

## 9. Fotografías y datos de contacto

Las fotos se guardan explícitamente en el disco `public`, bajo `perfiles/`, aunque el disco predeterminado general sea `local`. `obtenerUrlFoto()` devuelve `/storage/{photo_path}` o un avatar SVG neutro local. `storage:link` habilita su publicación. Los nombres enviados por el usuario no se utilizan directamente para construir la ruta almacenada.

`obtenerEnlaceWhatsApp()` elimina caracteres no numéricos y agrega el prefijo de `config/whatsapp.php` cuando no está presente al inicio. `WHATSAPP_CODIGO_PAIS` vale 54 en la plantilla; no se realiza una detección internacional automática de países. Un teléfono vacío no genera enlace.

La red profesional es una URL opcional validada. Se muestra como enlace externo seguro o como no informada.

## 10. Rutas y métodos

`routes/web.php` define inicio, dashboard, perfil y áreas por rol; `routes/auth.php` conserva los flujos de Breeze.

| Operación | Ruta y método |
| --- | --- |
| Consulta docente | `GET /docente/alumnos` y `GET /docente/alumnos/{usuario}` |
| Listado bedel | `GET /bedel/{seccion}` |
| Formulario de alta | `GET /bedel/{seccion}/agregar` |
| Guardar alta | `POST /bedel/{seccion}` |
| Detalle | `GET /bedel/{seccion}/{usuario}` |
| Formulario de edición | `GET /bedel/{seccion}/{usuario}/editar` |
| Guardar edición | `PATCH /bedel/{seccion}/{usuario}` |
| Confirmar eliminación | `GET /bedel/{seccion}/{usuario}/eliminar` |
| Ejecutar eliminación | `DELETE /bedel/{seccion}/{usuario}` |
| Guardar perfil propio | `PATCH /profile` |
| Cambiar contraseña propia | `PUT /password` |

`{seccion}` resume tres prefijos fijos: alumnos, docentes y bedeles. No es un parámetro público de rol libre. Los nombres propios de ruta son `docente.alumnos.*` y `bedel.alumnos.*`, `bedel.docentes.*`, `bedel.bedeles.*`.

No hay eliminaciones mediante GET, ruta antigua `/admin/users` ni DELETE `/profile`.

## 11. Frontend y permisos visibles

Blade renderiza las vistas y Tailwind aporta los estilos institucionales de `resources/css/app.css`. El logo es el PNG local UTN y no se recolorea ni depende de una URL externa. Alpine controla el menú móvil.

Los menús y tarjetas cambian según rol. El alumno tiene Mi Perfil/Mi Tarjeta; el docente, Alumnos/Mi Perfil; el bedel, las tres gestiones y Mi Perfil. Las tablas mantienen desplazamiento horizontal en pantallas pequeñas. Ocultar acciones acompaña, pero no sustituye, la autorización de backend.

## 12. Idioma y correo

`APP_LOCALE=es` y `APP_FALLBACK_LOCALE=es`; `APP_FAKER_LOCALE=en_US` solo afecta datos ficticios. Las traducciones dinámicas se encuentran en `lang/es/` y `lang/es.json`.

El proyecto conserva rutas y pantallas de verificación de correo, pero User no implementa actualmente el contrato `MustVerifyEmail`: no se debe presentar la verificación como requisito general obligatorio para usar el dashboard. Los seeders sí establecen correo verificado.

La configuración de entrega local usa correo en logs, no envío SMTP. Configurar transporte real si se necesita entregar mensajes de recuperación/verificación a casillas.

## 13. Testing y continuidad

La suite completa y la auditoría responsive se describen en [PRUEBAS.md](PRUEBAS.md). Resultado Etapa 11: 128 tests, 826 assertions y 0 fallos. Las pruebas solo operan sobre MySQL de testing, con fotos en almacenamiento simulado.

Las evidencias generadas son temporales y no forman parte de la ejecución de la aplicación. Conservar el código de pruebas, archivos de bloqueo y esta documentación; excluir configuración privada, dependencias reconstruibles, logs y perfiles de navegador de una entrega por copia.

## 14. Posibles mejoras futuras

Fuera del alcance actual podrían evaluarse materias, cursadas, notas, asistencia o reportes. Son posibilidades de continuidad: no están implementadas ni forman parte de esta entrega.
