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
            $usuario = $this->usuarioModel->obtenerPorEmail(email: $email);

            if ($usuario) {
                return [
                    'status' => 'error',
                    'message' => 'El usuario con el correo ingresado ya se encuentra registrado en el sistema',
                    'data' => null
                ];
            }
            if (!empty($clave)) {
                $clave = password_hash(password: $clave, algo: PASSWORD_BCRYPT);
            }
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
            if ($email != null) {
                $usuario = $this->usuarioModel->obtenerPorEmail(email: $email);
                if ($usuario && $usuario['id'] != $id) {
                    return [
                        'status' => 'error',
                        'message' => 'El correo al que desea cambiar ya esta siendo usado por otro usuario',
                        'data' => null
                    ];
                }
            }

            if (!empty($clave)) {
                $clave = password_hash(password: $clave, algo: PASSWORD_BCRYPT);
            }

            $resultado = $this->usuarioModel->actualizarUsuario(id: $id, email: $email, nombre: $nombre, clave: $clave);
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
            $usuario = $this->usuarioModel->obtenerPorId(id: $id);
            if (empty($usuario)) {
                return [
                    'status' => 'error',
                    'message' => 'El usuario que desea eliminar ya no se encuentra en el sistema',
                    'data' => null
                ];
            }

            $resultado = $this->usuarioModel->eliminar(id: $id);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Usuario eliminado',
                    'data' => $id
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al eliminar el usuario',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            // Aquí podrías registrar el error
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function obtenerListadoUsuarios(): array
    {
        try {
            $listaUsuarios = $this->usuarioModel->obtenerListadoUsuarios();
            return [
                'status' => 'success',
                'data' => $listaUsuarios
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el listado usuarios: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

}
