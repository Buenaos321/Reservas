<?php
require_once 'config.php'; // Asegúrate de incluir la configuración
require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de incluir el autoload de Composer

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class AuthMiddleware {
    public function verificarToken() : stdClass {
        // Obtiene los encabezados de la solicitud
        $headers = getallheaders(); // Cambiado para obtener todos los encabezados

        // Verifica si el encabezado Authorization está presente
        $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

        // Verifica si hay un token
        if (!$authHeader) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Token no proporcionado',
                'data' => null
            ]);
            http_response_code(response_code: 401); // Código de respuesta 401 Unauthorized
            exit; // Detiene la ejecución
        }

        // Extrae el token
        list($type, $token) = explode(separator: ' ', string: $authHeader);

        // Verifica si el tipo es Bearer
        if ($type !== 'Bearer' || !$token) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Token inválido',
                'data' => null
            ]);
            http_response_code(response_code: 401); // Código de respuesta 401 Unauthorized
            exit; // Detiene la ejecución
        }

        try {
            // Decodifica el token usando Key
            $decoded = JWT::decode($token, new Key(SECRET_KEY, 'HS256')); // Asegúrate de usar la clave correcta
            return $decoded; // Retorna el payload decodificado
        } catch (ExpiredException $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Token ha expirado',
                'data' => null
            ]);
            http_response_code(response_code: 401); // Código de respuesta 401 Unauthorized
            exit; // Detiene la ejecución
        } catch (Exception $e) {
            echo json_encode(value: [
                'status' => 'error',
                'message' => 'Token no válido: ' . $e->getMessage(),
                'data' => null
            ]);
            http_response_code(response_code: 401); // Código de respuesta 401 Unauthorized
            exit; // Detiene la ejecución
        }
    }
}
