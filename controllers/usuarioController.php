<?php
require_once __DIR__ . '/../services/usuarioService.php';

class UsuarioController {
    private $usuarioService;

    public function __construct() {
        $this->usuarioService = new UsuarioService();
    }

    public function registrar(): void {
        try {
            $email = $_POST['email'] ?? null;
            $clave = $_POST['clave'] ?? null;
            $nombre = $_POST['nombre'] ?? null;

            // Validación simple de entrada
            if (!$email || !$clave || !$nombre) {
                echo json_encode(value: [
                    'status' => 'error',
                    'message' => 'Email, clave y nombre son requeridos',
                    'data' => null
                ]);
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
        }
    }

    public function obtenerPorId($id): void {
        try {
            $respuesta = $this->usuarioService->obtenerUsuarioPorId(id: $id);
            echo json_encode(value: $respuesta);

        } catch (Exception $e) {
            // Manejo de excepciones desde el controlador
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error al obtener el usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
