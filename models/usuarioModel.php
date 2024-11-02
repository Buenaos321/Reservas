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

    public function actualizarUsuario($id, $email = null, $nombre = null, $clave = null): bool
    {
        if (empty($email) && empty($nombre) && empty($clave)) {
            return false;
        }
        // Comienza la construcción de la consulta
        $query = "UPDATE USUARIOS SET ";
        $params = []; // Array para almacenar los parámetros

        // Agregar campos a la consulta y parámetros según corresponda
        if (!empty($email) ) {
            $query .= "CORREO = :email, ";
            $params[':email'] = $email;
        }
        
        if (!empty($nombre)) {
            $query .= "NOMBRE = :nombre, ";
            $params[':nombre'] = $nombre;
        }

        if (!empty($clave)) {
            $query .= "PASSWORDHASH = :clave, ";
            $params[':clave'] = $clave;
        }

        // Eliminar la última coma y espacio
        $query = rtrim(string: $query, characters: ', ');
        
        // Añadir la cláusula WHERE
        $query .= " WHERE ID_USUARIO = :id";
        $params[':id'] = $id; // Añadir el ID al array de parámetros

        // Preparar y ejecutar la consulta
        $stmt = $this->db->prepare($query);

        // Asignar los parámetros
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value);
        }

        return $stmt->execute(); // Ejecuta la consulta y devuelve el resultado
    }


    public function eliminar($id): mixed
    {
        $query = "DELETE FROM USUARIOS WHERE ID_USUARIO = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obtenerListadoUsuarios(): mixed
    {
        $query = "SELECT 
            ID_USUARIO AS id,
            NOMBRE AS nombre,
            CORREO AS email,
            PASSWORDHASH AS clave
        FROM USUARIOS";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        
        // Cambiar fetch a fetchAll para obtener todos los usuarios
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
