<?php
require_once __DIR__ . '/../models/usuarioModel.php';

class UsuarioService
{
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new usuarioModel();
    }

    public function registrarUsuario($nombre, $email, $clave, $rol, $tipoDocumento, $numeroDocumento): array
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

            $usuario = $this->usuarioModel->obtenerPorNumeroIdentificacion(numeroDocumento: $numeroDocumento);

            if ($usuario) {
                return [
                    'status' => 'error',
                    'message' => 'El usuario con el numero de identificación ingresado ya se encuentra registrado en el sistema',
                    'data' => null
                ];
            }


            if (!empty($clave)) {
                $clave = password_hash(password: $clave, algo: PASSWORD_BCRYPT);
            }

            $usuarioId = $this->usuarioModel->crear(nombre: $nombre, email: $email, clave: $clave, rol: $rol, tipoDocumento: $tipoDocumento, numeroDocumento: $numeroDocumento);
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

    public function actualizarUsuario($id, $nombre, $email, $rol, $tipoDocumento, $numeroDocumento): array
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

            $resultado = $this->usuarioModel->actualizarUsuario(id: $id, nombre: $nombre, email: $email, rol: $rol, tipoDocumento: $tipoDocumento, numeroDocumento: $numeroDocumento);

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

    /**
     * Recibe como parametros la clave antigua y la clave nueva 
     * para posteriormente hacer las validaciones para actualizar la contraseña
     * @param mixed $idUsuario
     * @param mixed $claveAnterior
     * @param mixed $nuevaClave
     * @return array
     */
    public function actualizarClave($idUsuario, $claveAnterior, $nuevaClave): array
    {
        try {
            // Verificar que todos los parámetros necesarios estén presentes
            if (empty($idUsuario) || empty($claveAnterior) || empty($nuevaClave)) {
                return [
                    'status' => 'error',
                    'message' => 'Para actualizar la contraseña es necesario enviar el identificador del usuario, la clave anterior y la nueva clave',
                    'data' => null
                ];
            }

            // Obtener el usuario por email o número de identificación
            $usuario = $this->usuarioModel->obtenerPorEmail(email: $idUsuario) ?: $this->usuarioModel->obtenerPorNumeroIdentificacion($idUsuario);

            // Verificar si el usuario existe
            if (!$usuario) {
                return [
                    'status' => 'error',
                    'message' => 'Usuario no encontrado',
                    'data' => null
                ];
            }

            // Verificar si la contraseña anterior coincide con la almacenada
            if (!password_verify(password: $claveAnterior, hash: $usuario['clave'])) {
                return [
                    'status' => 'error',
                    'message' => 'La contraseña actual no coincide con la guardada por el usuario',
                    'data' => null
                ];
            }

            // Hashear la nueva contraseña
            $nuevaClaveHashed = password_hash(password: $nuevaClave, algo: PASSWORD_BCRYPT);

            // Actualizar la contraseña en la base de datos
            $actualizado = $this->usuarioModel->actualizarClaveUsuario(idUsuario: $usuario['id'], nuevaClaveHashed: $nuevaClaveHashed);

            if (!$actualizado) {
                return [
                    'status' => 'error',
                    'message' => 'Error al actualizar la contraseña',
                    'data' => null
                ];
            } else {
                return [
                    'status' => 'success',
                    'message' => 'Contraseña acttualizada con exito',
                    'data' => null
                ];
            }



        } catch (Exception $e) {
            // Manejo de excepciones
            return [
                'status' => 'error',
                'message' => 'Error al momento de actualizar la contraseña del usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }


}
