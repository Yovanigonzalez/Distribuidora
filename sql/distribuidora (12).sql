-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-03-2024 a las 04:33:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `distribuidora`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

CREATE TABLE `cajas` (
  `id` int(11) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `total_cajas` int(11) NOT NULL DEFAULT 0,
  `total_tapas` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cajas`
--

INSERT INTO `cajas` (`id`, `cliente`, `direccion`, `total_cajas`, `total_tapas`) VALUES
(17, 'CLIENTE VARIOS', '', 32, 33),
(19, 'FABRICIA ROMERO', 'PASEO', 110, 10),
(20, 'REYNA', 'PEÑAFIEL', 42, 33),
(23, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', 42, 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `direccion`) VALUES
(1, 'FABRICIA ROMERO', 'PASEO'),
(3, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA'),
(5, 'PABLO', 'CUAYUCATEP'),
(6, 'FABRICIA ROMERO', 'CASICRESPO'),
(7, 'VICTOR RODRIGUEZ', 'CERRO MILITARES'),
(8, 'CHAGUITA', 'PEÑAFIEL'),
(9, 'REYNA', 'PEÑAFIEL'),
(10, 'LAURA CARRERA', 'PEÑAFIEL'),
(11, 'POLLOS CUBANOS', 'PEÑAFIEL'),
(12, 'XOCHIL', 'PEÑAFIEL'),
(13, 'DAVID CRUZ', 'PEÑAFIEL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_deudas`
--

CREATE TABLE `cliente_deudas` (
  `id` int(11) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `total_deuda` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente_deudas`
--

INSERT INTO `cliente_deudas` (`id`, `cliente`, `direccion`, `total_deuda`) VALUES
(3, 'CLIENTE VARIOS', '', 882.00),
(4, 'FABRICIA ROMERO', 'PASEO', 252.00),
(5, 'REYNA', 'PEÑAFIEL', 483.00),
(6, 'FABRICIA ROMERO', 'PASEO', 252.00),
(7, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', 882.00),
(8, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', 441.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_resta`
--

CREATE TABLE `detalles_resta` (
  `id` int(11) NOT NULL,
  `cliente_nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `cajas_restantes` int(11) NOT NULL,
  `tapas_restantes` int(11) NOT NULL,
  `prev_total_cajas` int(11) NOT NULL,
  `prev_total_tapas` int(11) NOT NULL,
  `post_total_cajas` int(11) NOT NULL,
  `post_total_tapas` int(11) NOT NULL,
  `fecha_resta` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_resta`
--

INSERT INTO `detalles_resta` (`id`, `cliente_nombre`, `direccion`, `cliente_id`, `cajas_restantes`, `tapas_restantes`, `prev_total_cajas`, `prev_total_tapas`, `post_total_cajas`, `post_total_tapas`, `fecha_resta`) VALUES
(1, 'ANTONIO ALVAREZ', '', 23, 12, 21, 54, 54, 42, 33, '2024-03-21 03:16:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deudores`
--

CREATE TABLE `deudores` (
  `id` int(11) NOT NULL,
  `folio_venta` int(11) DEFAULT NULL,
  `kilos` decimal(10,2) DEFAULT NULL,
  `piezas` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cajas` int(11) DEFAULT NULL,
  `tapas` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL,
  `estatus` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `deudores`
--

INSERT INTO `deudores` (`id`, `folio_venta`, `kilos`, `piezas`, `categoria`, `precio`, `cajas`, `tapas`, `metodo_pago`, `subtotal`, `cliente`, `direccion`, `fecha_hora`, `estatus`) VALUES
(23, 1, 21.00, 21, 'ROSTICERO R-50', 21.00, 21, 12, 'credito', 441.00, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', '2024-03-20 08:15:49', 'estatus');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `id` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `producto` varchar(255) NOT NULL,
  `stock` decimal(10,2) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`id`, `categoria`, `producto`, `stock`, `fecha_registro`) VALUES
(1, 'ROSTICERO LH', 'ROSTICERO LH', 79.00, '2024-03-06 16:58:40'),
(2, 'ROSTICERO R-10', 'ROSTICERO R-10', 67.00, '2024-03-06 16:59:40'),
(3, 'ROSTICERO R-20', 'ROSTICERO R-20', -131.00, '2024-03-06 16:59:48'),
(4, 'ROSTICERO R-30', 'ROSTICERO R-30', 42.50, '2024-03-06 16:59:55'),
(5, 'ROSTICERO R-40', 'ROSTICERO R-40', -61.00, '2024-03-06 17:00:01'),
(6, 'ROSTICERO R-50', 'ROSTICERO R-50', 37.00, '2024-03-06 17:00:08'),
(7, 'ROSTICERO R-60', 'ROSTICERO R-60', -209.00, '2024-03-06 17:00:16'),
(8, 'ROSTICERO R-70', 'ROSTICERO R-70', 0.77, '2024-03-06 17:00:22'),
(9, 'ROSTICERO R-80', 'ROSTICERO R-80', -7.00, '2024-03-06 17:00:29'),
(10, 'ROSTICERO NATURAL 1.0 - 1.1', 'ROSTICERO NATURAL 1.0 - 1.1', 58.00, '2024-03-06 17:00:38'),
(15, 'ROSTICERO NATURAL 1.1 - 1.2', 'ROSTICERO NATURAL 1.1 - 1.2', -425.00, '2024-03-06 17:04:55'),
(16, 'ROSTICERO NATURAL 1.2 - 1.3', 'ROSTICERO NATURAL 1.2 - 1.3', -293.00, '2024-03-06 17:05:10'),
(17, 'ROSTICERO NATURAL 1.3 - 1.4', 'ROSTICERO NATURAL 1.3 - 1.4', -26.00, '2024-03-06 17:05:18'),
(18, 'ROSTICERO NATURAL 1.4 - 1.5', 'ROSTICERO NATURAL 1.4 - 1.5', 8.00, '2024-03-06 17:05:28'),
(19, 'ROSTICERO NATURAL 1.5 - 1.6', 'ROSTICERO NATURAL 1.5 - 1.6', 59.00, '2024-03-06 17:06:08'),
(20, 'ROSTICERO NATURAL 1.6 - 1.7', 'ROSTICERO NATURAL 1.6 - 1.7', -1.00, '2024-03-06 17:06:15'),
(21, 'ROSTICERO NATURAL 1.7 - 1.8', 'ROSTICERO NATURAL 1.7 - 1.8', 48.00, '2024-03-06 17:06:22'),
(22, 'ALA NATURAL', 'ALA NATURAL', 22.01, '2024-03-06 17:06:29'),
(23, 'ALA MARINADA', 'ALA MARINADA', 172.00, '2024-03-06 17:06:40'),
(24, 'CABEZA NATURAL', 'CABEZA NATURAL', 170.00, '2024-03-06 17:06:51'),
(25, 'CABEZA ESCALDADA', 'CABEZA ESCALDADA', 88.00, '2024-03-06 17:07:18'),
(26, 'PIERNA / MUSLO', 'PIERNA / MUSLO', 200.00, '2024-03-06 17:08:07'),
(27, 'MOLLEJA', 'MOLLEJA', 108.77, '2024-03-06 17:08:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`) VALUES
(1, 'ROSTICERO LH'),
(11, 'ROSTICERO NATURAL 1.0 - 1.1'),
(12, 'ROSTICERO NATURAL 1.1 - 1.2'),
(13, 'ROSTICERO NATURAL 1.2 - 1.3'),
(14, 'ROSTICERO NATURAL 1.3 - 1.4'),
(15, 'ROSTICERO NATURAL 1.4 - 1.5'),
(16, 'ROSTICERO NATURAL 1.5 - 1.6'),
(17, 'ROSTICERO NATURAL 1.6 - 1.7'),
(18, 'ROSTICERO NATURAL 1.7 - 1.8'),
(19, 'ROSTICERO NATURAL 1.8 - 1.9'),
(20, 'PATA ESCALDADA'),
(21, 'PATA NATURAL'),
(22, 'MOLLEJA'),
(23, 'CABEZA NATURAL'),
(24, 'CABEZA ESCALDADA'),
(25, 'PIERNA / MUSLO'),
(26, 'ALA NATURAL'),
(27, 'ALA MARINADA'),
(28, 'COSTILLA'),
(29, 'ROSTICERO ESCALDADO 1.0 - 1.1'),
(30, 'ROSTICERO ESCALDADO 1.1 - 1.2'),
(31, 'ROSTICERO ESCALDADO 1.2 - 1.3'),
(32, 'ROSTICERO ESCALDADO 1.3 - 1.4'),
(33, 'ROSTICERO ESCALDADO 1.4 - 1.5'),
(34, 'ROSTICERO ESCALDADO 1.5 - 1.6'),
(35, 'ROSTICERO ESCALDADO 1.6 - 1.7'),
(36, 'ROSTICERO ESCALDADO 1.7 - 1.8'),
(37, 'ROSTICERO ESCALDADO 1.8 - 1.9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remiciones`
--

CREATE TABLE `remiciones` (
  `id` int(11) NOT NULL,
  `folio_venta` int(11) NOT NULL,
  `kilos` decimal(10,2) NOT NULL,
  `piezas` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cajas` int(11) NOT NULL,
  `tapas` int(11) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `remiciones`
--

INSERT INTO `remiciones` (`id`, `folio_venta`, `kilos`, `piezas`, `categoria`, `precio`, `cajas`, `tapas`, `metodo_pago`, `subtotal`, `cliente`, `direccion`, `fecha_hora`, `estatus`) VALUES
(6, 1, 21.00, 21, 'ROSTICERO R-50', 21.00, 21, 12, 'credito', 441.00, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', '2024-03-20 08:15:49', 'estatus');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
(1, 'Francisco', '$2y$10$e4D3CiDhUtEz0/mPTjEGy.4ONd/UXzePMfj1bVdH14QvNhLlEIRiG'),
(2, 'Yovani', '$2y$10$4AwsvFDneW0iNNhqWnWUe.dFPVrRoYShAwcyIZvbaaVNMXAiKQlum');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `folio` int(11) DEFAULT NULL,
  `kilos` decimal(10,2) DEFAULT NULL,
  `piezas` int(11) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cajas` int(11) DEFAULT NULL,
  `tapas` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `cliente` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `folio`, `kilos`, `piezas`, `categoria`, `precio`, `cajas`, `tapas`, `metodo_pago`, `subtotal`, `cliente`, `direccion`, `fecha_hora`) VALUES
(21, 1, 21.00, 21, 'ROSTICERO R-50', 21.00, 21, 12, 'credito', 441.00, 'ANTONIO ALVAREZ', 'MERCADO LA PURISIMA', '2024-03-20 08:15:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cliente_direccion_unique` (`cliente`,`direccion`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cliente_deudas`
--
ALTER TABLE `cliente_deudas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalles_resta`
--
ALTER TABLE `detalles_resta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deudores`
--
ALTER TABLE `deudores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `remiciones`
--
ALTER TABLE `remiciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `cliente_deudas`
--
ALTER TABLE `cliente_deudas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `detalles_resta`
--
ALTER TABLE `detalles_resta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `deudores`
--
ALTER TABLE `deudores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `remiciones`
--
ALTER TABLE `remiciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
