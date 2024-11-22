<?php
include_once __DIR__ . '/../services/reservasService.php';
require_once __DIR__ . '/../config/authMiddleware.php';

class ReservaController
{
    private $reservaService;
    private $authMiddleware;

    public function __construct()
    {
        $this->reservaService = new ReservaService();
        $this->authMiddleware = new AuthMiddleware();
    }

    // Crear múltiples reservas en un rango de fechas y horas
    public function crear(): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido
            // Leer el cuerpo de la solicitud y decodificar el JSON
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

            // Almacenar los datos recibidos en variables
            $idSalon = $data['idSalon'];
            $idUsuario = $data['idUsuario'];
            $fechaInicio = $data['fechaInicio'];
            $fechaFin = $data['fechaFin'];
            $horaInicio = $data['horaInicio'];
            $horaFin = $data['horaFin'];


            // Llamar al servicio para crear las reservas en el rango especificado
            $respuesta = $this->reservaService->crearReservas(
                idSalon: $idSalon,
                idUsuario: $idUsuario,
                fechaInicio: $fechaInicio,
                fechaFin: $fechaFin,
                horaInicio: $horaInicio,
                horaFin: $horaFin
            );

            // Verificar la respuesta del servicio
            echo json_encode(value: $respuesta);
        } catch (Exception $e) {
            // Manejo de errores
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al crear las reservas: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    // Obtener todas las reservas
    public function obtener(): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Llamar al servicio para obtener todas las reservas
            $respuesta = $this->reservaService->obtenerReservas();

            echo json_encode(value: $respuesta);
        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener las reservas:' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    // Actualizar el estado de una reserva específica
    public function actualizar($idReserva): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            if (empty($idReserva)) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'El campo de id es necesario para actualizar el estado de la reserva',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Leer el cuerpo de la solicitud y decodificar el JSON
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);
            // Almacenar los datos recibidos en variables
            $estado = $data['estado'];

            // Llamar al servicio para actualizar la reserva
            $respuesta = $this->reservaService->actualizarReserva(
                idReserva: $idReserva,
                estado: $estado
            );

            echo json_encode(value: $respuesta);
        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al actualizar la reserva:' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    // Eliminar una reserva específica
    public function eliminar($idReserva): void
    {
        try {

            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            if (empty($idReserva)) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'El campo de id es necesario para eliminar el la reserva',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Llamar al servicio para eliminar la reserva
            $respuesta = $this->reservaService->eliminarReserva(idReserva: $idReserva);

            echo json_encode(value: $respuesta);
        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al eliminar la reserva:' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }
}
?>