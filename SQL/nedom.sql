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
  `usuario_eliminacio` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
