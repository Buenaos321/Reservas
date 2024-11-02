<?php
require_once __DIR__ . '/../models/usuarioModel.php';

class LoginService {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function autenticar($email, $clave): array {
        // Obtener el usuario por email
        $usuario = $this->usuarioModel->obtenerPorEmail($email);

        if (!$usuario) {
            return [
                'status' => 'error',
                'message' => 'Usuario no encontrado',
                'data' => null
            ];
        }

        // Verificar la clave (puedes usar password_verify si las claves están hasheadas)
        if ($usuario['clave'] !== $clave) {
            return [
                'status' => 'error',
                'message' => 'Clave incorrecta',
                'data' => null
            ];
        }

        // Si la autenticación es exitosa
        return [
            'status' => 'success',
            'message' => 'Inicio de sesión exitoso',
            'data' => [
                'id' => $usuario['id'],
                'email' => $usuario['email'],
                'nombre' => $usuario['nombre'],
            ]
        ];
    }
}
