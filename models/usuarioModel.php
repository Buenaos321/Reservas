<?php
require_once 'config/database.php';

class usuarioModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        list($this->db, $errorMessage) = $database->getConnection();

        if ($errorMessage) {
            throw new Exception(message: 'Error al conectar a la base de datos: ' . $errorMessage);
        }
    }

    /*
     * Permite obtener el usuario y la contraseña por medio de la dirección
     * de correo electronico
     */
    public function obtenerPorEmail($email): mixed
    {
        try {
            $query = "SELECT 
                ID_USUARIO AS id,
                NOMBRE AS nombre,
                CORREO AS email,
                PASSWORDHASH AS clave  
            FROM USUARIOS 
                WHERE CORREO = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception(message: 'Error al obtener el usuario: ' . $e->getMessage());
        }
    }

    public function crear($email, $clave, $nombre): mixed
    {
        $query = "INSERT INTO USUARIOS (NOMBRE,CORREO, PASSWORDHASH) VALUES (:nombre,:email, :clave)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':clave', $clave);
        $stmt->bindParam(':nombre', $nombre);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function obtenerPorId($id): mixed
    {
        $query = "SELECT 
            ID_USUARIO AS id,
            NOMBRE AS nombre,
            CORREO AS email,
            PASSWORDHASH AS clave
        FROM USUARIOS WHERE ID_USUARIO = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $email, $nombre, $clave): mixed
    {
        $query = "UPDATE USUARIOS SET CORREO = :email, NOMBRE = :nombre ,PASSWORDHASH = :clave WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':clave', $clave);
        return $stmt->execute();
    }

    public function eliminar($id): mixed
    {
        $query = "DELETE FROM USUARIOS WHERE ID_USUARIO = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
