<?php
require_once 'config/database.php';

class salonesModel
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

    public function obtenerPorId($id): mixed
    {
        $query = "SELECT 
            IdSalon AS id,
            Nombre AS nombre,
            Ubicacion AS ubicacion,
            Recursos AS recursos,
            (CASE WHEN Estado IS NULL 
                THEN Estado 
                ELSE 'A'
                END) AS estado
        FROM salones WHERE idSalon = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerListadoSalones(): mixed
    {
        $query = "SELECT 
            IdSalon AS id,
            Nombre AS nombre,
            Ubicacion AS ubicacion,
            Recursos AS recursos,
            (CASE WHEN Estado IS NULL 
                THEN Estado 
                ELSE 'A'
                END) AS estado
        FROM salones ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Cambiar fetch a fetchAll para obtener todos los salones
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregar($nombre, $ubicacion, $recursos, $estado): mixed
    {
        if (empty($estado)) {
            $estado = 'D';
        }

        $query = "INSERT INTO salones 
            (Nombre,Ubicacion, Recursos, Estado) VALUES 
            (:nombre,:ubicacion,:recursos,:estado)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':ubicacion', $ubicacion);
        $stmt->bindParam(':recursos', $recursos);
        $stmt->bindParam(':estado', $estado);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function modificar($id, $nombre, $ubicacion, $recursos, $estado): bool
    {
        if (empty($id) && empty($nombre) && empty($ubicacion) && empty($recursos) && empty($estado)) {
            return false;
        }
        // Comienza la construcción de la consulta
        $query = "UPDATE salones SET ";
        $params = []; // Array para almacenar los parámetros

        
        if (empty($estado)) {
            $estado = "A";
        }
        
        // Agregar campos a la consulta y parámetros según corresponda
        if (!empty($nombre)) {
            $query .= "Nombre = :nombre, ";
            $params[':nombre'] = $nombre;
        }

        if (!empty($ubicacion)) {
            $query .= "Ubicacion = :ubicacion, ";
            $params[':ubicacion'] = $ubicacion;
        }
        if (!empty($recursos)) {
            $query .= "Recursos = :recursos, ";
            $params[':recursos'] = $recursos;
        }
        if (!empty($estado)) {
            $query .= "Estado = :estado, ";
            $params[':estado'] = $estado;
        }



        // Eliminar la última coma y espacio
        $query = rtrim(string: $query, characters: ', ');

        // Añadir la cláusula WHERE
        $query .= " WHERE IdSalon = :id";
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
        $query = "DELETE FROM salones WHERE IdSalon = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}