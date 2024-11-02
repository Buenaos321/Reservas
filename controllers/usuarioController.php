<?php
require_once __DIR__ . '/../services/usuarioService.php';
require_once __DIR__ . '/../config/authMiddleware.php'; // Asegúrate de incluir el middleware

class UsuarioController {
    private $usuarioService;
    private $authMiddleware;

    public function __construct() {
        $this->usuarioService = new UsuarioService();
        $this->authMiddleware = new AuthMiddleware(); // Inicializa el middleware
    }

    public function registrar(): void {
        try {
            $email = $_POST['email'] ?? null;
            $clave = $_POST['clave'] ?? null;
            $nombre = $_POST['nombre'] ?? null;

            // Validación simple de entrada
            if (!$email || !$clave || !$nombre) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Email, clave y nombre son requeridos',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            $respuesta = $this->usuarioService->registrarUsuario(email: $email, clave: $clave, nombre: $nombre);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            // Manejo de excepciones desde el controlador
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error en el registro del usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function obtenerPorId($id): void {
        // Verifica el token JWT
        $decoded = $this->authMiddleware->verificarToken(); // Llama al método sin parámetros, ya que se obtiene con getallheaders() en el middleware

        try {
            // El token es válido, continúa con la lógica de obtención del usuario
            $respuesta = $this->usuarioService->obtenerUsuarioPorId(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            // Manejo de excepciones desde el controlador
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }
}
