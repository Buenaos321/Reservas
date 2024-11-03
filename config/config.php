<?php
/**
 * Llave secreta del Jwt
 */
define(constant_name: 'SECRET_KEY', value: 'tu_clave_secreta');

#region BdConfiguration
/**
 * Tipo de base de datos a utilizar.
 * Permite alternar entre diferentes motores de base de datos.
 * Valores posibles:
 * - 'pgsql' para PostgreSQL
 * - 'mysql' para MySQL
 */
define(constant_name: 'DB_TYPE', value: 'pgsql');

/**
 * Host de la base de datos.
 * Especifica la dirección del servidor de base de datos.
 * Normalmente es 'localhost' para servidores locales o una dirección IP/remota.
 */
define(constant_name: 'DB_HOST', value: 'localhost');

/**
 * Nombre de la base de datos.
 * Indica el nombre de la base de datos a la que se conectará la aplicación.
 */
define(constant_name: 'DB_NAME', value: 'RESERVAS');

/**
 * Nombre de usuario para la conexión de base de datos.
 * Proporciona el nombre de usuario con permisos para acceder a la base de datos.
 */
define(constant_name: 'DB_USERNAME', value: 'ADMINISTRADOR');

/**
 * Contraseña del usuario de la base de datos.
 * La contraseña correspondiente al usuario definido en DB_USERNAME.
 * IMPORTANTE: Asegúrate de proteger este valor y evitar que sea accesible públicamente.
 */
define(constant_name: 'DB_PASSWORD', value: 'd!6@!7mX*sHw0-s*');
#endregion

