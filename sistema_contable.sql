-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2025 a las 19:05:45
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_contable`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asientos_contables`
--

CREATE TABLE `asientos_contables` (
  `id_asiento` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_periodo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `asientos_contables`
--

INSERT INTO `asientos_contables` (`id_asiento`, `fecha`, `descripcion`, `id_periodo`) VALUES
(5, '2025-05-20', 'Inversión inicial', 2),
(6, '2024-01-01', 'Compra de Laptop para recepcion', 1),
(11, '2025-05-20', 'Inversión inicial', 2),
(18, '2025-05-20', 'pago', 2),
(21, '2025-05-22', 'vnetas', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `id_cuenta` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('Activo','Pasivo','Patrimonio','Ingreso','Gasto') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`id_cuenta`, `codigo`, `nombre`, `tipo`) VALUES
(1, '1.1.1', 'Caja', 'Activo'),
(2, '1.2.1', 'Cuentas por Cobrar', 'Activo'),
(3, '2.1.1', 'Proveedores', 'Pasivo'),
(4, '3.1.1', 'Capital Social', 'Patrimonio'),
(5, '4.1.1', 'Ventas', 'Ingreso'),
(6, '5.1.1', 'Gastos de Alquiler', 'Gasto'),
(7, '6.1.1', 'Bancos', 'Activo'),
(8, '1.1.1', 'Caja', 'Activo'),
(9, '2.1.1', 'Proveedores', 'Pasivo'),
(10, '3.1.1', 'Capital Social', 'Patrimonio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id_empresa` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rif` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `nombre`, `rif`) VALUES
(1, 'Mi Empresa S.A.', '123456789-J'),
(2, 'NETRONICA.NET', '3516592-8'),
(3, 'Real Madrid', '2499000-2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_financieros`
--

CREATE TABLE `estados_financieros` (
  `id_estado` int(11) NOT NULL,
  `tipo` enum('Balance General','Estado de Resultados') NOT NULL,
  `id_periodo` int(11) DEFAULT NULL,
  `datos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estados_financieros`
--

INSERT INTO `estados_financieros` (`id_estado`, `tipo`, `id_periodo`, `datos`) VALUES
(21, 'Balance General', 1, '{\r\n    \"activos\": {\r\n        \"caja\": 15000.00,\r\n        \"cuentas_por_cobrar\": 25000.00,\r\n        \"inventario\": 40000.00,\r\n        \"activos_fijos\": 120000.00\r\n    },\r\n    \"pasivos\": {\r\n        \"proveedores\": 30000.00,\r\n        \"prestamos\": 50000.00\r\n    },\r\n    \"patrimonio\": {\r\n        \"capital_social\": 150000.00,\r\n        \"utilidades_retenidas\": 20000.00\r\n    }\r\n}'),
(22, 'Estado de Resultados', 1, '{\r\n    \"ingresos\": {\r\n        \"ventas\": 120000.00,\r\n        \"servicios\": 15000.00\r\n    },\r\n    \"gastos\": {\r\n        \"costo_ventas\": 75000.00,\r\n        \"gastos_administrativos\": 20000.00,\r\n        \"gastos_ventas\": 15000.00\r\n    },\r\n    \"utilidad_neta\": 25000.00\r\n}'),
(23, 'Balance General', 2, '{\r\n    \"activos\": {\r\n        \"caja\": 18000.00,\r\n        \"cuentas_por_cobrar\": 28000.00,\r\n        \"inventario\": 42000.00,\r\n        \"activos_fijos\": 118000.00\r\n    },\r\n    \"pasivos\": {\r\n        \"proveedores\": 32000.00,\r\n        \"prestamos\": 48000.00\r\n    },\r\n    \"patrimonio\": {\r\n        \"capital_social\": 150000.00,\r\n        \"utilidades_retenidas\": 42000.00\r\n    }\r\n}'),
(24, 'Estado de Resultados', 2, '{\r\n    \"ingresos\": {\r\n        \"ventas\": 135000.00,\r\n        \"servicios\": 18000.00\r\n    },\r\n    \"gastos\": {\r\n        \"costo_ventas\": 82000.00,\r\n        \"gastos_administrativos\": 22000.00,\r\n        \"gastos_ventas\": 17000.00\r\n    },\r\n    \"utilidad_neta\": 32000.00\r\n}'),
(25, 'Balance General', 3, '{\r\n    \"activos\": {\r\n        \"caja\": 22000.00,\r\n        \"cuentas_por_cobrar\": 32000.00,\r\n        \"inventario\": 45000.00,\r\n        \"activos_fijos\": 115000.00\r\n    },\r\n    \"pasivos\": {\r\n        \"proveedores\": 35000.00,\r\n        \"prestamos\": 45000.00\r\n    },\r\n    \"patrimonio\": {\r\n        \"capital_social\": 150000.00,\r\n        \"utilidades_retenidas\": 59000.00\r\n    }\r\n}'),
(26, 'Estado de Resultados', 3, '{\r\n    \"ingresos\": {\r\n        \"ventas\": 145000.00,\r\n        \"servicios\": 20000.00\r\n    },\r\n    \"gastos\": {\r\n        \"costo_ventas\": 88000.00,\r\n        \"gastos_administrativos\": 24000.00,\r\n        \"gastos_ventas\": 19000.00\r\n    },\r\n    \"utilidad_neta\": 34000.00\r\n}'),
(27, 'Balance General', 4, '{\r\n    \"activos\": {\r\n        \"caja\": 25000.00,\r\n        \"cuentas_por_cobrar\": 35000.00,\r\n        \"inventario\": 48000.00,\r\n        \"activos_fijos\": 112000.00\r\n    },\r\n    \"pasivos\": {\r\n        \"proveedores\": 38000.00,\r\n        \"prestamos\": 42000.00\r\n    },\r\n    \"patrimonio\": {\r\n        \"capital_social\": 150000.00,\r\n        \"utilidades_retenidas\": 75000.00\r\n    }\r\n}'),
(28, 'Estado de Resultados', 4, '{\r\n    \"ingresos\": {\r\n        \"ventas\": 155000.00,\r\n        \"servicios\": 22000.00\r\n    },\r\n    \"gastos\": {\r\n        \"costo_ventas\": 92000.00,\r\n        \"gastos_administrativos\": 26000.00,\r\n        \"gastos_ventas\": 21000.00\r\n    },\r\n    \"utilidad_neta\": 38000.00\r\n}'),
(29, 'Balance General', 5, '{\r\n    \"activos\": {\r\n        \"caja\": 28000.00,\r\n        \"cuentas_por_cobrar\": 38000.00,\r\n        \"inventario\": 50000.00,\r\n        \"activos_fijos\": 110000.00\r\n    },\r\n    \"pasivos\": {\r\n        \"proveedores\": 40000.00,\r\n        \"prestamos\": 40000.00\r\n    },\r\n    \"patrimonio\": {\r\n        \"capital_social\": 150000.00,\r\n        \"utilidades_retenidas\": 86000.00\r\n    }\r\n}'),
(30, 'Estado de Resultados', 5, '{\r\n    \"ingresos\": {\r\n        \"ventas\": 165000.00,\r\n        \"servicios\": 25000.00\r\n    },\r\n    \"gastos\": {\r\n        \"costo_ventas\": 98000.00,\r\n        \"gastos_administrativos\": 28000.00,\r\n        \"gastos_ventas\": 23000.00\r\n    },\r\n    \"utilidad_neta\": 41000.00\r\n}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`id_periodo`, `nombre`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 'Enero 2024', '2024-01-01', '2024-01-31'),
(2, 'Mayo', '2025-05-01', '2025-05-31'),
(3, 'febrero', '2025-02-01', '2025-02-28'),
(4, 'marzo', '2025-03-01', '2025-03-31'),
(5, 'Abril', '2025-04-01', '2025-04-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id_transaccion` int(11) NOT NULL,
  `id_asiento` int(11) DEFAULT NULL,
  `id_cuenta` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `tipo` enum('Debe','Haber') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `transacciones`
--

INSERT INTO `transacciones` (`id_transaccion`, `id_asiento`, `id_cuenta`, `monto`, `tipo`) VALUES
(7, 5, 3, 10000.00, 'Haber'),
(8, 5, 1, 10000.00, 'Debe'),
(17, 6, 7, 12000.00, 'Debe'),
(18, 6, 7, 12000.00, 'Haber'),
(19, 11, 3, 10000.00, 'Haber'),
(20, 11, 1, 10000.00, 'Debe'),
(27, 18, 9, 100.00, 'Haber'),
(28, 18, 1, 100.00, 'Debe'),
(32, 21, 5, 10000.00, 'Debe'),
(33, 21, 7, 10000.00, 'Haber');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_empresa`, `nombre`, `apellido`, `email`, `username`, `password_hash`, `rol`, `fecha_registro`) VALUES
(2, 1, 'Bryan Alexander', 'PEREZ SOSA', 'informatica@amsa.gob.gt', 'bryan', '$2y$10$/n8tOPtcFBJtUb2HhXL16uYfPreqxagYsMJ1uaeJP9W3sGO5m4gdi', 'admin', '2025-05-20 17:14:30'),
(17, 1, 'Jose', 'Castellon', 'info@netronica.net', 'admin', '$2y$10$zfu2EB1jG2ykpfYhQ5TPrufx6AUU50Vk8n.5mWPBS6uy2DSXcu1.e', 'admin', '2025-05-21 14:56:14'),
(18, 2, 'Rudy', 'Quiñonez', 'estuardo@netronica.com.gt', 'estuardo', '$2y$10$5qGewi4ALAhJMZ1WQ9EA7uWrVG8geej3U/raDjYRc7hXxLyzDGvlS', 'admin', '2025-05-21 15:06:07'),
(19, 1, 'Jose Luis', 'Perales', 'ventas@netronica.net', 'jose', '$2y$10$L9qc9QlMDo2nvtyGVwWdju1iEB8b0.nr5Vev7.fY0Jgatd6vt43Ua', 'admin', '2025-05-21 16:52:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  ADD PRIMARY KEY (`id_asiento`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id_cuenta`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `estados_financieros`
--
ALTER TABLE `estados_financieros`
  ADD PRIMARY KEY (`id_estado`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id_transaccion`),
  ADD KEY `id_asiento` (`id_asiento`),
  ADD KEY `id_cuenta` (`id_cuenta`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_empresa` (`id_empresa`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  MODIFY `id_asiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `id_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estados_financieros`
--
ALTER TABLE `estados_financieros`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asientos_contables`
--
ALTER TABLE `asientos_contables`
  ADD CONSTRAINT `asientos_contables_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `estados_financieros`
--
ALTER TABLE `estados_financieros`
  ADD CONSTRAINT `estados_financieros_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`id_asiento`) REFERENCES `asientos_contables` (`id_asiento`),
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`id_cuenta`) REFERENCES `cuentas` (`id_cuenta`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id_empresa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
