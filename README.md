# Documentación de la API de Reservas

## Índice

1. [Introducción](#introducción)
2. [Requisitos](#requisitos)
3. [Instalación](#instalación)
4. [Configuración](#configuración)
5. [Ejecución](#ejecución)
6. [Pantalla de Estado de la API](#pantalla-de-estado-de-la-api)
7. [Errores Frecuentes al Instalar la App](#errores-frecuentes-al-instalar-la-app)
8. [Uso de la API](#uso-de-la-api)
9. [Licencia](#licencia)

## Introducción

Esta API permite gestionar las reservas de espacios, incluyendo la creación, consulta y eliminación de reservas. Está diseñada para facilitar la administración de espacios de una manera eficiente y centralizada.

## Requisitos

- **PHP 7.4 o superior**
- **Extensiones de PHP necesarias**: 
  - `pdo_mysql` para MySQL
  - `pdo_pgsql` para PostgreSQL (si se usa este motor)
- **Servidor web**: Apache, Nginx, o cualquier servidor compatible con PHP
- **Composer** (gestor de dependencias de PHP)

## Instalación

1. Clona este repositorio:
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   ```
2. Navega al directorio del proyecto:
   ```bash
   cd tu-repositorio
   ```
3. Instala las dependencias de PHP:
   ```bash
   composer install
   ```

## Configuración

1. **Configuración de la Base de Datos**: En el archivo `config.php`, define las constantes de conexión a la base de datos. Aquí puedes alternar entre MySQL y PostgreSQL según el valor de `DB_TYPE`.

   ```php
   define('DB_TYPE', 'mysql');       // Cambia a 'pgsql' para PostgreSQL
   define('DB_HOST', 'localhost');   // Dirección del servidor de base de datos
   define('DB_NAME', 'RESERVAS');    // Nombre de la base de datos
   define('DB_USERNAME', 'USUARIO'); // Usuario de la base de datos
   define('DB_PASSWORD', 'PASSWORD'); // Contraseña de la base de datos
   ```

   > **Nota**: Asegúrate de tener habilitadas las extensiones `pdo_mysql` o `pdo_pgsql` en PHP, dependiendo del motor de base de datos que uses.

## Ejecución

Inicia el servidor local para verificar que la API esté en funcionamiento:

```bash
php -S localhost:8000
```

Luego, accede a `http://localhost:8000` en el navegador para ver la pantalla de inicio.

## Pantalla de Estado de la API

La API proporciona una **pantalla de inicio** que verifica:

- Si la API está en ejecución.
- Si la conexión con la base de datos fue exitosa o si ocurrió algún error.

### Mensaje de Error de Conexión

Si la conexión con la base de datos **no fue exitosa**, la pantalla de inicio mostrará un mensaje de error con la excepción correspondiente. Esto facilita la identificación y resolución de problemas de conexión a la base de datos.


## Uso de la API

Describe aquí los endpoints más importantes de la API, por ejemplo:

### Endpoints Principales

Basado en la información proporcionada, aquí te dejo la estructura actualizada de los endpoints para manejar reservas:

### Endpoints Actualizados de Reservas

1. **Obtener Lista de Reservas**
   - **Ruta**: `/reservas/obtenerlista`
   - **Método**: `POST`
   - **Descripción**: Lista todas las reservas registradas en el sistema.
   - **Ejemplo**:
     ```bash
     POST http://localhost/reservas/?route=reservas/obtenerlista
     ```
   - **Headers**:
     - `Content-Type`: `application/json`
     - `Authorization`: `Bearer {TOKEN}` 
   - **Respuesta**:
     ```json
     {
       "status": "success",
       "message": "Reservas obtenidas correctamente",
       "data": [
         {
           "idReserva": 1,
           "idSalon": 10,
           "nombreSalon": "Sala A",
           "capacidadSalon": 30,
           "ubicacionSalon": "Piso 1",
           "idUsuarioReserva": 5,
           "fecha": "2024-11-25",
           "horaInicio": "09:00:00",
           "horaFin": "10:00:00",
           "estado": "A",
           "fechaReserva": "2024-11-20T12:00:00Z"
         }
       ]
     }
     ```

2. **Crear Reservas**
   - **Ruta**: `/reservas/agregar`
   - **Método**: `POST`
   - **Descripción**: Permite crear nuevas reservas en un rango de fechas y horas, con intervalos de 1 hora.
   - **Parámetros del Cuerpo**:
     - `idSalon` (int, requerido): ID del salón que se quiere reservar.
     - `idUsuario` (int, requerido): ID del usuario que realiza la reserva.
     - `fechaInicio` (string, requerido): Fecha de inicio del rango (Formato: `YYYY-MM-DD`).
     - `fechaFin` (string, requerido): Fecha de fin del rango (Formato: `YYYY-MM-DD`).
     - `horaInicio` (string, requerido): Hora de inicio del rango (Formato: `HH:MM`).
     - `horaFin` (string, requerido): Hora de fin del rango (Formato: `HH:MM`).
   - **Ejemplo**:
     ```bash
     POST http://localhost/reservas/?route=reservas/agregar
     ```
   - **Cuerpo de la Solicitud**:
     ```json
     {
       "idSalon": 1,
       "idUsuario": 2,
       "fechaInicio": "2024-11-21",
       "fechaFin": "2024-11-23",
       "horaInicio": "09:00",
       "horaFin": "17:00"
     }
     ```
   - **Headers**:
     - `Content-Type`: `application/json`
     - `Authorization`: `Bearer {TOKEN}` 
   - **Respuesta**:
     ```json
     {
       "status": "success",
       "message": "Reservas creadas exitosamente.",
       "data": null
     }
     ```

3. **Actualizar Reserva**
   - **Ruta**: `/reservas/modificar`
   - **Método**: `PUT`
   - **Descripción**: Permite actualizar el estado de una reserva específica.
   - **Parámetros del Cuerpo**:
     - `idReserva` (int, requerido): ID de la reserva que se desea actualizar.
     - `estado` (string, requerido): Nuevo estado de la reserva (`A` para Activa, `C` para Cancelada, etc.).
   - **Ejemplo**:
     ```bash
     PUT http://localhost/reservas/?route=reservas/modificar&id=6
     ```
   - **Cuerpo de la Solicitud**:
     ```json
     {
       "estado": "C"
     }
     ```
   - **Headers**:
     - `Content-Type`: `application/json`
     - `Authorization`: `Bearer {TOKEN}` 
   - **Respuesta**:
     ```json
     {
       "status": "success",
       "message": "Reserva actualizada correctamente.",
       "data": {
         "id": 6
       }
     }
     ```

4. **Eliminar Reserva**
   - **Ruta**: `/reservas/eliminar`
   - **Método**: `DELETE`
   - **Descripción**: Elimina una reserva específica usando su ID.
   - **Parámetros**:
     - `id` (int, requerido): ID de la reserva a eliminar.
   - **Ejemplo**:
     ```bash
     DELETE http://localhost/reservas/?route=reservas/eliminar&id=8
     ```
   - **Headers**:
     - `Authorization`: `Bearer {TOKEN}` 
   - **Respuesta**:
     ```json
     {
       "status": "success",
       "message": "Reserva eliminada correctamente.",
       "data": {
         "id": 8
       }
     }
     ```

### Notas Importantes
- Todos los métodos `POST`, `PUT`, y `DELETE` requieren que el cuerpo de la solicitud esté en formato JSON.
- Asegúrate de incluir el token de autorización en los headers cuando el endpoint lo requiera.
- En caso de errores, la respuesta del servidor incluirá un mensaje detallado en el campo `message`.


## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](https://opensource.org/licenses/MIT).

---






# Errores Frecuentes al Instalar la App

1. **Error "could not find driver"**:
   - Indica que la extensión de PHP para PostgreSQL no está habilitada o instalada. 
   - **Solución**:
     - Para habilitar la extensión en Windows o macOS, busca y descomenta las líneas `;extension=pdo_pgsql` y `;extension=pgsql` en el archivo `php.ini`.
     - En Linux, instala el módulo de PHP para PostgreSQL usando `sudo apt-get install php-pgsql` (Debian/Ubuntu) o `sudo yum install php-pgsql` (RHEL/CentOS), y reinicia el servidor web.

2. **Error de conexión a la base de datos**:
   - Puede ocurrir si las credenciales en el archivo de configuración son incorrectas o si el servidor de base de datos no está disponible.
   - **Solución**: Verifica que `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, y `DB_NAME` sean correctos y que el servidor de base de datos esté funcionando.

3. **Error de CORS (Cross-Origin Resource Sharing)**:
   - Puede ocurrir si intentas acceder a la API desde un dominio diferente y no se permiten los orígenes adecuados.
   - **Solución**: Asegúrate de que la cabecera `Access-Control-Allow-Origin` esté configurada correctamente en tu API. Por ejemplo:
     ```php
     header('Access-Control-Allow-Origin: *');
     ```

4. **Error en el método PATCH**:
   - Si el formato del cuerpo de la solicitud no es válido, la API puede devolver un error.
   - **Solución**: Asegúrate de enviar el cuerpo de la solicitud en el formato JSON correcto y de que el campo a actualizar exista en el recurso.

5. **Error de autorización**:
   - Si un endpoint requiere un token y no se proporciona uno, o si el token es inválido.
   - **Solución**: Asegúrate de incluir el token de autorización en los headers de la solicitud para los endpoints que lo requieran:
     ```http
     Authorization: Bearer {token}
     ```

6. **Error en la respuesta de la API**:
   - Si la API no devuelve el formato esperado, podría deberse a un problema en la lógica de la aplicación o en la configuración del servidor.
   - **Solución**: Verifica los logs de la aplicación y la configuración del servidor para identificar el problema.

7. **Mensajes de error visibles en la pantalla de inicio de la API**:
   - La API tiene una pantalla de inicio que indica si está en ejecución y si la conexión con la base de datos fue exitosa. Si la conexión no fue exitosa, mostrará el mensaje de excepción.
   - **Solución**: Revisa la consola del servidor para entender el mensaje de error.

### Nota Adicional

Es importante reiniciar el servidor (Apache, Nginx o cualquier servidor que estés usando) después de habilitar o instalar extensiones, así como después de realizar cambios en la configuración de CORS o en la lógica de la API.
