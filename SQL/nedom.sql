SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nedom`
--
CREATE DATABASE IF NOT EXISTS `nedom` DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci;
USE `nedom`;

-- --------------------------------------------------------

--
-- Estructura de la tabla `avisos`
--

DROP TABLE IF EXISTS `avisos`;
CREATE TABLE `avisos` (
  `id` int NOT NULL,
  `fecha_aviso` date NOT NULL,
  `tratamiento_id` int NOT NULL,
  `prescripcion_id` int DEFAULT NULL,
  `mensaje` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `procesado` tinyint(1) NOT NULL DEFAULT '0',
  `usuario_proceso` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `tipo_aviso` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `roles_proceso` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `cambios_prescripciones`
--

DROP TABLE IF EXISTS `cambios_prescripciones`;
CREATE TABLE `cambios_prescripciones` (
  `id_cambios` int NOT NULL,
  `id_tratamiento` int NOT NULL,
  `id_prescripcion` int NOT NULL,
  `id_prescripcion_inicial` int DEFAULT NULL,
  `indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `valida_hasta` date DEFAULT NULL,
  `fecha_hasta_toma` date DEFAULT NULL,
  `consejos_admin` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `medi_desc` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip_prescripcion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_fk` int DEFAULT NULL,
  `via_codigo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frecuencia_fk` int DEFAULT NULL,
  `frec_codigo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frec_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frec_tipo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dosis` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuaris_prescripcion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `cambios_tratamientos`
--

DROP TABLE IF EXISTS `cambios_tratamientos`;
CREATE TABLE `cambios_tratamientos` (
  `id_cambios` int NOT NULL,
  `id` int NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `suspendido` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `confirmado_hasta` datetime DEFAULT NULL,
  `pendiente_confirmar` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `valido_hasta` datetime DEFAULT NULL,
  `etc_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `nhc` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip` varchar(14) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nif` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nombre_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `apellidos_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_exitus` date DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `poblacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `provincia` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `antecedentes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_login` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_grupo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_rol` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_num_colegiado` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_codigo_medico` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_activo` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio_codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `obs_tratamiento` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `equipos_administracion`
--

DROP TABLE IF EXISTS `equipos_administracion`;
CREATE TABLE `equipos_administracion` (
  `id` int NOT NULL,
  `lab` varchar(50) DEFAULT NULL,
  `bomba` varchar(50) DEFAULT NULL,
  `equipo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de la tabla `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE `estados` (
  `id` int NOT NULL,
  `descripcion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `clase_bootstrap` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `import_prescripciones`
--

DROP TABLE IF EXISTS `import_prescripciones`;
CREATE TABLE `import_prescripciones` (
  `id_tratamiento` int NOT NULL,
  `id_prescripcion` int NOT NULL,
  `id_prescripcion_inicial` int DEFAULT NULL,
  `indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `valida_hasta` date DEFAULT NULL,
  `fecha_hasta_toma` date DEFAULT NULL,
  `consejos_admin` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `medi_desc` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip_prescripcion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_fk` int DEFAULT NULL,
  `via_codigo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frecuencia_fk` int DEFAULT NULL,
  `frec_codigo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `freq_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `freq_tipo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dosis` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_prescripcion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '',
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `import_tratamientos`
--

DROP TABLE IF EXISTS `import_tratamientos`;
CREATE TABLE `import_tratamientos` (
  `id` int NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `suspendido` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `confirmado_hasta` datetime DEFAULT NULL,
  `pendiente_confirmar` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `valido_hasta` datetime DEFAULT NULL,
  `etc_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `nhc` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip` varchar(14) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nif` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nombre_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `apellidos_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_exitus` date DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `poblacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `provincia` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `antecedentes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_login` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_grupo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_rol` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_num_colegiado` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_codigo_medico` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_activo` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio_codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `obs_tratamiento` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `indicaciones`
--

DROP TABLE IF EXISTS `indicaciones`;
CREATE TABLE `indicaciones` (
  `id` int NOT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `grupo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `subgrupo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `via` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `pacientes`
--

DROP TABLE IF EXISTS `pacientes`;
CREATE TABLE `pacientes` (
  `cip_paciente` varchar(29) NOT NULL,
  `nhc` varchar(9) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `poblacion` varchar(50) DEFAULT NULL,
  `codigo_postal` varchar(5) DEFAULT NULL,
  `usuario_creacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `usuario_modificacion` varchar(50) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `usuario_eliminacion` varchar(50) DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de la tabla `prescripciones`
--

DROP TABLE IF EXISTS `prescripciones`;
CREATE TABLE `prescripciones` (
  `id_prescripcion` int NOT NULL,
  `id_prescripcion_inicial` int DEFAULT NULL,
  `id_tratamiento` int NOT NULL,
  `indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_indicacion` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `valida_hasta` date DEFAULT NULL,
  `fecha_hasta_toma` date DEFAULT NULL,
  `consejos_admin` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `medi_desc` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip_prescripcion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_fk` int DEFAULT NULL,
  `via_codigo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `via_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frecuencia_fk` int DEFAULT NULL,
  `frec_codi` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frec_descripcion` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `frec_tipo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dosis` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_prescripcion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `seguimientos`
--

DROP TABLE IF EXISTS `seguimientos`;
CREATE TABLE `seguimientos` (
  `id` int NOT NULL,
  `tratamiento_id` int NOT NULL,
  `tipo_seguimiento_id` int NOT NULL,
  `observaciones` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `fecha_seguimiento` date NOT NULL,
  `equipo_administracion_id` int DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `usuario_creacion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `usuario_modificacion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL,
  `usuario_eliminacion` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
CREATE TABLE `solicitudes` (
  `id` int NOT NULL,
  `tratamiento_id` int NOT NULL,
  `estado_id` int NOT NULL,
  `fecha_cambio_confirmado` date DEFAULT NULL,
  `usuario_creacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `usuario_modificacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL,
  `usuario_eliminacion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `tipos_seguimiento`
--

DROP TABLE IF EXISTS `tipos_seguimiento`;
CREATE TABLE `tipos_seguimiento` (
  `id` int NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `rol` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `estados_origen` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado_destino_id` int DEFAULT NULL,
  `roles_destino_avisos` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `mensaje_aviso` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `titulo_aviso` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `editable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de la tabla `tratamientos`
--

DROP TABLE IF EXISTS `tratamientos`;
CREATE TABLE `tratamientos` (
  `id` int NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `suspendido` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `confirmado_hasta` datetime DEFAULT NULL,
  `pendiente_confirmar` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `valido_hasta` datetime DEFAULT NULL,
  `etc_id` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `fecha_proceso` datetime DEFAULT NULL,
  `nhc` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `cip` varchar(14) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nif` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nombre_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `apellidos_paciente` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_exitus` date DEFAULT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `poblacion` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `codigo_postal` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `provincia` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci DEFAULT NULL,
  `nacionalidad` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `antecedentes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `usuario` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_login` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_grupo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_rol` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_num_colegiado` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_codigo_medico` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `usuario_activo` enum('S','N','') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `servicio_codigo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `obs_tratamiento` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `estado_registro` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `control` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de soporte para vistas `usuarios_silicon`
--
DROP VIEW IF EXISTS `usuarios_silicon`;
CREATE TABLE `usuarios_silicon` (
`id` varchar(30)
,`usuario` varchar(50)
,`usuario_login` varchar(50)
,`usuario_servicio` varchar(200)
,`usuario_num_colegiado` varchar(20)
,`usuario_activo` enum('S','N','')
);

-- --------------------------------------------------------

--
-- Estructura para vista `usuarios_silicon`
--
DROP TABLE IF EXISTS `usuarios_silicon`;

DROP VIEW IF EXISTS `usuarios_silicon`;
CREATE VIEW `usuarios_silicon`  AS SELECT DISTINCT `nedom`.`tratamientos`.`usuario_codigo_medico` AS `id`, `nedom`.`tratamientos`.`usuario` AS `usuario`, `nedom`.`tratamientos`.`usuario_login` AS `usuario_login`, `nedom`.`tratamientos`.`usuario_servicio` AS `usuario_servicio`, `nedom`.`tratamientos`.`usuario_num_colegiado` AS `usuario_num_colegiado`, `nedom`.`tratamientos`.`usuario_activo` AS `usuario_activo` FROM `nedom`.`tratamientos` ;

--
-- Índices
--

--
-- Índices para la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trat_aviso` (`tratamiento_id`);

--
-- Índices para la tabla `cambios_prescripciones`
--
ALTER TABLE `cambios_prescripciones`
  ADD PRIMARY KEY (`id_cambios`),
  ADD KEY `id_prescripcio` (`id_prescripcion`);

--
-- Índices para la tabla `cambios_tratamientos`
--
ALTER TABLE `cambios_tratamientos`
  ADD PRIMARY KEY (`id_cambios`),
  ADD KEY `id` (`id`);

--
-- Índices para la tabla `equipos_administracion`
--
ALTER TABLE `equipos_administracion`
  ADD PRIMARY KEY (`id`);

--
-- Índices para la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Índices para la tabla `import_prescripciones`
--
ALTER TABLE `import_prescripciones`
  ADD PRIMARY KEY (`id_prescripcion`);

--
-- Índices para la tabla `import_tratamientos`
--
ALTER TABLE `import_tratamientos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para la tabla `indicaciones`
--
ALTER TABLE `indicaciones`
  ADD PRIMARY KEY (`id`);

--
-- Índices para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`cip_paciente`),
  ADD UNIQUE KEY `cip` (`cip_paciente`);

--
-- Índices para la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD PRIMARY KEY (`id_prescripcion`),
  ADD KEY `id_tratamiento_fk` (`id_tratamiento`);

--
-- Índices para la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tratamiento_seg_fk` (`tratamiento_id`),
  ADD KEY `id_tipo_seg_fk` (`tipo_seguimiento_id`),
  ADD KEY `id_eq_fk` (`equipo_administracion_id`);

--
-- Índices para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trat_sol_fk` (`tratamiento_id`),
  ADD KEY `id_estado_sol_fk` (`estado_id`);

--
-- Índices para la tabla `tipos_seguimiento`
--
ALTER TABLE `tipos_seguimiento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para las tablas volcadas
--

--
-- AUTO_INCREMENT para la tabla `avisos`
--
ALTER TABLE `avisos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `cambios_prescripciones`
--
ALTER TABLE `cambios_prescripciones`
  MODIFY `id_cambios` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `cambios_tratamientos`
--
ALTER TABLE `cambios_tratamientos`
  MODIFY `id_cambios` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `equipos_administracion`
--
ALTER TABLE `equipos_administracion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `indicaciones`
--
ALTER TABLE `indicaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT para la tabla `tipos_seguimiento`
--
ALTER TABLE `tipos_seguimiento`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para las tablas
--

--
-- Restricciones para la tabla `avisos`
--
ALTER TABLE `avisos`
  ADD CONSTRAINT `id_trat_aviso` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restricciones para la tabla `prescripciones`
--
ALTER TABLE `prescripciones`
  ADD CONSTRAINT `id_tratamiento_fk` FOREIGN KEY (`id_tratamiento`) REFERENCES `tratamientos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restricciones para la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD CONSTRAINT `id_eq_fk` FOREIGN KEY (`equipo_administracion_id`) REFERENCES `equipos_administracion` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_tipo_seg_fk` FOREIGN KEY (`tipo_seguimiento_id`) REFERENCES `tipos_seguimiento` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_tratamiento_seg_fk` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Restricciones para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `id_estado_sol_fk` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `id_trat_sol_fk` FOREIGN KEY (`tratamiento_id`) REFERENCES `tratamientos` (`id`);
COMMIT;

--
-- Alimenta las tablas maestra estados, indicaciones y tipos_seguimiento
--
INSERT INTO `estados` (`id`, `descripcion`, `clase_bootstrap`) VALUES
(1, 'Pendiente', 'secondary'),
(2, 'Validada', 'primary'),
(3, 'Aprobada', 'success'),
(4, 'Denegada', 'danger'),
(5, 'Caducada', 'warning'),
(6, 'Finalizada', 'info'),
(7, 'Eliminada', 'danger'),
(8, 'Rechazada', 'danger');
COMMIT;

INSERT INTO `indicaciones` (`id`, `codigo`, `descripcion`, `grupo`, `subgrupo`, `via`) VALUES
(1, 'NED-A.1', 'Tumores de cabeza y cuello', 'Alteraciones mecánicas de la deglución o del tránsito, que necesitan sonda', NULL, 'sonda'),
(2, 'NED-A.2', 'Tumores del aparato digestivo (esófago, estómago)', 'Alteraciones mecánicas de la deglución o del tránsito, que necesitan sonda', NULL, 'sonda'),
(3, 'NED-A.3', 'Cirugía ORL y maxilofacial', 'Alteraciones mecánicas de la deglución o del tránsito, que necesitan sonda', NULL, 'sonda'),
(4, 'NED-A.4', 'Estenosis esofágica no tumoral', 'Alteraciones mecánicas de la deglución o del tránsito, que necesitan sonda', NULL, 'sonda'),
(5, 'NED-B.1.01', 'Esclerosis múltiple', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(6, 'NED-B.1.02', 'Esclerosis lateral amiotrófica', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(7, 'NED-B.1.03', 'Síndromes miasteniformes', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(8, 'NED-B.1.04', 'Síndrome de Guillain-Barré', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(9, 'NED-B.1.05', 'Secuelas de enfermedades infecciosas o traumáticas del sistema nervioso central', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(10, 'NED-B.1.06', 'Retraso mental grave', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(11, 'NED-B.1.07', 'Procesos degenerativos severos del sistema nervioso central (Enfermedades neurológicas con afagia o disfagia severa)', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', 'Enfermedades neurológicas que cursan con disfagia grave', 'sonda'),
(12, 'NED-B.2', 'Accidentes cerebrovasculares', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', '', 'sonda'),
(13, 'NED-B.3', 'Tumores cerebrales', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', '', 'sonda'),
(14, 'NED-B.4', 'Parálisis cerebral', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', '', 'sonda'),
(15, 'NED-B.5', 'Coma neurológico', 'Trastornos neuromotores que impiden la deglución o el tránsito y que necesitan sonda', '', 'sonda'),
(16, 'NED-B.6', 'Tumores cerebrales', 'Trastornos severos de la motilidad intestinal: pseudoobstrucción intestinal, gastroparesia diabética', '', 'sonda'),
(17, 'NED-C.1.01', 'Síndrome de intestino corto severo', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(18, 'NED-C.1.02', 'Diarrea intratable de origen autoinmune', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(19, 'NED-C.1.03', 'Linfoma', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(20, 'NED-C.1.04', 'Esteatorrea postgastrectomía', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(21, 'NED-C.1.05', 'Carcinoma de páncreas', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(22, 'NED-C.1.06', 'Resección amplia pancreática', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(23, 'NED-C.1.07', 'Insuficiencia vascular mesentérica', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(24, 'NED-C.1.08', 'Amiloidosis', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(25, 'NED-C.1.09', 'Esclerodermia', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(26, 'NED-C.1.10', 'Enteritis eosinofílica', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Síndromes de malabsorción severa', 'via oral'),
(27, 'NED-C.2.01', 'Epilepsia refractaria', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Enfermedades neurológicas subsidiarias de dietas cetogénicas', 'via oral'),
(28, 'NED-C.2.02', 'Deficiencia del transportador tipo I de glucosa', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Enfermedades neurológicas subsidiarias de dietas cetogénicas', 'via oral'),
(29, 'NED-C.2.03', 'Deficiencia del complejo piruvato-deshidrogenasa', 'Pacientes con requerimientos especiales de energía y/o nutrientes', 'Enfermedades neurológicas subsidiarias de dietas cetogénicas', 'via oral'),
(30, 'NED-C.3', 'Alergia o intolerancia diagnosticada a las proteínas de la leche de vaca en lactantes', 'Pacientes con requerimientos especiales de energía y/o nutrientes', '', 'via oral'),
(31, 'NED-C.4', 'Pacientes desnutridos que deben ser sometidos a cirugía mayor programada o trasplantes', 'Pacientes con requerimientos especiales de energía y/o nutrientes', '', 'via oral'),
(32, 'NED-C.5', 'Pacientes con encefalopatía hepática crónica con intolerancia a las proteínas de la dieta', 'Pacientes con requerimientos especiales de energía y/o nutrientes', '', 'via oral'),
(33, 'NED-C.6', 'Pacientes con adrenoleucodistrofia ligada al cromosoma X, neurológicamente asintomáticos', 'Pacientes con requerimientos especiales de energía y/o nutrientes', '', 'via oral'),
(34, 'NED-D.1', 'Enfermedad inflamatoria intestinal: colitis ulcerosa y enfermedad de Crohn', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral'),
(35, 'NED-D.2', 'Caquexia cancerosa por enteritis crónica por tratamiento quimio y/o radioterápico', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral'),
(36, 'NED-D.3', 'Patología médica infecciosa que conlleva malabsorción severa: SIDA', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral'),
(37, 'NED-D.4', 'Fibrosis quística', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral'),
(38, 'NED-E.1', 'Otros: Disfagia', 'Otros', '', 'via oral'),
(39, 'NED-F.1', 'Otros: Pediatría', 'Otros', '', 'via oral'),
(40, 'NED-D.5', 'Fístulas enterocutáneas de bajo débito', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral'),
(41, 'NED-D.6', 'Insuficiencia renal infantil que compromete el crecimiento del paciente', 'Situaciones clínicas que cursan con desnutrición grave', '', 'via oral');
COMMIT;

INSERT INTO `tipos_seguimiento` (`id`, `descripcion`, `rol`, `estados_origen`, `estado_destino_id`, `roles_destino_avisos`, `mensaje_aviso`, `titulo_aviso`, `editable`) VALUES
(1, 'Validación', 'admin,farmacia', '1', 2, 'admin,catsalut', '<h3>El tratamiento {id_tratamiento} ha sido validado y ha generado una nueva solicitud</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para continuar con el proceso</p>', 'NED - Solicitud validada', 0),
(2, 'Vuelta a Pendiente', 'admin,farmacia', '2,3,4,5,6,7,8', 1, NULL, NULL, NULL, 0),
(3, 'Aprobar Solicitud', 'admin,catsalut', '2', 3, 'farmacia', '<h3>La solicitud con ID:{id_tratamiento} ha sido aprobada</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para continuar con el proceso</p>', 'NED - Solicitud Aprobada', 0),
(4, 'Datos de seguimiento de Farmacia', 'admin,farmacia', NULL, NULL, NULL, NULL, NULL, 1),
(5, 'Datos de seguimiento de Prescriptor', 'admin,prescriptor', NULL, NULL, NULL, NULL, NULL, 1),
(6, 'Pendiente de Renovar', '', '3', 5, 'admin,prescriptor', '<h3>La solicitud con ID:{id_tratamiento} ha caducado, y está pendiente de ser revisada por un/una presprictor/a (cambio de fecha \"confirmado hasta\")</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para continuar con el proceso</p>', 'NED - Solicitud Caducada', 0),
(7, 'Finalizar Solicitud', 'admin,prescriptor,catsalut', '1,2,3,5', 6, 'admin,farmacia,catsalut', '<h3>La solicitud con ID:{id_tratamiento} ha sido finalizada por un/a prescriptor/a </h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para ver los detalles</p>', 'NED - Solicitud Finalizada', 0),
(8, 'Denegar Solicitud', 'admin,catsalut', '2,3', 4, 'farmacia', '<h3>La solicitud con ID:{id_tratamiento} ha sido denegada</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para ver los detalles</p>', 'NED - Solicitud Denegada', 0),
(9, 'Eliminar solicitud', '', NULL, 7, NULL, NULL, NULL, 0),
(10, 'Requerir información a Prescriptor', 'admin,catsalut', '2,3,5', 2, 'admin,prescriptor', '<h3>Hay un requerimiento de información sobre la solicitud {id_tratamiento}</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para completar la información requerida</p>', 'NED - Requerimiento de información', 1),
(11, 'Requerir información a Farmacia', 'admin,catsalut', '2,3,5', 2, 'admin,farmacia', '<h3>Hay un requerimiento de información sobre la solicitud {id_tratamiento}</h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para completar la información requerida</p>', 'NED - Requerimiento de información', 1),
(12, 'Detalles de administración', 'admin,farmacia', '1,2,3', NULL, NULL, NULL, NULL, 1),
(13, 'Rechazar', 'admin,farmacia', '1', 8, 'admin,prescriptor', '<h3>La solicitud con ID:{id_tratamiento} ha sido Rechazada por el servicio de Farmacia </h3><p>Por favor, accede a la <a href=\"{baseURL}/tratamientos/{id_tratamiento}\">aplicación NED</a> para ver los detalles</p>\n', 'NED - Solicitud Rechazada', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
