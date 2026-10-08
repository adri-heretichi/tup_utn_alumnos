# Sistema de Gestión de Alumnos - TUP UTN

Aplicación web desarrollada con Laravel para la **cátedra Programación IV** de la **Tecnicatura Universitaria en Programación de la Universidad Tecnológica Nacional (UTN)**.

El sistema está pensado para ser utilizado por múltiples docentes y centraliza en una única base la información de los alumnos. La incorporación y administración de docentes queda a cargo del rol **Bedel**, evitando que los propios docentes tengan que gestionar las cuentas de otros docentes.

## Objetivo y alcance

El objetivo del sistema es **centralizar los perfiles y datos de contacto de los alumnos para facilitar su identificación, ubicación y comunicación por parte de los docentes**.

Cada alumno registra su información personal, fotografía y medios de contacto, como correo electrónico, teléfono, WhatsApp y red profesional. Los docentes autorizados pueden consultar esa información desde una misma base de alumnos, sin necesidad de mantener listados individuales.

El sistema contempla tres tipos de usuarios:

- **Alumno:** registra y mantiene actualizada su información personal y de contacto.
- **Docente:** consulta exclusivamente la información de los alumnos registrados.
- **Bedel:** administra alumnos, docentes y otros bedeles.

El alcance del proyecto se limita a la gestión e identificación de usuarios y sus datos de contacto.

No se implementan funcionalidades relacionadas con:

- Materias.
- Cursadas.
- Notas.
- Asistencia.
- Reportes académicos.

## Tecnologías utilizadas

El proyecto fue desarrollado utilizando:

- **PHP**
- **Laravel 12**
- **MySQL**
- **Laravel Breeze**
- **Blade**
- **Tailwind CSS**
- **Alpine.js**
- **Vite**
- **Node.js**
- **npm**
- **Composer**

## Instalación

Para ejecutar el proyecto en un entorno local se deben seguir los siguientes pasos.

### 1. Clonar el repositorio

Abrir una terminal y ejecutar:

git clone URL_DEL_REPOSITORIO

Luego ingresar a la carpeta del proyecto:

cd tup_utn_alumnos

Reemplazar `URL_DEL_REPOSITORIO` por la URL correspondiente del repositorio en GitHub.

### 2. Crear la base de datos

Antes de ejecutar las migraciones se debe crear una base de datos vacía llamada:

tup_utn_alumnos

Puede utilizarse:
- MySQL.
- MariaDB.
- phpMyAdmin.
- Otra herramienta compatible.

Si se utiliza XAMPP, se puede iniciar MySQL/MariaDB desde el panel de XAMPP y crear la base desde phpMyAdmin.

### 3. Instalar las dependencias de PHP

Dentro de la carpeta del proyecto ejecutar:

composer install


### 4. Instalar las dependencias de frontend

Ejecutar:

npm install

### 5. Crear el archivo de configuración

El repositorio incluye el archivo:

.env.example

Se debe crear una copia con el nombre:

.env


### 6. Configurar la conexión a la base de datos

Abrir el archivo `.env` y configurar la conexión.

Ejemplo:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tup_utn_alumnos
DB_USERNAME=root
DB_PASSWORD=

Los valores de usuario y contraseña deben adaptarse a la configuración de MySQL utilizada en cada equipo.

### 7. Generar la clave de Laravel

Ejecutar:

php artisan key:generate

### 8. Ejecutar las migraciones

Ejecutar:

php artisan migrate

Este comando crea las tablas necesarias para el funcionamiento del sistema dentro de la base `tup_utn_alumnos`.

### 9. Crear los usuarios iniciales

El proyecto incluye Seeders para generar los usuarios iniciales del sistema.

Ejecutar:

php artisan db:seed --class=DocenteSeeder

Luego:

php artisan db:seed --class=BedelSeeder


Las credenciales de los usuarios iniciales se encuentran definidas dentro de los Seeders correspondientes.

### 10. Crear el enlace de almacenamiento

Para que las fotografías cargadas por los usuarios puedan visualizarse correctamente, ejecutar:

php artisan storage:link

### 11. Compilar el frontend

Ejecutar:

npm run build

Durante el desarrollo también puede utilizarse:

`
npm run dev


### 12. Iniciar el proyecto

Ejecutar:

php artisan serve


Laravel mostrará en la terminal la dirección local donde se encuentra disponible el sistema.

Por ejemplo:

http://127.0.0.1:8000

Abrir en el navegador la dirección indicada por Laravel.

## Guía de uso

Una vez instalado y ejecutándose el sistema, se puede comenzar a utilizar según los diferentes roles.

### 1. Ingresar como Bedel

El primer acceso administrativo se realiza con el usuario **Bedel** creado mediante `BedelSeeder`.

El Bedel es el encargado de la administración general de los usuarios.

Desde su panel puede gestionar:

- Alumnos.
- Docentes.
- Otros Bedeles.

El Bedel puede crear nuevos docentes para que puedan ingresar posteriormente al sistema.

También puede crear alumnos manualmente si fuera necesario.

### 2. Registrar docentes

Desde la sección **Docentes**, el Bedel puede:

- Visualizar los docentes existentes.
- Agregar nuevos docentes.
- Editar sus datos.
- Eliminar docentes.

Los docentes no se registran desde el formulario público.

Deben ser incorporados por un Bedel.

Esto permite que la administración de los docentes quede centralizada y evita que un docente tenga que crear o administrar la cuenta de otro docente.

### 3. Registrar alumnos

Los alumnos pueden ingresar al sistema de dos maneras.

#### Registro realizado por el propio alumno

Desde la pantalla principal, el alumno puede seleccionar:

Registrarse

Todo usuario que se registre desde el registro público será creado automáticamente con rol:


Alumno

El alumno podrá completar su información personal y de contacto.

#### Registro realizado por el Bedel

El Bedel también puede crear alumnos desde su panel administrativo.

Esto permite incorporar manualmente a un alumno si fuera necesario.

### 4. Uso del sistema como Alumno

Una vez registrado, el alumno puede iniciar sesión y acceder a su cuenta.

Desde el sistema puede:

- Consultar su perfil.
- Editar sus datos personales.
- Cargar o modificar su fotografía.
- Registrar o actualizar su teléfono.
- Registrar o actualizar su red profesional.
- Cambiar su contraseña.
- Visualizar su tarjeta personal.

La sección **Mi Tarjeta** muestra de forma resumida:

- Fotografía.
- Nombre y apellido.
- Correo electrónico.
- Rol Alumno.

La información cargada por el alumno queda disponible para los docentes autorizados.

### 5. Uso del sistema como Docente

Una vez que el Bedel crea la cuenta del docente, este puede iniciar sesión con sus propias credenciales.

El docente accede a una única base centralizada de alumnos.

Desde la sección **Alumnos** puede visualizar:

- Fotografía.
- Nombre y apellido.
- Correo electrónico.
- Número de teléfono.
- Acceso a WhatsApp.
- Red profesional o social.
- Fecha de registro.
- Detalle completo del alumno.

El objetivo es permitir que el docente pueda **identificar, ubicar y contactar fácilmente a cada alumno**.

El sistema está preparado para que varios docentes puedan utilizar la misma base de alumnos, evitando que cada docente deba mantener un listado independiente.

El docente no crea ni administra cuentas de otros docentes.

Tampoco administra Bedeles.

### 6. Uso del sistema como Bedel

El Bedel dispone de las secciones:

Alumnos
Docentes
Bedeles
Mi Perfil

#### Alumnos

Puede:

- Ver alumnos.
- Agregar alumnos.
- Editar alumnos.
- Eliminar alumnos.

#### Docentes

Puede:

- Ver docentes.
- Agregar docentes.
- Editar docentes.
- Eliminar docentes.

#### Bedeles

Puede:

- Ver Bedeles.
- Crear nuevos Bedeles.
- Editar otros Bedeles.
- Eliminar otros Bedeles.

El sistema impide que un Bedel elimine su propia cuenta y también evita que se elimine al último Bedel disponible.

## Resumen de instalación

El orden básico para levantar el proyecto después de clonarlo es:


git clone URL_DEL_REPOSITORIO
cd tup_utn_alumnos


Crear la base de datos:

tup_utn_alumnos

Luego ejecutar:

composer install
npm install
copy .env.example .env

Configurar la conexión a la base de datos dentro de `.env`.

Después ejecutar:

php artisan key:generate
php artisan migrate
php artisan db:seed --class=DocenteSeeder
php artisan db:seed --class=BedelSeeder
php artisan storage:link
npm run build
php artisan serve

Finalmente, abrir en el navegador la dirección indicada por Laravel.



