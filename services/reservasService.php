<?php
include_once __DIR__ . '/../models/reservaModel.php';

class ReservaService {
    private $reservaModel;

    public function __construct() {
        $this->reservaModel = new ReservaModel();
    }

    // Crear múltiples reservas en un rango de fechas y horas, con intervalos de 1 hora
    public function crearReservas($idSalon, $idUsuario, $fechaInicio, $fechaFin, $horaInicio, $horaFin): array {
        try {
            // Verificar que el salón existe antes de proceder
            if (!$this->reservaModel->salonExiste($idSalon)) {
                return [
                    'status' => 'error',
                    'message' => 'El salón especificado no existe.',
                    'data' => null
                ];
            }

            $fechaInicioDT = new DateTime(datetime: $fechaInicio);
            $fechaFinDT = new DateTime(datetime: $fechaFin);

            // Iterar sobre el rango de fechas
            while ($fechaInicioDT <= $fechaFinDT) {
                $fecha = $fechaInicioDT->format(format: 'Y-m-d');

                // Iterar sobre el rango de horas en cada día, con intervalos de 1 hora
                $horaInicioDT = new DateTime(datetime: $horaInicio);
                $horaFinDT = new DateTime(datetime: $horaFin);

                while ($horaInicioDT < $horaFinDT) {
                    $horaInicioStr = $horaInicioDT->format(format: 'H:i:s');
                    
                    // Calcular la hora de finalización sumando 1 hora
                    $horaFinIntervaloDT = clone $horaInicioDT;
                    $horaFinIntervaloDT->modify(modifier: "+1 hour");

                    // No crear reservas si la hora final del intervalo supera la horaFin del rango
                    if ($horaFinIntervaloDT > $horaFinDT) {
                        break;
                    }

                    $horaFinStr = $horaFinIntervaloDT->format(format: 'H:i:s');

                    // Intentar crear la reserva para la fecha y hora actual
                    $reservaId = $this->reservaModel->crearReserva($idSalon, $idUsuario, $fecha, $horaInicioStr, $horaFinStr);

                    if (!$reservaId) {
                        return [
                            'status' => 'error',
                            'message' => 'Error al crear la reserva para la fecha ' . $fecha . ' y hora ' . $horaInicioStr,
                            'data' => null
                        ];
                    }

                    // Incrementar la hora actual por 1 hora
                    $horaInicioDT->modify(modifier: "+1 hour");
                }

                // Incrementar la fecha en un día
                $fechaInicioDT->modify(modifier: '+1 day');
            }

            // Si todas las reservas se han creado exitosamente
            return [
                'status' => 'success',
                'message' => 'Reservas creadas exitosamente.',
                'data' => null
            ];
        } catch (Exception $e) {
            // Capturar cualquier excepción y devolver el error
            return [
                'status' => 'error',
                'message' => 'Error al crear las reservas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    // Otros métodos CRUD permanecen igual
    public function obtenerReservas(): array {
        try {
            $reservas = $this->reservaModel->obtenerReservas();
            return [
                'status' => 'success',
                'message' => 'Reservas obtenidas correctamente.',
                'data' => $reservas
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al obtener las reservas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function actualizarReserva($idReserva, $estado): array {
        try {
            $resultado = $this->reservaModel->actualizarReserva($idReserva, $estado);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Reserva actualizada correctamente.',
                    'data' => ['id' => $idReserva]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al actualizar la reserva.',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al actualizar la reserva: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function eliminarReserva($idReserva): array {
        try {
            $resultado = $this->reservaModel->eliminarReserva(idReserva: $idReserva);
            if ($resultado) {
                return [
                    'status' => 'success',
                    'message' => 'Reserva eliminada correctamente.',
                    'data' => ['id' => $idReserva]
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Error al eliminar la reserva.',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al eliminar la reserva: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
?>
