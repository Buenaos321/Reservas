<?php
include_once __DIR__ . '/../db.php';

class ReservaModel
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

    // Crear una nueva reserva
    public function crearReserva($idSalon, $idUsuario, $fecha, $horaInicio, $horaFin): mixed
    {
        $query = "INSERT INTO reservas (
            IdSalon, 
            IdUsuario, 
            Fecha, 
            HoraInicio, 
            HoraFin, 
            Estado) 
        VALUES (
            :idSalon, 
            :idUsuario, 
            :fecha, 
            :horaInicio, 
            :horaFin, 
            'A'
        )";
        $stmt = $this->db->prepare($query);

        // Asignar parámetros
        $stmt->bindParam(':idSalon', $idSalon);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':horaInicio', $horaInicio);
        $stmt->bindParam(':horaFin', $horaFin);

        return $stmt->execute();
    }

    // Obtener todas las reservas
    public function obtenerReservas(): mixed
    {
        $query = "SELECT 
            reservas.IdReserva AS idReserva,
            reservas.Idsalon AS idSalon,
            salones.Nombre AS nombreSalon,
            30 AS capacidadSalon,
            salones.Ubicacion AS ubicacionSalon,
            reservas.IdUsuario AS idUsuarioReserva,
            reservas.Fecha AS Fecha,
            reservas.HoraInicio AS horaInicio,
            reservas.HoraFin AS horaFin,
            reservas.Estado AS estado,
            reservas.FechaReserva AS fechaReserva
        FROM reservas 
        LEFT JOIN salones ON
            reservas.Idsalon = salones.Idsalon 
        LEFT JOIN usuarios ON 
            reservas.IdUsuario = usuarios.IdUsuario ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar una reserva específica
    public function actualizarReserva($idReserva, $estado): mixed
    {
        $query = "UPDATE reservas SET Estado = :estado WHERE IdReserva = :idReserva";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':idReserva', $idReserva);
        $stmt->bindParam(':estado', $estado);

        return $stmt->execute();
    }

    // Eliminar una reserva específica
    public function eliminarReserva($idReserva): mixed
    {
        $query = "DELETE FROM reservas WHERE IdReserva = :idReserva";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':idReserva', $idReserva);

        return $stmt->execute();
    }
}
?>