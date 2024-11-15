-- phpMyAdmin SQL Dump
-- Version: 5.2.1
-- URL: https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-11-2024 a las 23:08:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

-- Configuración de conjunto de caracteres para el cliente y conexión
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `reserva_salones`
-- --------------------------------------------------------
-- Estructura de tabla para `reservas`
-- Tabla principal para almacenar las reservas de los salones.
-- Incluye detalles como el usuario que hace la reserva, el salón seleccionado,
-- la fecha y el horario de la reserva, así como el estado de la misma.
CREATE TABLE `reservas` (
    `IdReserva` bigint(20) UNSIGNED NOT NULL, -- ID único de la reserva, generado automáticamente
    `IdSalon` bigint(20) UNSIGNED DEFAULT NULL, -- ID del salón reservado, referencia a la tabla `salones`
    `IdUsuario` bigint(20) UNSIGNED DEFAULT NULL, -- ID del usuario que realiza la reserva, referencia a la tabla `usuarios`
    `Fecha` date NOT NULL, -- Fecha específica para la reserva
    `HoraInicio` time NOT NULL, -- Hora de inicio de la reserva
    `HoraFin` time NOT NULL, -- Hora de finalización de la reserva
    `Estado` enum('D', 'A', 'C') DEFAULT 'A', -- Estado de la reserva (D = Disponible, A = Activa, C = Cancelada)
    `FechaReserva` timestamp NULL DEFAULT current_timestamp() -- Fecha y hora en que se realizó la reserva
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `salones`
-- Tabla que almacena la información de los salones disponibles para reservas.
-- Incluye el nombre, la ubicación, los recursos disponibles en cada salón,
-- y el estado (disponible o bloqueado).
CREATE TABLE `salones` (
    `IdSalon` bigint(20) UNSIGNED NOT NULL, -- ID único del salón, generado automáticamente
    `Nombre` varchar(50) NOT NULL, -- Nombre descriptivo del salón
    `Ubicacion` varchar(100) NOT NULL, -- Ubicación física del salón
    `Recursos` text DEFAULT NULL, -- Recursos disponibles en el salón (ej., proyectores, sillas, pizarras)
    `Estado` enum('D', 'B') DEFAULT 'D' -- Estado del salón (D = Disponible, B = Bloqueado)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para `usuarios`
-- Tabla que almacena la información de los usuarios del sistema.
-- Contiene datos personales, credenciales y roles de cada usuario.
CREATE TABLE `usuarios` (
    `IdUsuario` bigint(20) UNSIGNED NOT NULL, -- ID único del usuario, generado automáticamente
    `NumeroDocumento` varchar(20) NOT NULL UNIQUE, -- Número de documento único para identificación del usuario
    `Nombre` varchar(50) NOT NULL, -- Nombre completo del usuario
    `Correo` varchar(100) NOT NULL UNIQUE, -- Correo electrónico único del usuario
    `Clave` varchar(255) NOT NULL, -- Contraseña en formato cifrado
    `Rol` enum('A', 'U') DEFAULT 'U', -- Rol del usuario en el sistema (A = Administrador, U = Usuario)
    `TipoDocumento` enum('CC', 'TI', 'PA', 'CE', 'PE') NOT NULL DEFAULT 'CC', -- Tipo de documento de identidad:
                                                                             -- CC = Cédula de Ciudadanía
                                                                             -- TI = Tarjeta de Identidad
                                                                             -- PA = Pasaporte
                                                                             -- CE = Cédula de Extranjería
                                                                             -- PE = Permiso de Permanencia
    `FechaRegistro` timestamp NULL DEFAULT current_timestamp() -- Fecha y hora en que se registró el usuario
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Índices para las tablas
-- Índices de la tabla `reservas`
ALTER TABLE `reservas` 
ADD PRIMARY KEY (`IdReserva`), -- Índice primario basado en el ID único de la reserva
ADD KEY `fk_usuario_reserv` (`IdUsuario`), -- Índice de clave foránea para `IdUsuario`
ADD KEY `fk_usuario_rese` (`IdSalon`); -- Índice de clave foránea para `IdSalon`

-- Índices de la tabla `salones`
ALTER TABLE `salones` 
ADD PRIMARY KEY (`IdSalon`), -- Índice primario basado en el ID único del salón
ADD UNIQUE KEY `IdSalon` (`IdSalon`); -- Índice único para asegurar valores únicos de `IdSalon`

-- Índices de la tabla `usuarios`
ALTER TABLE `usuarios` 
ADD PRIMARY KEY (`IdUsuario`), -- Índice primario basado en el ID único del usuario
ADD UNIQUE KEY `IdUsuario` (`IdUsuario`); -- Índice único para asegurar valores únicos de `IdUsuario`

-- --------------------------------------------------------
-- Configuración de AUTO_INCREMENT
-- AUTO_INCREMENT de la tabla `reservas`
ALTER TABLE `reservas` 
MODIFY `IdReserva` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT; -- Incremento automático para ID de reserva

-- AUTO_INCREMENT de la tabla `salones`
ALTER TABLE `salones` 
MODIFY `IdSalon` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT; -- Incremento automático para ID de salón

-- AUTO_INCREMENT de la tabla `usuarios`
ALTER TABLE `usuarios` 
MODIFY `IdUsuario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT; -- Incremento automático para ID de usuario

-- --------------------------------------------------------
-- Restricciones y relaciones entre tablas
-- Restricciones de la tabla `reservas`
ALTER TABLE `reservas` 
ADD CONSTRAINT `fk_usuario_rese` FOREIGN KEY (`IdSalon`) REFERENCES `salones` (`IdSalon`), -- Llave foránea que vincula `IdSalon` con la tabla `salones`
ADD CONSTRAINT `fk_usuario_reserv` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`); -- Llave foránea que vincula `IdUsuario` con la tabla `usuarios`

COMMIT;

-- Restauración de configuraciones anteriores de conjunto de caracteres
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
