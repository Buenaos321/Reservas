<?php
require_once __DIR__ . '/../models/usuarioModel.php';

class UsuarioService
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new usuarioModel();
    }

    public function registrarUsuario($email, $clave, $nombre): array
    {
        try {
            $usuarioId = $this->usuarioModel->crear(email: $email, clave: $clave, nombre: $nombre);
            if ($usuarioId) {
                return [
                    'status' => 'success',
                    'message' => 'Usuario creado',
                    'data' => ['id' => $usuarioId]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al crear el usuario',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al crear el usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function obtenerUsuarioPorId($id): array
    {
        try {
            $usuario = $this->usuarioModel->obtenerPorId(id: $id);
            if ($usuario) {
                return [
                    'status' => 'success',
                    'data' => $usuario
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Usuario no encontrado',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function actualizarUsuario($id, $email, $nombre, $clave): array
    {
        try {
            $resultado = $this->usuarioModel->actualizar(id: $id, email: $email, nombre: $nombre, clave: $clave);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Usuario actualizado',
                    'data' => null
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al actualizar el usuario',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function eliminarUsuario($id): array
    {
        try {
            $resultado = $this->usuarioModel->eliminar(id: $id);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Usuario eliminado',
                    'data' => null
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al eliminar el usuario',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
