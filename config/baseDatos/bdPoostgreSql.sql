-- Base de datos: `reserva_salones`
BEGIN;

-- Configuración de zona horaria y de transacción en PostgreSQL
SET TIME ZONE 'UTC';

-- Tabla principal `reservas` para almacenar detalles de reservas de salones
CREATE TABLE reservas (
    IdReserva BIGSERIAL PRIMARY KEY, -- ID único de la reserva, con autoincremento en PostgreSQL
    IdSalon BIGINT REFERENCES salones(IdSalon) ON DELETE SET NULL, -- ID del salón reservado, FK opcional a `salones`
    IdUsuario BIGINT REFERENCES usuarios(IdUsuario) ON DELETE SET NULL, -- ID del usuario que realiza la reserva, FK opcional a `usuarios`
    Fecha DATE NOT NULL, -- Fecha de la reserva
    HoraInicio TIME NOT NULL, -- Hora de inicio de la reserva
    HoraFin TIME NOT NULL, -- Hora de finalización de la reserva
    Estado VARCHAR(20) DEFAULT 'Activa', -- Estado de la reserva
    FechaReserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha y hora de creación de la reserva
);

-- Tabla `reservas_activas` para identificar reservas activas en el día
CREATE TABLE reservas_activas (
    IdSalon BIGINT NOT NULL REFERENCES salones(IdSalon) ON DELETE CASCADE, -- ID del salón con reserva activa, FK a `salones`
    IdUsuario BIGINT NOT NULL REFERENCES usuarios(IdUsuario) ON DELETE CASCADE, -- ID del usuario que tiene reserva activa, FK a `usuarios`
    FechaRegistro DATE NOT NULL -- Fecha en que se registró la reserva activa
);

-- Tabla `salones` para información de salones disponibles
CREATE TABLE salones (
    IdSalon BIGSERIAL PRIMARY KEY, -- ID único del salón, autoincremental
    Nombre VARCHAR(50) NOT NULL, -- Nombre del salón
    Ubicacion VARCHAR(100) NOT NULL, -- Ubicación del salón
    Recursos TEXT, -- Recursos disponibles en el salón
    Estado VARCHAR(20) DEFAULT 'Disponible' -- Estado del salón
);

-- Tabla `usuarios` para almacenar información de usuarios y sus roles
CREATE TABLE usuarios (
    IdUsuario BIGSERIAL PRIMARY KEY, -- ID único del usuario, autoincremental
    Nombre VARCHAR(50) NOT NULL, -- Nombre del usuario
    Correo VARCHAR(100) NOT NULL, -- Correo electrónico del usuario
    Clave VARCHAR(255) NOT NULL, -- Clave del usuario
    Rol VARCHAR(20) DEFAULT 'Usuario', -- Rol del usuario en el sistema
    TipoDocumento VARCHAR(50) NOT NULL, -- Tipo de documento de identidad
    NumeroDocumento VARCHAR(20) NOT NULL, -- Número de documento
    FechaRegistro DATE NOT NULL -- Fecha en que se registró el usuario
);

-- Índices adicionales
-- Índices de `reservas` para las columnas `IdUsuario` y `IdSalon`
CREATE INDEX idx_reservas_usuario ON reservas (IdUsuario);
CREATE INDEX idx_reservas_salon ON reservas (IdSalon);

-- Índices de `reservas_activas` para las columnas `IdUsuario` y `IdSalon`
CREATE INDEX idx_reservas_activas_usuario ON reservas_activas (IdUsuario);
CREATE INDEX idx_reservas_activas_salon ON reservas_activas (IdSalon);

COMMIT;
