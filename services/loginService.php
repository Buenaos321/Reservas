<?php
require_once __DIR__ . '/../models/usuarioModel.php';

class LoginService
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function autenticar($email, $numeroDocumento, $clave): array
    {
        $usuario =null;
        if(!empty($email)){
            $usuario = $this->usuarioModel->obtenerPorEmail(email: $email);

        }
        // Obtener el usuario por email

        
        if (!$usuario && !empty($numeroDocumento)) {
            $usuario = $this->usuarioModel->obtenerPorNumeroIdentificacion(numeroDocumento: $numeroDocumento);
        }

        if (!$usuario) {
            return [
                'status' => 'error',
                'message' => 'Usuario no encontrado',
                'data' => null
            ];
        }
        // Verificar la contraseña ingresada con el hash almacenado
        if (!password_verify(password: $clave, hash: $usuario['clave'])) {
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
