<?php
require_once __DIR__ . '/../models/salonesModel.php';

class salonesService
{
    private $salonesModel;

    public function __construct()
    {
        $this->salonesModel = new salonesModel();
    }

    public function obtenersalonPorId($id): array
    {
        try {
            $salones = $this->salonesModel->obtenerPorId(id: $id);
            if ($salones) {
                return [
                    'status' => 'success',
                    'data' => $salones
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'salon no encontrado',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el salón: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function obtenerListadoSalones(): array
    {
        try {
            $listaSalones = $this->salonesModel->obtenerListadoSalones();
            return [
                'status' => 'success',
                'data' => $listaSalones
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener el listado de salones: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function agregarSalon($nombre, $ubicacion, $recursos, $estado): array
    {
        try {
            $salonId = $this->salonesModel->agregar(nombre: $nombre, ubicacion: $ubicacion, recursos: $recursos, estado: $estado);
            if ($salonId) {
                return [
                    'status' => 'success',
                    'message' => 'Salón creado',
                    'data' => ['id' => $salonId]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al crear el salón',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al crear el salón: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }


    public function modificarSalon($id, $nombre, $ubicacion, $recursos, $estado): array
    {
        try {
            $salon = $this->salonesModel->obtenerPorId(id: $id);
            if (empty($salon)) {
                return [
                    'status' => 'error',
                    'message' => 'El salon que desea modificar ya no se encuentra en el sistema',
                    'data' => null
                ];
            }

            $salonId = $this->salonesModel->modificar(id: $id, nombre: $nombre, ubicacion: $ubicacion, recursos: $recursos, estado: $estado);
            if ($salonId) {
                return [
                    'status' => 'success',
                    'message' => 'Salón modificado',
                    'data' => ['id' => $id]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al modificar el salón',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al modificar el salón: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }



    public function eliminarSalon($id): array
    {
        try {
            $salon = $this->salonesModel->obtenerPorId(id: $id);
            if (empty($salon)) {
                return [
                    'status' => 'error',
                    'message' => 'El salón que desea eliminar ya no se encuentra en el sistema',
                    'data' => null
                ];
            }

            $resultado = $this->salonesModel->eliminar(id: $id);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'salón eliminado',
                    'data' => $id
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al eliminar el salón',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar el salón: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }


}