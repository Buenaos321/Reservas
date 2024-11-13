-- phpMyAdmin SQL Dump
-- Version: 5.2.1
-- URL: https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-11-2024 a las 23:08:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

-- Configuración de conjunto de caracteres para el cliente y conexión
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `reserva_salones`
-- --------------------------------------------------------
-- Estructura de tabla para `reservas`
-- Tabla principal para almacenar las reservas de los salones.
-- Incluye detalles como el usuario, salón, fecha y horario de la reserva.
CREATE TABLE
    `reservas` (
        `IdReserva` bigint (20) UNSIGNED NOT NULL, -- ID único de la reserva
        `IdSalon` bigint (20) UNSIGNED DEFAULT NULL, -- ID del salón reservado
        `IdUsuario` bigint (20) UNSIGNED DEFAULT NULL, -- ID del usuario que realiza la reserva
        `Fecha` date NOT NULL, -- Fecha de la reserva
        `HoraInicio` time NOT NULL, -- Hora de inicio de la reserva
        `HoraFin` time NOT NULL, -- Hora de finalización de la reserva
        `Estado` enum ('Activa', 'Cancelada') DEFAULT 'Activa', -- Estado de la reserva
        `FechaReserva` timestamp NULL DEFAULT current_timestamp() -- Fecha y hora en que se realizó la reserva
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `reservas_activas`
-- Tabla de control para identificar qué reservas están activas en el día.
-- Contiene el ID del usuario, el salón y la fecha de registro de la reserva activa.
CREATE TABLE
    `reservas_activas` (
        `IdSalon` bigint (20) UNSIGNED NOT NULL, -- ID del salón con una reserva activa
        `IdUsuario` bigint (20) UNSIGNED NOT NULL, -- ID del usuario que tiene una reserva activa
        `FechaRegistro` date NOT NULL -- Fecha en la que se registró la reserva activa
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `salones`
-- Tabla que almacena la información de los salones disponibles para reservas.
-- Incluye detalles como el nombre, ubicación, recursos disponibles y estado.
CREATE TABLE
    `salones` (
        `IdSalon` bigint (20) UNSIGNED NOT NULL, -- ID único del salón
        `Nombre` varchar(50) NOT NULL, -- Nombre del salón
        `Ubicacion` varchar(100) NOT NULL, -- Ubicación del salón
        `Recursos` text DEFAULT NULL, -- Recursos disponibles en el salón (opcional)
        `Estado` enum ('Disponible', 'Bloqueado') DEFAULT 'Disponible' -- Estado del salón
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `usuarios`
-- Tabla que almacena la información de los usuarios del sistema, sus roles y datos personales.
CREATE TABLE
    `usuarios` (
        `IdUsuario` bigint (20) UNSIGNED NOT NULL, -- ID único del usuario
        `Nombre` varchar(50) NOT NULL, -- Nombre del usuario
        `Correo` varchar(100) NOT NULL, -- Correo electrónico del usuario
        `Clave` varchar(255) NOT NULL, -- Contraseña del usuario (en este caso, campo renombrado)
        `Rol` enum ('Administrador', 'Usuario') DEFAULT 'Usuario', -- Rol del usuario en el sistema
        `TipoDocumento` enum (
            'Cédula de Ciudadanía',
            'Tarjeta de Identidad',
            'Pasaporte',
            'Cédula de Extranjería'
        ) NOT NULL, -- Tipo de documento de identidad del usuario
        `NumeroDocumento` varchar(20) NOT NULL, -- Número de documento del usuario
        `FechaRegistro` date NOT NULL -- Fecha en que se registró el usuario
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Índices para las tablas
-- Índices de la tabla `reservas`
ALTER TABLE `reservas` ADD PRIMARY KEY (`IdReserva`), -- Índice primario basado en el ID de la reserva
ADD KEY `fk_usuario_reserv` (`IdUsuario`), -- Índice de clave foránea para `IdUsuario`
ADD KEY `fk_usuario_rese` (`IdSalon`);

-- Índice de clave foránea para `IdSalon`
-- Índices de la tabla `reservas_activas`
ALTER TABLE `reservas_activas` ADD KEY `fk_usuario_reservas` (`IdUsuario`), -- Índice de clave foránea para `IdUsuario`
ADD KEY `fk_usuario_salones` (`IdSalon`);

-- Índice de clave foránea para `IdSalon`
-- Índices de la tabla `salones`
ALTER TABLE `salones` ADD PRIMARY KEY (`IdSalon`), -- Índice primario basado en el ID del salón
ADD UNIQUE KEY `IdSalon` (`IdSalon`);

-- Índice único para asegurar valores únicos de `IdSalon`
-- Índices de la tabla `usuarios`
ALTER TABLE `usuarios` ADD PRIMARY KEY (`IdUsuario`), -- Índice primario basado en el ID del usuario
ADD UNIQUE KEY `IdUsuario` (`IdUsuario`);

-- Índice único para asegurar valores únicos de `IdUsuario`
-- --------------------------------------------------------
-- Configuración de AUTO_INCREMENT
-- AUTO_INCREMENT de la tabla `reservas`
ALTER TABLE `reservas` MODIFY `IdReserva` bigint (20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Incremento automático para ID de reserva
-- AUTO_INCREMENT de la tabla `salones`
ALTER TABLE `salones` MODIFY `IdSalon` bigint (20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Incremento automático para ID de salón
-- AUTO_INCREMENT de la tabla `usuarios`
ALTER TABLE `usuarios` MODIFY `IdUsuario` bigint (20) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Incremento automático para ID de usuario
-- --------------------------------------------------------
-- Restricciones y relaciones entre tablas
-- Restricciones de la tabla `reservas`
ALTER TABLE `reservas` ADD CONSTRAINT `fk_usuario_rese` FOREIGN KEY (`IdSalon`) REFERENCES `salones` (`IdSalon`), -- Llave foránea a `salones`
ADD CONSTRAINT `fk_usuario_reserv` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`);

-- Llave foránea a `usuarios`
-- Restricciones de la tabla `reservas_activas`
ALTER TABLE `reservas_activas` ADD CONSTRAINT `fk_usuario_reservas` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`), -- Llave foránea a `usuarios`
ADD CONSTRAINT `fk_usuario_salones` FOREIGN KEY (`IdSalon`) REFERENCES `salones` (`IdSalon`);

-- Llave foránea a `salones`
COMMIT;

-- Restauración de configuraciones anteriores de conjunto de caracteres
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;