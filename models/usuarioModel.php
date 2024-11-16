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
                IdUsuario AS id,
                Clave AS clave,
                Nombre AS nombre,
                Correo AS email,
                (CASE WHEN Rol IS NULL 
                THEN Rol 
                ELSE 'U'
                END) AS rol
            FROM USUARIOS 
                WHERE CORREO = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Permite obtener el usuario por numero de identificacion(cedula)
     * @param mixed $numeroDocumento
     * @return mixed
     */
    public function obtenerPorNumeroIdentificacion($numeroDocumento): mixed
    {
        $query = "SELECT 
                IdUsuario AS id,
                Clave AS clave,
                Nombre AS nombre,
                Correo AS email,
                (CASE WHEN Rol IS NULL 
                THEN Rol 
                ELSE 'U'
                END) AS rol
            FROM USUARIOS 
                WHERE NumeroDocumento = :numeroDocumento LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':numeroDocumento', $numeroDocumento);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $email, $clave, $rol, $tipoDocumento, $numeroDocumento): mixed
    {
        if(empty($rol)){
            $rol='U';
        }
        if(empty($tipoDocumento)){
            $tipoDocumento= 'CC';
        }


        $query = "INSERT INTO USUARIOS 
            (Nombre,Correo, Clave, Rol, TipoDocumento, NumeroDocumento) VALUES 
            (:nombre,:email,:clave,:rol,:tipoDocumento,:numeroDocumento)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':clave', $clave);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':tipoDocumento', $tipoDocumento);
        $stmt->bindParam(':numeroDocumento', $numeroDocumento);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function consultarClave($id): mixed
    {
        $query = "SELECT 
            IdUsuario AS id,
            Clave AS clave
        FROM USUARIOS WHERE IdUsuario = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id): mixed
    {
        $query = "SELECT 
            IdUsuario AS id,
            Nombre AS nombre,
            Correo AS email,
            Rol AS rol,
            TipoDocumento AS tipoDocumento,
            NumeroDocumento AS numeroDocumento
        FROM USUARIOS WHERE IdUsuario = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Para actualizar un usuario se requiere que alguno de los datos de actualizacion 
     * se encuentre definido de lo contrario no se hara la actualizacion
     */
    public function actualizarUsuario($id, $nombre, $email, $rol, $tipoDocumento, $numeroDocumento): bool
    {
        if (empty($nombre) && empty($email) && empty($rol) && empty($tipoDocumento) && empty($numeroDocumento)) {
            return false;
        }
        // Comienza la construcción de la consulta
        $query = "UPDATE USUARIOS SET ";
        $params = []; // Array para almacenar los parámetros

        // Agregar campos a la consulta y parámetros según corresponda

        if (!empty($nombre)) {
            $query .= "Nombre = :nombre, ";
            $params[':nombre'] = $nombre;
        }

        if (!empty($email)) {
            $query .= "Correo = :email, ";
            $params[':email'] = $email;
        }

        if (!empty($rol)) {
            $query .= "Rol = :rol, ";
            $params[':rol'] = $rol;
        }

        if (!empty($tipoDocumento)) {
            $query .= "TipoDocumento = :tipoDocumento, ";
            $params[':tipoDocumento'] = $tipoDocumento;
        }

        if (!empty($numeroDocumento)) {
            $query .= "NumeroDocumento = :numeroDocumento, ";
            $params[':numeroDocumento'] = $numeroDocumento;
        }

        // Eliminar la última coma y espacio
        $query = rtrim(string: $query, characters: ', ');

        // Añadir la cláusula WHERE
        $query .= " WHERE IdUsuario = :id";
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
        $query = "DELETE FROM usuarios WHERE IdUsuario = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function obtenerListadoUsuarios(): mixed
    {
        $query = "SELECT 
            IdUsuario AS id,
            Nombre AS nombre,
            Correo AS email,
            Rol AS rol,
            TipoDocumento AS tipoDocumento,
            NumeroDocumento AS numeroDocumento
        FROM USUARIOS";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        // Cambiar fetch a fetchAll para obtener todos los usuarios
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Permite el modificar la contraseña del usuario
     * @param mixed $idUsuario
     * @param mixed $nuevaClaveHashed enviar la contraseña encriptada
     * @return bool
     */
    public function actualizarClaveUsuario($idUsuario, $nuevaClaveHashed): bool
    {

        // Conexión a la base de datos (usando PDO)
        $sql = "UPDATE usuarios SET Clave = :nuevaClave WHERE IdUsuario = :idUsuario";

        // Preparar la consulta
        $stmt = $this->db->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':nuevaClave', $nuevaClaveHashed, PDO::PARAM_STR);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);

        // Ejecutar la consulta
        return $stmt->execute();

    }


}
