<?php
require_once 'config.php';

/**
 * Clase Database
 * Esta clase proporciona una conexión a la base de datos utilizando PDO y soporta conexiones tanto para MySQL como PostgreSQL.
 */
class Database
{
    public $conn;

    /**
     * Establece y retorna una conexión a la base de datos.
     * 
     * Esta función determina el tipo de base de datos (MySQL o PostgreSQL) basado en una constante de configuración (`DB_TYPE`). 
     * Intenta crear una conexión utilizando PDO y devuelve tanto la conexión como cualquier mensaje de error en caso de fallo.
     *
     * @return array Un array con dos elementos: 
     *               - La conexión PDO si es exitosa, o `null` si falla.
     *               - Un mensaje de error (string) si ocurre algún problema, o `null` si no hay error.
     */
    public function getConnection(): array
    {
        $this->conn = null; // Inicializa la conexión en null
        $errorMessage = null; // Variable para almacenar el mensaje de error en caso de excepción

        try {
            // Determina el tipo de conexión en base a la constante DB_TYPE
            if (DB_TYPE === 'pgsql') {
                // Conexión a una base de datos PostgreSQL
                $this->conn = new PDO(dsn: "pgsql:host=" . DB_HOST . ";dbname=" . DB_NAME, username: DB_USERNAME, password: DB_PASSWORD);
            } else if (DB_TYPE === 'mysql'){
                // Conexión a una base de datos MySQL
                $this->conn = new PDO(dsn: "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, username: DB_USERNAME, password: DB_PASSWORD);
            }

            // Configura la codificación de caracteres a UTF-8
            $this->conn->exec(statement: "set names utf8");
        } catch (PDOException $exception) {
            // Captura el error y almacena el mensaje en caso de una excepción
            $errorMessage = "Error de conexión: " . $exception->getMessage();
        }

        // Retorna un array con la conexión (o null si falla) y el mensaje de error (o null si es exitosa)
        return [$this->conn, $errorMessage];
    }
}
