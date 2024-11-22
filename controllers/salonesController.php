<?php
require_once __DIR__ . '/../services/salonesService.php';
require_once __DIR__ . '/../config/authMiddleware.php';

class SalonesController
{
    private $salonesService;
    private $authMiddleware;

    public function __construct()
    {
        $this->salonesService = new salonesService();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function obtenerPorId($id): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Token válido, continuar con la obtención del salón
            $respuesta = $this->salonesService->obtenersalonPorId(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el usuario: ' . $e->getMessage(),
                'e' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function obtenerListadoSalones(): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido
            // Token válido, continuar con la obtención del salón
            $respuesta = $this->salonesService->obtenerListadoSalones();
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el listado de salones: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function agregarSalon(): void
    {
        try {
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido
            // Leer el cuerpo de la solicitud y decodificar el JSON
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

            // Obtener valores del JSON decodificado
            $nombre = $data['nombre'] ?? null;
            $ubicacion = $data['ubicacion'] ?? null;
            $recursos = $data['recursos'] ?? null;
            $estado = $data['estado'] ?? 'D';

            // Validación de campos
            if (empty($nombre) || empty($ubicacion) || empty($recursos)) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'Los campos de nombre, ubicación y recursos son requeridos para el registro del salón',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Llamar al servicio para registrar el usuario
            $respuesta = $this->salonesService->agregarSalon(nombre: $nombre, ubicacion: $ubicacion, recursos: $recursos, estado: $estado);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error en el registro del salón: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function actualizarSalon($id): void
    {
        try {
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido
            // Leer el cuerpo de la solicitud y decodificar el JSON
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

            // Obtener valores del JSON decodificado
            $nombre = $data['nombre'] ?? null;
            $ubicacion = $data['ubicacion'] ?? null;
            $recursos = $data['recursos'] ?? null;
            $estado = $data['estado'] ?? 'D';

            // Validación de campos
            if (empty($id) && (empty($nombre) || empty($ubicacion) || empty($recursos))) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'el campo de id y los campos de nombre, ubicación y recursos son requeridos para el registro del salón',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Llamar al servicio para registrar el usuario
            $respuesta = $this->salonesService->modificarSalon(id: $id, nombre: $nombre, ubicacion: $ubicacion, recursos: $recursos, estado: $estado);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al registrar el salón: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }


    public function eliminarSalon($id): void
    {
        try {
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Validación de campos
            if (empty($id)) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'El campo de id es necesario para eliminar el salón',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Llamar al servicio para registrar el usuario
            $respuesta = $this->salonesService->eliminarSalon(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al eliminar el salón: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }


}