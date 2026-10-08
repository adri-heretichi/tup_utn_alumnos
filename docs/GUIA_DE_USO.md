# Guía de uso

Sistema de Gestión de Alumnos de la Tecnicatura Universitaria en Programación, Universidad Tecnológica Nacional.

## Acceso y funciones comunes

Desde el inicio público se puede iniciar sesión o registrarse como alumno. Utilizar el Gmail y la contraseña de la cuenta. El menú permite cerrar sesión; en móvil se abre con el botón de tres líneas.

Todos los roles pueden consultar su propio perfil, editar sus datos y cambiar su contraseña. Ningún rol puede autoeliminarse. No existe selector de rol en el registro ni en la edición.

La opción de recuperar contraseña utiliza el mecanismo de Breeze. La entrega local registra los correos en logs; el envío a una casilla requiere configurar un servicio de correo.

## Alumno

### Registro e inicio de sesión

Abrir `/register`, completar nombre, Gmail, contraseña y confirmación, y seleccionar una fotografía válida. Teléfono y red profesional son opcionales. El registro crea siempre un **Alumno**, incluso si alguien intenta enviar otro rol desde fuera del formulario.

Después del registro se inicia la sesión y se muestra la portada. En próximos accesos utilizar **Iniciar sesión**. Docentes y bedeles son creados por un bedel, no mediante este registro.

### Inicio

El menú del alumno contiene **Inicio** y **Mi Perfil**, además de **Cerrar sesión**.

El dashboard tiene dos tarjetas:

- **Mi Perfil:** consulta de información mediante **Ver mi perfil**.
- **Mi Tarjeta:** presentación con fotografía, nombre completo, Gmail e insignia **Alumno**. Si no hay foto, muestra un avatar neutro. No contiene botones de edición.

### Perfil y edición

En **Mi Perfil** se muestran fotografía, nombre, rol, Gmail, teléfono, WhatsApp y red profesional. **Editar Perfil** lleva al formulario de datos propios.

Se puede modificar nombre, Gmail, teléfono, red profesional y fotografía. La nueva foto es opcional: si no se selecciona, se conserva la actual. Guardar con **Guardar cambios**. Al reemplazarla correctamente se retira la fotografía anterior del almacenamiento local.

En la misma pantalla de edición está **Cambiar contraseña**: ingresar contraseña actual, nueva contraseña y confirmación. El alumno no puede eliminar su cuenta ni consultar otros usuarios.

## Docente

El menú contiene **Inicio**, **Alumnos** y **Mi Perfil**, además de **Cerrar sesión**. El dashboard ofrece **Ver alumnos** y **Ver mi perfil**.

### Consulta de alumnos

**Alumnos** muestra exclusivamente usuarios con rol alumno. No incluye docentes ni bedeles. La tabla presenta:

- Fotografía y nombre completo.
- Gmail y teléfono.
- Acción **Abrir WhatsApp**, si existe un teléfono utilizable.
- Enlace a red profesional, si fue informada.
- Fecha de registro y **Ver detalle**.

Cuando faltan los datos se muestra **No informado** o **No informada**, según corresponda. En móvil, la tabla admite desplazamiento horizontal.

**Ver detalle** abre la ficha completa del alumno. Los enlaces externos se abren en otra pestaña. El docente solo consulta: no crea, edita administrativamente ni elimina alumnos; tampoco administra docentes o bedeles.

El docente edita su información y cambia su contraseña desde su perfil propio. No puede autoeliminarse.

## Bedel

El menú contiene **Inicio**, **Alumnos**, **Docentes**, **Bedeles** y **Mi Perfil**, además de **Cerrar sesión**.

El dashboard tiene accesos separados para ver o agregar usuarios de cada categoría y consultar el perfil propio.

### Alumnos

El listado contiene únicamente alumnos. **Agregar alumno** solicita nombre, Gmail, contraseña, confirmación y fotografía; teléfono y red profesional son opcionales.

Cada registro permite **Ver detalle**, **Editar** y, cuando la autorización lo permite, **Eliminar**. La edición cambia datos de contacto y foto, pero no convierte al alumno en otro rol. Tampoco cambia su contraseña desde ese formulario administrativo.

### Docentes

El listado contiene solamente docentes. **Agregar docente** solicita nombre, Gmail, contraseña y confirmación. Teléfono, red profesional y fotografía son opcionales.

Se puede consultar, editar y eliminar docentes. La edición conserva el rol docente. El perfil propio continúa disponible desde **Mi Perfil**.

### Bedeles

El listado contiene solamente bedeles. **Agregar bedel** utiliza los mismos datos de alta que docentes, con fotografía opcional. El backend establece el rol bedel.

Se puede consultar y editar otros bedeles y eliminarlos cuando esté permitido. Un bedel **no puede eliminarse a sí mismo**. El sistema protege al último bedel para conservar administración. La interfaz omite las acciones de eliminación no permitidas y el backend también las rechaza.

### Confirmación de eliminación

**Eliminar** abre una confirmación con **Cancelar** y **Eliminar**. Abrir esa pantalla no borra al usuario. La confirmación envía una petición DELETE y, si corresponde, retira su fotografía local. La eliminación es permanente.

## Reglas de los formularios

| Campo | Regla actual |
| --- | --- |
| Nombre | Obligatorio; texto; máximo 255 caracteres |
| Gmail | Obligatorio; formato correo; minúsculas; único; termina en `@gmail.com`; máximo 255 caracteres |
| Contraseña de alta | Obligatoria; mínimo 8 caracteres; confirmada |
| Cambio propio de contraseña | Contraseña actual correcta; nueva contraseña de al menos 8 caracteres y confirmación |
| Teléfono | Opcional; texto; máximo 30 caracteres |
| Red profesional | Opcional; URL válida; máximo 255 caracteres |
| Fotografía | Imagen JPG, JPEG, PNG o WEBP; máximo 2 MB |

La foto es obligatoria en registro público y alta administrativa de alumnos. Es opcional al crear docentes/bedeles y en cualquier edición. Las reglas actuales de contraseña no imponen una mezcla obligatoria de mayúsculas, símbolos o números.

WhatsApp se construye desde el teléfono, retirando caracteres que no sean dígitos y usando `WHATSAPP_CODIGO_PAIS` cuando el número no comienza con ese prefijo. La configuración local utiliza `54`; se debe cargar un número apropiado para esa configuración.

## Mensajes y restricciones

Los formularios muestran errores en español sin guardar los datos inválidos. Un acceso autenticado fuera de los permisos devuelve **403**. Una página inexistente devuelve **404**. Si expiró la sesión o falta el token del formulario, volver a cargar la pantalla e iniciar sesión si corresponde.
