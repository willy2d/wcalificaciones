-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1:33060
-- Tiempo de generación: 11-08-2016 a las 06:27:48
-- Versión del servidor: 5.5.32
-- Versión de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `wcalificaciones`
--
CREATE DATABASE IF NOT EXISTS `wcalificaciones` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `wcalificaciones`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_authassignment`
--

CREATE TABLE IF NOT EXISTS `cruge_authassignment` (
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  `itemname` varchar(64) NOT NULL,
  PRIMARY KEY (`userid`,`itemname`),
  KEY `fk_cruge_authassignment_cruge_authitem1` (`itemname`),
  KEY `fk_cruge_authassignment_user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_authitem`
--

CREATE TABLE IF NOT EXISTS `cruge_authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cruge_authitem`
--

INSERT INTO `cruge_authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('action_alumnos_admin', 0, '', NULL, 'N;'),
('action_alumnos_create', 0, '', NULL, 'N;'),
('action_alumnos_delete', 0, '', NULL, 'N;'),
('action_alumnos_index', 0, '', NULL, 'N;'),
('action_alumnos_update', 0, '', NULL, 'N;'),
('action_alumnos_view', 0, '', NULL, 'N;'),
('action_cursos_admin', 0, '', NULL, 'N;'),
('action_cursos_create', 0, '', NULL, 'N;'),
('action_cursos_delete', 0, '', NULL, 'N;'),
('action_cursos_index', 0, '', NULL, 'N;'),
('action_cursos_update', 0, '', NULL, 'N;'),
('action_cursos_view', 0, '', NULL, 'N;'),
('action_diplomados_admin', 0, '', NULL, 'N;'),
('action_diplomados_create', 0, '', NULL, 'N;'),
('action_diplomados_delete', 0, '', NULL, 'N;'),
('action_diplomados_index', 0, '', NULL, 'N;'),
('action_diplomados_update', 0, '', NULL, 'N;'),
('action_diplomados_view', 0, '', NULL, 'N;'),
('action_especialidades_admin', 0, '', NULL, 'N;'),
('action_especialidades_create', 0, '', NULL, 'N;'),
('action_especialidades_delete', 0, '', NULL, 'N;'),
('action_especialidades_index', 0, '', NULL, 'N;'),
('action_especialidades_update', 0, '', NULL, 'N;'),
('action_especialidades_view', 0, '', NULL, 'N;'),
('action_notas_admin', 0, '', NULL, 'N;'),
('action_notas_create', 0, '', NULL, 'N;'),
('action_notas_delete', 0, '', NULL, 'N;'),
('action_notas_index', 0, '', NULL, 'N;'),
('action_notas_pdf', 0, '', NULL, 'N;'),
('action_notas_update', 0, '', NULL, 'N;'),
('action_notas_view', 0, '', NULL, 'N;'),
('action_semestres_admin', 0, '', NULL, 'N;'),
('action_semestres_create', 0, '', NULL, 'N;'),
('action_semestres_delete', 0, '', NULL, 'N;'),
('action_semestres_index', 0, '', NULL, 'N;'),
('action_semestres_update', 0, '', NULL, 'N;'),
('action_semestres_view', 0, '', NULL, 'N;'),
('action_site_contact', 0, '', NULL, 'N;'),
('action_site_error', 0, '', NULL, 'N;'),
('action_site_exportar', 0, '', NULL, 'N;'),
('action_site_index', 0, '', NULL, 'N;'),
('action_site_login', 0, '', NULL, 'N;'),
('action_site_logout', 0, '', NULL, 'N;'),
('action_site_pdf', 0, '', NULL, 'N;'),
('action_site_view', 0, '', NULL, 'N;'),
('action_site_viewpdf', 0, '', NULL, 'N;'),
('action_users_admin', 0, '', NULL, 'N;'),
('action_users_create', 0, '', NULL, 'N;'),
('action_users_delete', 0, '', NULL, 'N;'),
('action_users_index', 0, '', NULL, 'N;'),
('action_users_update', 0, '', NULL, 'N;'),
('action_users_view', 0, '', NULL, 'N;'),
('controller_alumnos', 0, '', NULL, 'N;'),
('controller_cursos', 0, '', NULL, 'N;'),
('controller_diplomados', 0, '', NULL, 'N;'),
('controller_especialidades', 0, '', NULL, 'N;'),
('controller_notas', 0, '', NULL, 'N;'),
('controller_semestres', 0, '', NULL, 'N;'),
('controller_site', 0, '', NULL, 'N;'),
('controller_users', 0, '', NULL, 'N;'),
('estudiante', 2, 'estudiante', 'estudiante', 'N;'),
('guest', 2, 'guest', 'guest', 'N;'),
('profesor', 2, 'profesor', 'profesor', 'N;');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_authitemchild`
--

CREATE TABLE IF NOT EXISTS `cruge_authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_field`
--

CREATE TABLE IF NOT EXISTS `cruge_field` (
  `idfield` int(11) NOT NULL AUTO_INCREMENT,
  `fieldname` varchar(20) NOT NULL,
  `longname` varchar(50) DEFAULT NULL,
  `position` int(11) DEFAULT '0',
  `required` int(11) DEFAULT '0',
  `fieldtype` int(11) DEFAULT '0',
  `fieldsize` int(11) DEFAULT '20',
  `maxlength` int(11) DEFAULT '45',
  `showinreports` int(11) DEFAULT '0',
  `useregexp` varchar(512) DEFAULT NULL,
  `useregexpmsg` varchar(512) DEFAULT NULL,
  `predetvalue` mediumblob,
  PRIMARY KEY (`idfield`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_fieldvalue`
--

CREATE TABLE IF NOT EXISTS `cruge_fieldvalue` (
  `idfieldvalue` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `idfield` int(11) NOT NULL,
  `value` blob,
  PRIMARY KEY (`idfieldvalue`),
  KEY `fk_cruge_fieldvalue_cruge_user1` (`iduser`),
  KEY `fk_cruge_fieldvalue_cruge_field1` (`idfield`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_session`
--

CREATE TABLE IF NOT EXISTS `cruge_session` (
  `idsession` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) NOT NULL,
  `created` bigint(30) DEFAULT NULL,
  `expire` bigint(30) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `ipaddress` varchar(45) DEFAULT NULL,
  `usagecount` int(11) DEFAULT '0',
  `lastusage` bigint(30) DEFAULT NULL,
  `logoutdate` bigint(30) DEFAULT NULL,
  `ipaddressout` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idsession`),
  KEY `crugesession_iduser` (`iduser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_system`
--

CREATE TABLE IF NOT EXISTS `cruge_system` (
  `idsystem` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `largename` varchar(45) DEFAULT NULL,
  `sessionmaxdurationmins` int(11) DEFAULT '30',
  `sessionmaxsameipconnections` int(11) DEFAULT '10',
  `sessionreusesessions` int(11) DEFAULT '1' COMMENT '1yes 0no',
  `sessionmaxsessionsperday` int(11) DEFAULT '-1',
  `sessionmaxsessionsperuser` int(11) DEFAULT '-1',
  `systemnonewsessions` int(11) DEFAULT '0' COMMENT '1yes 0no',
  `systemdown` int(11) DEFAULT '0',
  `registerusingcaptcha` int(11) DEFAULT '0',
  `registerusingterms` int(11) DEFAULT '0',
  `terms` blob,
  `registerusingactivation` int(11) DEFAULT '1',
  `defaultroleforregistration` varchar(64) DEFAULT NULL,
  `registerusingtermslabel` varchar(100) DEFAULT NULL,
  `registrationonlogin` int(11) DEFAULT '1',
  PRIMARY KEY (`idsystem`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `cruge_system`
--

INSERT INTO `cruge_system` (`idsystem`, `name`, `largename`, `sessionmaxdurationmins`, `sessionmaxsameipconnections`, `sessionreusesessions`, `sessionmaxsessionsperday`, `sessionmaxsessionsperuser`, `systemnonewsessions`, `systemdown`, `registerusingcaptcha`, `registerusingterms`, `terms`, `registerusingactivation`, `defaultroleforregistration`, `registerusingtermslabel`, `registrationonlogin`) VALUES
(1, 'default', NULL, 10, 10, 1, -1, -1, 0, 0, 1, 1, 0x5465726d696e6f73207920636f6e646963696f6e65732064656c20492e452e532e452e502e204e756573747261205365c3b16f72612064652043686f7461, 1, 'guest', 'Terminos y condiciones', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cruge_user`
--

CREATE TABLE IF NOT EXISTS `cruge_user` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `regdate` bigint(30) DEFAULT NULL,
  `actdate` bigint(30) DEFAULT NULL,
  `logondate` bigint(30) DEFAULT NULL,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL COMMENT 'Hashed password',
  `authkey` varchar(100) DEFAULT NULL COMMENT 'llave de autentificacion',
  `state` int(11) DEFAULT '0',
  `totalsessioncounter` int(11) DEFAULT '0',
  `currentsessioncounter` int(11) DEFAULT '0',
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `cruge_user`
--

INSERT INTO `cruge_user` (`iduser`, `regdate`, `actdate`, `logondate`, `username`, `email`, `password`, `authkey`, `state`, `totalsessioncounter`, `currentsessioncounter`) VALUES
(1, NULL, NULL, 1366174490, 'adminxyz', 'wdspeedy@gmail.com', 'ca472d8ab5780fa252c95185889b96ce', NULL, 1, 0, 0),
(2, NULL, NULL, NULL, 'invitado', 'invitado', '9ce21d8f3992d89a325aa9dcf520a591', NULL, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `d8d1f_alumnos`
--

CREATE TABLE IF NOT EXISTS `d8d1f_alumnos` (
  `codalumno` int(11) NOT NULL,
  `idvhd` varchar(11) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nro` int(11) NOT NULL,
  `grado` varchar(2) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `orden` int(2) NOT NULL,
  `apellidosnombres` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `genero` varchar(1) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `observacion` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`codalumno`),
  UNIQUE KEY `codalumno` (`codalumno`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `d8d1f_notas`
--

CREATE TABLE IF NOT EXISTS `d8d1f_notas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Idvhd` varchar(11) NOT NULL,
  `nro` int(11) NOT NULL,
  `grado` varchar(2) NOT NULL,
  `orden` varchar(2) NOT NULL,
  `codalumno` int(8) NOT NULL,
  `apellidosnombres` varchar(255) NOT NULL,
  `genero` varchar(1) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `arte` int(2) DEFAULT NULL,
  `cta` int(2) DEFAULT NULL,
  `comu` int(2) DEFAULT NULL,
  `efis` int(2) DEFAULT NULL,
  `etra` int(2) DEFAULT NULL,
  `erel` int(2) DEFAULT NULL,
  `fcc` int(2) DEFAULT NULL,
  `hge` int(2) DEFAULT NULL,
  `ingl` int(2) DEFAULT NULL,
  `mate` int(2) DEFAULT NULL,
  `pfrrhh` int(2) DEFAULT NULL,
  `areasdesaprobadas` varchar(2) DEFAULT NULL,
  `recomendacion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `codalumno` (`codalumno`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `d8d1f_notas`
--

INSERT INTO `d8d1f_notas` (`id`, `Idvhd`, `nro`, `grado`, `orden`, `codalumno`, `apellidosnombres`, `genero`, `observacion`, `arte`, `cta`, `comu`, `efis`, `etra`, `erel`, `fcc`, `hge`, `ingl`, `mate`, `pfrrhh`, `areasdesaprobadas`, `recomendacion`) VALUES
(2, '1A1', 1, '1A', '1', 12345678, 'ALTAMIRANO ATAUCURI, JHON ELVIS', 'M', '0', 14, 11, 10, 15, 11, 12, 12, 10, 12, 9, 10, '4', 'Tutor MARCOS : Tienes 4 areas desaprobadas, debes esforzarte bastante para reivindicarte, tu tutor de aula se reunira con tu padre o apoderado para ayudarte a superar tus calificaciones'),
(3, '2A6', 2, '2A', '6', 11111111, 'OCTUBRE CARBAJAL, MARIA NATALI', 'F', 'REPITENTE', 15, 11, 12, 16, 12, 12, 11, 12, 11, 9, 13, '1', 'Tutor JUAN : Tienes una area desaprobada, debes estudiar un poco mas y reforzar tus habitos de estudio'),
(4, '3A14', 3, '3A', '14', 22222222, 'AGUERRO CAMPOS, DAYSI LIZET', 'F', '0', 16, 16, 17, 16, 16, 18, 19, 17, 16, 19, 18, '0', 'Tutor MARIA : Invicto, felicitaciones por tu esfuerzo y dedicacion'),
(5, '4A4', 4, '4A', '4', 33333333, 'AGUILA AREQUIPA, OLGA ESTHER', 'F', 'REINGRESANTE', 15, 11, 14, 15, 16, 14, 13, 12, 12, 9, 11, '1', 'Tutor JOSEPH : Tienes una area desaprobada, debes estudiar un poco mas y reforzar tus habitos de estudio'),
(6, '5A9', 5, '5A', '9', 44444444, 'CAMPOS TRUJILLO, SANDRA LIZETH', 'F', 'TRASLADADO', 16, 12, 16, 17, 16, 17, 15, 14, 14, 15, 15, '0', 'Tutor VICTOR : Invicto, felicitaciones por tu esfuerzo y dedicacion');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
