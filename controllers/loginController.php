<?php
require_once __DIR__ . '/../config/config.php'; // Asegúrate de que la ruta sea correcta
require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de incluir el autoload de Composer
require_once __DIR__ . '/../services/loginService.php';
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class LoginController {
    private $loginService;

    public function __construct() {
        $this->loginService = new LoginService();
    }

    public function login(): void {
        // Obtiene los datos del cuerpo de la solicitud en formato JSON
        $data = json_decode(json: file_get_contents(filename: "php://input"));

        // Validación de entrada
        $idUsuario = $data->idUsuario ?? null;
        $clave = $data->clave ?? null;

        if ((empty($idUsuario)) || !$clave) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'El documento o el e-mail y clave son requeridos',
                'data' => null
            ]);
            return;
        }

        // Lógica de autenticación
        try {
            $respuesta = $this->loginService->autenticar(email: $idUsuario,numeroDocumento:$idUsuario, clave: $clave);

            // Si la autenticación es exitosa, genera el token JWT
            if ($respuesta['status'] === 'success') {
                $token = $this->generarToken(idUsuario: $respuesta['data']['id']); // Generar el token es numero de identificacion o email
                $respuesta['token'] = $token; // Agregar el token a la respuesta
            }

            echo json_encode(value: $respuesta); // Responder con JSON
        } catch (Exception $e) {
            // Manejo de excepciones desde el controlador
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Error durante la autenticación: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
    /**
     * Ahora el token guardara el identificador unico del usuario.
     * @param mixed $idUsuario
     * @return string
     */
    private function generarToken($idUsuario): string {
        // Crea el payload del token
        $payload = [
            'iat' => time(), // Tiempo en que se emite el token
            'exp' => time() + (24 * 60 * 60), // Tiempo de expiración (1 día = 24 horas)
            'id' => $idUsuario // Puedes agregar más datos si es necesario
        ];

        // Generar el token
        return JWT::encode($payload, SECRET_KEY, 'HS256'); // Agregar el algoritmo 'HS256'
    }
}
