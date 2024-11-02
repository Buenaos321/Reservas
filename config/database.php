<?php
class Database {
    private $host = "localhost";
    private $db_name = "RESERVAS";
    private $username = "ADMINISTRADOR";
    private $password = "d!6@!7mX*sHw0-s*";
    public $conn;

    public function getConnection(): array {
        $this->conn = null;
        $errorMessage = null; // Variable para almacenar el mensaje de error

        try {
            $this->conn = new PDO(dsn: "mysql:host=" . $this->host . ";dbname=" . $this->db_name, username: $this->username, password: $this->password);
            $this->conn->exec(statement: "set names utf8");
        } catch (PDOException $exception) {
            $errorMessage = "Error de conexión: " . $exception->getMessage(); // Almacena el mensaje de error
        }

        return [$this->conn, $errorMessage]; // Retorna la conexión y el mensaje de error
    }
}
