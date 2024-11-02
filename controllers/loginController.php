<?php
require_once __DIR__ . '/../services/loginService.php';

class LoginController {
    private $loginService;

    public function __construct() {
        $this->loginService = new LoginService();
    }

    public function login(): void {
        // Obtiene los datos del cuerpo de la solicitud en formato JSON
        $data = json_decode(json: file_get_contents(filename: "php://input"));

        // Validación de entrada
        $email = $data->email ?? null;
        $clave = $data->clave ?? null;

        if (!$email || !$clave) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Email y clave son requeridos',
                'data' => null
            ]);
            return;
        }

        // Lógica de autenticación
        try {
            $respuesta = $this->loginService->autenticar($email, $clave);
            echo json_encode(value: $respuesta);
        } catch (Exception $e) {
            // Manejo de excepciones desde el controlador
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error durante la autenticación: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}
