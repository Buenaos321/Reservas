<?php
require_once __DIR__ . '/../services/usuarioService.php';
require_once __DIR__ . '/../config/authMiddleware.php';

class UsuarioController
{
    private $usuarioService;
    private $authMiddleware;

    public function __construct()
    {
        $this->usuarioService = new UsuarioService();
        $this->authMiddleware = new AuthMiddleware();
    }

    public function registrar(): void
    {
        try {
            // Leer el cuerpo de la solicitud y decodificar el JSON
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

            // Obtener valores del JSON decodificado
            $email = $data['email'] ?? null;
            $clave = $data['clave'] ?? null;
            $nombre = $data['nombre'] ?? null;

            // Validación de campos
            if (!$email || !$clave || !$nombre) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'Email, clave y nombre son requeridos',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // Código de respuesta 400 Bad Request
                return;
            }

            // Llamar al servicio para registrar el usuario
            $respuesta = $this->usuarioService->registrarUsuario(email: $email, clave: $clave, nombre: $nombre);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error en el registro del usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function obtenerPorId($id): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Token válido, continuar con la obtención del usuario
            $respuesta = $this->usuarioService->obtenerUsuarioPorId(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function actualizarUsuario($id): void
    {
        try {
            $this->authMiddleware->verificarToken();

            // Leer y decodificar el JSON del cuerpo de la solicitud
            $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

            // Extraer valores del JSON, si están presentes
            $nombre = $data['nombre'] ?? null;
            $email = $data['email'] ?? null;
            $clave = $data['clave'] ?? null;
            

            // Validación: al menos uno de los campos debe estar presente
            if (!$nombre && !$email && !$clave) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'Al menos uno de los campos (nombre, email, clave) debe ser proporcionado para actualizar',
                    'data' => null
                ]);
                http_response_code(response_code: 400); // 400 Bad Request
                return;
            }

            // Llama al servicio para actualizar el usuario con los valores proporcionados
            $respuesta = $this->usuarioService->actualizarUsuario(id: $id, email: $email, nombre: $nombre, clave: $clave);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            // Manejo de excepciones
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // 500 Internal Server Error
        }
    }



    public function eliminarUsuario($id): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Token válido, continuar con la obtención del usuario
            $respuesta = $this->usuarioService->eliminarUsuario(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }

    public function obtenerListadoUsuarios(): void
    {
        try {
            // Verificación de token JWT usando el middleware
            $this->authMiddleware->verificarToken(); // Este método lanza una excepción si el token es inválido

            // Token válido, continuar con la obtención del usuario
            $respuesta = $this->usuarioService->obtenerListadoUsuarios();
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el listado de usuarios: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 500); // Código de respuesta 500 Internal Server Error
        }
    }
}
