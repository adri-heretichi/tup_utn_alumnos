# Roles y permisos

La autorización utiliza `User.rol`: `alumno`, `docente` o `bedel`. `is_admin` permanece por compatibilidad, pero no determina los permisos.

## Matriz

| Acción | Alumno | Docente | Bedel |
| --- | --- | --- | --- |
| Registrarse públicamente con ese rol | Sí | No | No |
| Iniciar y cerrar sesión | Sí | Sí | Sí |
| Ver perfil propio | Sí | Sí | Sí |
| Editar perfil propio | Sí | Sí | Sí |
| Cambiar contraseña propia | Sí | Sí | Sí |
| Ver listado de alumnos | No | Sí | Sí |
| Ver detalle administrativo de alumno | No | Sí | Sí |
| Crear alumno | No | No | Sí |
| Editar alumno administrativamente | No | No | Sí |
| Eliminar alumno | No | No | Sí |
| Ver listado y detalle de docentes | No | No | Sí |
| Crear docente | No | No | Sí |
| Editar docente administrativamente | No | No | Sí |
| Eliminar docente | No | No | Sí |
| Ver listado y detalle de bedeles | No | No | Sí |
| Crear bedel | No | No | Sí |
| Editar bedel administrativamente | No | No | Sí |
| Eliminar otro bedel | No | No | Sí, con restricciones |
| Cambiar rol de un usuario existente | No | No | No |
| Autoeliminarse | No | No | No |

Consultar el perfil propio no equivale a acceder al detalle administrativo. El alumno puede ver sus datos desde `/profile/show`, pero no abrir una ficha administrativa, ni siquiera con su propio ID.

El registro público crea exclusivamente alumnos. Un docente o bedel que necesite otra cuenta no obtiene su rol desde `/register`; las altas privilegiadas pertenecen al bedel.

## Restricciones del bedel

- Puede eliminar otros bedeles cuando la operación conserva al menos uno.
- No puede autoeliminarse, aunque existan otros bedeles.
- Los formularios de edición conservan el rol del usuario.
- La sección de alta determina el rol en backend; enviar otro rol en el cuerpo de la petición no lo cambia.
- La eliminación utiliza transacción y bloqueo de filas, y vuelve a comprobar al actor que realiza la operación.

## Aplicación de permisos

`VerificarRol` restringe las rutas Docente/Bedel y se ejecuta antes de resolver usuarios por ID. `UsuarioPolicy` controla consulta, creación, actualización y eliminación. Los accesos autenticados sin permiso devuelven 403; los invitados son enviados al inicio de sesión por el middleware `auth`.

Los menús reflejan los permisos, pero las restricciones también se aplican a peticiones directas. No existe la antigua administración general `/admin/users` ni eliminación propia mediante DELETE `/profile`.
