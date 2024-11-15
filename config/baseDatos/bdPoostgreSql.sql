-- PostgreSQL SQL Dump
-- Base de datos: `reserva_salones`
-- --------------------------------------------------------
-- Creación de esquema (si es necesario)
CREATE SCHEMA IF NOT EXISTS public;
SET search_path TO public;

-- Configuración del modo de transacción y zona horaria
START TRANSACTION;

SET TIME ZONE 'UTC';

-- --------------------------------------------------------
-- Estructura de tabla para `salones`
-- Tabla que almacena la información de los salones disponibles para reservas.
CREATE TABLE salones (
    IdSalon BIGSERIAL PRIMARY KEY, -- ID único del salón, con incremento automático
    Nombre VARCHAR(50) NOT NULL, -- Nombre descriptivo del salón
    Ubicacion VARCHAR(100) NOT NULL, -- Ubicación física del salón
    Recursos TEXT, -- Recursos disponibles en el salón (ej., proyectores, sillas, pizarras)
    Estado VARCHAR(1) DEFAULT 'D' CHECK (Estado IN ('D', 'B')) -- Estado del salón (D = Disponible, B = Bloqueado)
);

-- --------------------------------------------------------
-- Estructura de tabla para `usuarios`
-- Tabla que almacena la información de los usuarios del sistema.
CREATE TABLE usuarios (
    IdUsuario BIGSERIAL PRIMARY KEY, -- ID único del usuario, con incremento automático
    NumeroDocumento VARCHAR(20) NOT NULL UNIQUE, -- Número de documento único para identificación del usuario
    Nombre VARCHAR(50) NOT NULL, -- Nombre completo del usuario
    Correo VARCHAR(100) NOT NULL UNIQUE, -- Correo electrónico único del usuario
    Clave VARCHAR(255) NOT NULL, -- Contraseña en formato cifrado
    Rol VARCHAR(1) DEFAULT 'U' CHECK (Rol IN ('A', 'U')), -- Rol del usuario en el sistema (A = Administrador, U = Usuario)
    TipoDocumento VARCHAR(2) NOT NULL DEFAULT 'CC' CHECK (TipoDocumento IN ('CC', 'TI', 'PA', 'CE', 'PE')), -- Tipo de documento de identidad:
                                                                                                             -- CC = Cédula de Ciudadanía
                                                                                                             -- TI = Tarjeta de Identidad
                                                                                                             -- PA = Pasaporte
                                                                                                             -- CE = Cédula de Extranjería
                                                                                                             -- PE = Permiso de Permanencia
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha y hora en que se registró el usuario
);

-- --------------------------------------------------------
-- Estructura de tabla para `reservas`
-- Tabla principal para almacenar las reservas de los salones.
CREATE TABLE reservas (
    IdReserva BIGSERIAL PRIMARY KEY, -- ID único de la reserva, con incremento automático
    IdSalon BIGINT NOT NULL, -- ID del salón reservado, referencia a la tabla `salones`
    IdUsuario BIGINT NOT NULL, -- ID del usuario que realiza la reserva, referencia a la tabla `usuarios`
    Fecha DATE NOT NULL, -- Fecha específica para la reserva
    HoraInicio TIME NOT NULL, -- Hora de inicio de la reserva
    HoraFin TIME NOT NULL, -- Hora de finalización de la reserva
    Estado VARCHAR(1) DEFAULT 'A' CHECK (Estado IN ('D', 'A', 'C')), -- Estado de la reserva (D = Disponible, A = Activa, C = Cancelada)
    FechaReserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora en que se realizó la reserva
    -- Definición de claves foráneas
    CONSTRAINT fk_reserva_salon FOREIGN KEY (IdSalon) REFERENCES salones(IdSalon),
    CONSTRAINT fk_reserva_usuario FOREIGN KEY (IdUsuario) REFERENCES usuarios(IdUsuario)
);

-- --------------------------------------------------------
-- Creación de índices para optimización
-- Índices de la tabla `reservas`
CREATE INDEX idx_reservas_idusuario ON reservas(IdUsuario); -- Índice de clave foránea para `IdUsuario`
CREATE INDEX idx_reservas_idsalon ON reservas(IdSalon); -- Índice de clave foránea para `IdSalon`

-- --------------------------------------------------------
-- Commit de la transacción
COMMIT;
