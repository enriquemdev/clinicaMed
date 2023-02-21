-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20220501.46b7525c53
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2022 a las 09:55:34
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdcleanfull`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `increment_persona` (IN `id` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
         	SET id=(SELECT COUNT(*) FROM tblpersona);
		UPDATE tblpersona SET Codigo = id WHERE Codigo = 0;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerCargosEmpleado` (IN `idEmpleado` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
         SELECT  a.FechaAsignacion, a.Salario, a.Estado,b.Nombre as NombreCargo
		FROM tblhistorialcargos as a 
        INNER JOIN catcargos as b ON (a.IdCargo = b.ID)
		where a.CodEmpleado=idEmpleado
        ORDER BY a.FechaAsignacion DESC;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosEmpleado` (IN `idEmpleado` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT  a.Codigo as CodigoEmpleado, a.INSS, a.CodPersona, b.Cedula, 
        b.Nombres as NombresEmpleado, b.Apellidos as ApellidosEmpleado, 
        b.Fecha_de_nacimiento, b.Telefono, b.Genero, b.Estado_civil, b.Direccion, b.Email,
        b.Direccion
		FROM tblempleado as a 
        INNER JOIN tblpersona as b ON (a.CodPersona = b.Codigo)
		where a.Codigo=idEmpleado;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosEmpleados` ()   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
		#set @minimo=(SELECT MIN(Codigo) FROM tblempleado);
        #set @maximo=(SELECT MAX(Codigo) FROM tblempleado);
        SELECT  a.Codigo as CodigoEmpleado, a.INSS, a.CodPersona, b.Cedula, b.Nombres as NombresEmpleado,
        b.Apellidos as ApellidosEmpleado, b.Fecha_de_nacimiento, b.Telefono, 
        b.Genero, b.Estado_civil, b.Direccion, b.Email
		FROM tblempleado as a 
        INNER JOIN tblpersona as b ON (a.CodPersona = b.Codigo);
        #ORDER BY a.Codigo ASC LIMIT (@minimo,@maximo);
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosExamen` (IN `idExamen` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT a.Codigo AS CodigoExamen, a.RecetaPrevia,c.Nombres as NombresDoctor,
		c.Apellidos as ApellidosDoctor,e.Nombres as NombresPaciente,e.Apellidos as ApellidosPaciente,
		f.Nombre as NombreSalaMedica,a.FechaYHora  
		FROM tblexamen as a
		INNER JOIN tblempleado as b ON (a.EmpleadoRealizacion=b.Codigo)
		INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
		INNER JOIN tblpaciente as d ON (a.CodPaciente=d.CodigoP)
		INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
		INNER JOIN catsalaexamen as f ON (a.SalaMedica=f.ID)
		where a.Codigo=idExamen;	
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosExamenes` ()   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT a.Codigo AS CodigoExamen, a.RecetaPrevia,c.Nombres as NombresDoctor,
		c.Apellidos as ApellidosDoctor,e.Nombres as NombresPaciente,e.Apellidos as ApellidosPaciente,
		f.Nombre as NombreSalaMedica,a.FechaYHora
		FROM tblexamen as a
		INNER JOIN tblempleado as b ON (a.EmpleadoRealizacion=b.Codigo)
		INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
		INNER JOIN tblpaciente as d ON (a.CodPaciente=d.CodigoP)
		INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
		INNER JOIN catsalaexamen as f ON (a.SalaMedica=f.ID);	
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosPaciente` (IN `idPaciente` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT a.CodigoP, a.INSS,a.CodPersona, b.Cedula, b.Telefono, b.Direccion, b.Nombres AS NombresPaciente, 
        b.Apellidos as ApellidosPaciente, b.Fecha_de_nacimiento, a.CodExpediente, 
        a.GrupoSanguineo, c.Nombre as NombreGrupoSanguineo
		FROM tblpaciente as a
		INNER JOIN tblpersona as b ON (a.CodPersona = b.Codigo) 
        INNER JOIN catgruposanguineo as c ON (a.GrupoSanguineo=c.ID)
		where a.CodigoP=idPaciente;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerDatosPacientes` ()   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT a.CodigoP, a.INSS,a.CodPersona, b.Nombres AS NombresPaciente, 
        b.Apellidos as ApellidosPaciente, b.Fecha_de_nacimiento, a.CodExpediente, 
        a.GrupoSanguineo, c.Nombre as NombreGrupoSanguineo
		FROM tblpaciente as a
		INNER JOIN tblpersona as b ON (a.CodPersona = b.Codigo) 
        INNER JOIN catgruposanguineo as c ON (a.GrupoSanguineo=c.ID);
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerEspecialidadesEmpleado` (IN `idEmpleado` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT  b.Nombres, b.Apellidos, d.Nombre as NombreEspecialidad,d.Descripcion
		FROM tblempleado as a 
        INNER JOIN tblpersona as b ON (a.CodPersona=b.Codigo) 
        INNER JOIN tblespecialidad as c ON (c.CodDoctor=a.Codigo)
        INNER JOIN catespecialidades as d ON (d.ID=c.IDEspecialidad)
        WHERE a.Codigo=idEmpleado;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerFamiliaresPersona` (IN `idPersona` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
        SELECT  a.Nombres, a.Apellidos, a.Telefono,b.EsTutor,d.Nombre as Parentesco,a.Email
		FROM tblpersona as a 
        INNER JOIN tblfamiliares as b ON (b.CodPersona=a.Codigo)
        INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
        INNER JOIN catparentesco as d ON (b.Parentesco=d.ID)
        WHERE b.FamiliarDe=idPersona;
	COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_ObtenerUltimoCargoActivoEmpleado` (IN `idEmpleado` INT)   BEGIN	
	DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
		SELECT 'An SQL exception has occurred' AS SQL_EXCEPTION ;
        ROLLBACK;
    END;
	START TRANSACTION;
         SELECT  b.Nombre as NombreCargo
		FROM tblhistorialcargos as a 
        INNER JOIN catcargos as b ON (a.IdCargo = b.ID)
		where a.CodEmpleado=idEmpleado AND a.Estado=1
        ORDER BY a.FechaAsignacion DESC;
	COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catcaja`
--

CREATE TABLE `catcaja` (
  `idCaja` int(11) NOT NULL,
  `nombreCaja` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `EstadoCaja` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catcaja`
--

INSERT INTO `catcaja` (`idCaja`, `nombreCaja`, `Descripcion`, `EstadoCaja`) VALUES
(1, 'Caja 1 ClinicaMedica', 'Caja de clinica medica', 2),
(2, 'Caja 2', 'Segunda caja', 1),
(3, 'caja 3', '3', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catcargos`
--

CREATE TABLE `catcargos` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catcargos`
--

INSERT INTO `catcargos` (`ID`, `Nombre`, `Descripcion`, `FechaRegistro`) VALUES
(1, 'Enfermera', 'Profesional', '2022-09-30 06:00:00'),
(2, 'Doctor', 'Profesional', '2022-09-30 06:00:00'),
(3, 'Recepcionista', 'Profesional', '2022-09-30 06:00:00'),
(4, 'Cajero', 'Profesional', '2022-09-30 06:00:00'),
(5, 'Gerente', 'Profesional', '2022-09-30 06:00:00'),
(6, 'Doctor radiologico', 'Profesional', '2022-09-30 06:00:00'),
(7, 'Administrador', 'Profesional', '2022-09-30 06:00:00'),
(8, 'Farmaceuta', 'Profesional', '2022-09-30 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catconsultorio`
--

CREATE TABLE `catconsultorio` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catconsultorio`
--

INSERT INTO `catconsultorio` (`ID`, `Nombre`, `Descripcion`, `FechaRegistro`) VALUES
(1, 'Consultorio 1', 'Sala de consulta', '2022-09-30 06:00:00'),
(2, 'Consultorio 2', 'Sala de consulta', '2022-09-30 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catenfermedades`
--

CREATE TABLE `catenfermedades` (
  `ID` tinyint(4) NOT NULL,
  `NombreEnfermedad` varchar(40) NOT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `TipoEnfermedad` varchar(30) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catenfermedades`
--

INSERT INTO `catenfermedades` (`ID`, `NombreEnfermedad`, `Descripcion`, `TipoEnfermedad`, `FechaRegistro`) VALUES
(1, 'Bronquitis aguda', 'Enfermedad comun', 'Respiratoria', '2022-09-30 06:00:00'),
(2, 'Resfriado común', 'Enfermedad comun', 'Respiratoria', '2022-09-30 06:00:00'),
(3, 'Influenza', 'Enfermedad comun', 'Respiratoria', '2022-09-30 06:00:00'),
(4, 'COVID 19', 'Enfermedad mortal', 'Respiratoria', '2022-09-30 06:00:00'),
(5, 'VIH', 'Enfermedad mortal', 'ITS', '2022-09-30 06:00:00'),
(6, 'SIDA', 'Enfermedad mortal', 'ITS', '2022-09-30 06:00:00'),
(7, 'Sifilis', 'Enfermedad mortal', 'ITS', '2022-09-30 06:00:00'),
(8, 'Gonorrea', 'Enfermedad mortal', 'ITS', '2022-09-30 06:00:00'),
(9, 'Evola', 'Enfermedad mortal', 'Respiratoria', '2022-09-30 06:00:00'),
(10, 'Dengue', 'Enfermedad mortal', 'Febril', '2022-09-30 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catespecialidades`
--

CREATE TABLE `catespecialidades` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(80) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catespecialidades`
--

INSERT INTO `catespecialidades` (`ID`, `Nombre`, `Descripcion`, `FechaRegistro`) VALUES
(1, 'Pediatria', 'Especialista', '2022-09-30 06:00:00'),
(2, 'Nutriologia', 'Especialista', '2022-09-30 06:00:00'),
(3, 'Cardiologia', 'Especialista', '2022-09-30 06:00:00'),
(4, 'Gastroenterología', 'Especialista', '2022-09-30 06:00:00'),
(5, 'Rinoplastia', 'Especialista', '2022-09-30 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestado`
--

CREATE TABLE `catestado` (
  `ID` tinyint(4) NOT NULL,
  `NombreEstado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestado`
--

INSERT INTO `catestado` (`ID`, `NombreEstado`) VALUES
(1, 'Activo'),
(2, 'Inactivo'),
(3, 'Espera'),
(4, 'Negado'),
(5, 'Abierto'),
(6, 'Cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadocita`
--

CREATE TABLE `catestadocita` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadocita`
--

INSERT INTO `catestadocita` (`ID`, `Nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadocivil`
--

CREATE TABLE `catestadocivil` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadocivil`
--

INSERT INTO `catestadocivil` (`ID`, `Nombre`) VALUES
(1, 'Solter@'),
(2, 'Casad@');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadocompra`
--

CREATE TABLE `catestadocompra` (
  `idEstadoCompra` int(11) NOT NULL,
  `nombreEstado` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadocompra`
--

INSERT INTO `catestadocompra` (`idEstadoCompra`, `nombreEstado`) VALUES
(1, 'Solicitado'),
(2, 'Autorizado'),
(3, 'Terminado'),
(4, 'Rechazado'),
(5, 'Revertdio'),
(6, 'Recibido completo'),
(7, 'Recibido parcial'),
(8, 'Recibido erroneo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadoconsulta`
--

CREATE TABLE `catestadoconsulta` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadoconsulta`
--

INSERT INTO `catestadoconsulta` (`ID`, `Nombre`) VALUES
(1, 'Asignada'),
(2, 'En Espera'),
(3, 'Prioridad'),
(4, 'Revertida'),
(5, 'Terminada'),
(6, 'En proceso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadoservicios`
--

CREATE TABLE `catestadoservicios` (
  `idEstadoServicio` int(11) NOT NULL,
  `nombreEstadoServicio` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadoservicios`
--

INSERT INTO `catestadoservicios` (`idEstadoServicio`, `nombreEstadoServicio`, `Descripcion`) VALUES
(1, 'Pago Pendiente', 'No se ha pagado nada del servicio aun'),
(2, 'Pago Semi-pendiente', 'Ya se ha realizado un adelanto del pago, pero aun no se paga el valor total neto.'),
(3, 'Pago Finalizado', 'Se ha cancelado el valor total neto del servicio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catestadosmedicos`
--

CREATE TABLE `catestadosmedicos` (
  `idEstadoMedico` int(11) NOT NULL,
  `nombreEstadoMedico` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catestadosmedicos`
--

INSERT INTO `catestadosmedicos` (`idEstadoMedico`, `nombreEstadoMedico`) VALUES
(1, 'Activo'),
(2, 'En Espera'),
(3, 'Denegado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catexamenesmedicos`
--

CREATE TABLE `catexamenesmedicos` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Precio` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catexamenesmedicos`
--

INSERT INTO `catexamenesmedicos` (`ID`, `Nombre`, `Precio`) VALUES
(1, 'Radiografia', '300'),
(2, 'Sangre general', '100'),
(3, 'Tomografía', '250'),
(4, 'Orina', '50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catgenero`
--

CREATE TABLE `catgenero` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catgenero`
--

INSERT INTO `catgenero` (`ID`, `Nombre`) VALUES
(1, 'M'),
(2, 'F');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catgruposanguineo`
--

CREATE TABLE `catgruposanguineo` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catgruposanguineo`
--

INSERT INTO `catgruposanguineo` (`ID`, `Nombre`) VALUES
(1, 'O-'),
(2, 'O+'),
(3, 'A-'),
(4, 'A+'),
(5, 'B-'),
(6, 'B+'),
(7, 'AB-'),
(8, 'AB+');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catlaboratorio`
--

CREATE TABLE `catlaboratorio` (
  `idLaboratorio` int(11) NOT NULL,
  `nombreLaboratorio` varchar(80) NOT NULL,
  `descripcionLaboratorio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catlaboratorio`
--

INSERT INTO `catlaboratorio` (`idLaboratorio`, `nombreLaboratorio`, `descripcionLaboratorio`) VALUES
(1, 'LABORATORIOS RARPE', 'Son tuanis loco'),
(2, 'LABORATORIOS BAYER', 'Son tuanis loco'),
(3, 'LABORATORIOS RAMOS', 'Son tuanis loco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmaquinaria`
--

CREATE TABLE `catmaquinaria` (
  `ID` tinyint(4) NOT NULL,
  `NombreMaquinaria` varchar(80) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmaquinaria`
--

INSERT INTO `catmaquinaria` (`ID`, `NombreMaquinaria`, `Descripcion`) VALUES
(1, 'Esterilizadores', 'Equipo medico'),
(2, 'Desfibriladores', 'Equipo medico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmedicamentos`
--

CREATE TABLE `catmedicamentos` (
  `Codigo` int(11) NOT NULL,
  `nombreComercial` varchar(40) NOT NULL,
  `nombreGenerico` varchar(40) NOT NULL,
  `formula` varchar(100) NOT NULL,
  `presentacion` varchar(40) NOT NULL,
  `descripcionMedicamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmedicamentos`
--

INSERT INTO `catmedicamentos` (`Codigo`, `nombreComercial`, `nombreGenerico`, `formula`, `presentacion`, `descripcionMedicamento`) VALUES
(1, 'Omeprazol', 'Omeprazol', 'asd', 'asd', 'asd'),
(2, 'Paracetamol', 'Paracetamol', 'asd', 'asd', 'asd'),
(3, 'Acetaminofen', 'Acetaminofen', 'asd', 'asd', 'asd'),
(4, 'Salbutamol', 'Salbutamol', 'asd', 'asd', 'asd'),
(5, 'Ketotifeno', 'Ketotifeno', 'asd', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmetodosdepago`
--

CREATE TABLE `catmetodosdepago` (
  `idMetodoPago` int(11) NOT NULL,
  `NombreMetodoPago` varchar(100) NOT NULL,
  `Descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmetodosdepago`
--

INSERT INTO `catmetodosdepago` (`idMetodoPago`, `NombreMetodoPago`, `Descripcion`) VALUES
(1, 'Contado', 'Pago en efectivo'),
(3, 'Tarjeta LAFISE', 'Pago por medio de tarjeta LAFISE'),
(4, 'Tarjeta BAC', 'Del banco bac');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmodulos`
--

CREATE TABLE `catmodulos` (
  `CodModulo` int(11) NOT NULL,
  `NombreModulo` varchar(30) NOT NULL,
  `DescripcionModulo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmodulos`
--

INSERT INTO `catmodulos` (`CodModulo`, `NombreModulo`, `DescripcionModulo`) VALUES
(1, 'Cita', 'Modulo cita'),
(2, 'Usuarios', 'Modulo usuarios'),
(3, 'Empleados', 'Modulo empleados'),
(4, 'Paciente', 'Modulo paciente'),
(5, 'Consulta', 'Modulo consulta'),
(6, 'Examen', 'Modulo examen'),
(7, 'Caja', 'Modulo caja'),
(8, 'Catalogos', 'Modulo catalogos'),
(9, 'Farmacia', 'Modulo farmacia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmoneda`
--

CREATE TABLE `catmoneda` (
  `idMoneda` int(11) NOT NULL,
  `nombreMoneda` varchar(100) NOT NULL,
  `simbolo` varchar(2) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `EsReferencia` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmoneda`
--

INSERT INTO `catmoneda` (`idMoneda`, `nombreMoneda`, `simbolo`, `Descripcion`, `EsReferencia`) VALUES
(1, 'Cordoba', 'C$', 'Moneda nicaraguense', 1),
(2, 'Dolar', '$', 'Moneda estado unidence', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catnivelacademico`
--

CREATE TABLE `catnivelacademico` (
  `ID` tinyint(4) NOT NULL,
  `NombreNivelAcademico` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catnivelacademico`
--

INSERT INTO `catnivelacademico` (`ID`, `NombreNivelAcademico`) VALUES
(1, 'Secundaria'),
(2, 'Universitario'),
(3, 'Maestria'),
(4, 'Doctorado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catparentesco`
--

CREATE TABLE `catparentesco` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catparentesco`
--

INSERT INTO `catparentesco` (`ID`, `Nombre`) VALUES
(1, 'No relación'),
(2, 'Padre'),
(3, 'Madre');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catprivilegio`
--

CREATE TABLE `catprivilegio` (
  `Codigo` int(11) NOT NULL,
  `NombrePrivilegio` varchar(30) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catprivilegio`
--

INSERT INTO `catprivilegio` (`Codigo`, `NombrePrivilegio`, `Descripcion`) VALUES
(1, 'Agregar', 'Agregar Registros'),
(2, 'Ver', 'Ver Registros'),
(3, 'Actualizar', 'Actualizar Registros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catsalaexamen`
--

CREATE TABLE `catsalaexamen` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Dimensiones` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catsalaexamen`
--

INSERT INTO `catsalaexamen` (`ID`, `Nombre`, `Dimensiones`) VALUES
(1, 'Sala examen 1', '2x2'),
(2, 'Sala examen 2', '3x3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catservicios`
--

CREATE TABLE `catservicios` (
  `idServicio` int(11) NOT NULL,
  `nombreServicio` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `PrecioGeneral` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catservicios`
--

INSERT INTO `catservicios` (`idServicio`, `nombreServicio`, `Descripcion`, `PrecioGeneral`) VALUES
(1, 'Examen', 'Un examen alcorazon', '500'),
(2, 'Consulta general', 'Una consulta general', '300'),
(3, 'Venta medicamentos', 'Una venta de medicamentos', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catsintomas`
--

CREATE TABLE `catsintomas` (
  `idSintoma` int(11) NOT NULL,
  `nombreSintoma` varchar(100) NOT NULL,
  `descripcionSintoma` varchar(255) DEFAULT NULL,
  `estadoSintoma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catsintomas`
--

INSERT INTO `catsintomas` (`idSintoma`, `nombreSintoma`, `descripcionSintoma`, `estadoSintoma`) VALUES
(1, 'Calentura', 'Temperatura corporal elevada', 1),
(2, 'Tos', 'Tos fea', 1),
(3, 'Secrecion nasal', 'moquera', 1),
(4, 'Dolor de estomago', 'Dolor Estomacal', 1),
(5, 'Dolor de cabeza', 'Dolor en la Cabeza', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catsubmodulos`
--

CREATE TABLE `catsubmodulos` (
  `CodSubModulo` int(11) NOT NULL,
  `NombreSubModulo` varchar(30) NOT NULL,
  `DescripcionSubModulo` varchar(100) DEFAULT NULL,
  `CodigoModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catsubmodulos`
--

INSERT INTO `catsubmodulos` (`CodSubModulo`, `NombreSubModulo`, `DescripcionSubModulo`, `CodigoModulo`) VALUES
(1, 'Cita', 'Submodulo Cita', 1),
(2, 'Usuarios', 'Submodulo Usuarios', 2),
(3, 'Empleados', 'Submodulo Empleados', 3),
(4, 'Especialidades', 'Submodulo Empleados', 3),
(5, 'Estudios Académicos', 'Submodulo Empleados', 3),
(6, 'Familiares Empleado', 'Submodulo Empleados', 3),
(7, 'Historial Cargos', 'Submodulo Empleados', 3),
(8, 'Paciente', 'Submodulo Paciente', 4),
(9, 'Antecedentes', 'Submodulo Paciente', 4),
(10, 'Familiares Paciente', 'Submodulo Paciente', 4),
(11, 'Ocupacion Paciente', 'Submodulo Paciente', 4),
(12, 'Consulta', 'Submodulo Consulta', 5),
(13, 'Signos Vitales', 'Submodulo Consulta', 5),
(14, 'Diagnóstico', 'Submodulo Consulta', 5),
(15, 'Receta Médica', 'Submodulo Consulta', 5),
(16, 'Receta Examen', 'Submodulo Consulta', 5),
(17, 'Constancia', 'Submodulo Consulta', 5),
(18, 'Examen', 'Submodulo Examen', 6),
(19, 'Resultados', 'Submodulo Examen', 6),
(20, 'Maquinaria Médica', 'Submodulo Examen', 6),
(21, 'Catalogos', 'Submodulo Catalogos', 8),
(22, 'SolicitudConsulta', 'Donde la recepcionista solicita la consulta hacia el medico.', 5),
(23, 'InventarioFarmacia', 'Inventario para farmacia', 9),
(24, 'ComprasFarmacia', 'Compras para farmacia', 9),
(25, 'VentasFarmacia', 'Ventas para farmacia', 9),
(26, 'DetalleCompraFarmacia', 'Detalle de la compra de la farmacia', 9),
(27, 'DetalleVentaFarmacia', 'Detalle de la venta de la farmacia', 9),
(28, 'Realizar Cobro', 'Proceso normal de la gestion de cobro de servicios de la clinica', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblantecedentes`
--

CREATE TABLE `tblantecedentes` (
  `ID` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `Enfermedad` tinyint(4) NOT NULL,
  `FechaAparicion` date DEFAULT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Tratamiento` varchar(200) NOT NULL,
  `Notas` varchar(200) DEFAULT NULL,
  `EsGenetico` tinyint(4) NOT NULL,
  `EstadoAntecedente` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblaperturacaja`
--

CREATE TABLE `tblaperturacaja` (
  `idApertura` int(11) NOT NULL,
  `Caja` int(11) NOT NULL,
  `MontoInicial` decimal(10,2) NOT NULL,
  `EmpleadoCaja` int(11) NOT NULL,
  `FyHInicio` datetime NOT NULL,
  `FyHCierre` datetime DEFAULT NULL,
  `direccionMAC` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblaperturacaja`
--

INSERT INTO `tblaperturacaja` (`idApertura`, `Caja`, `MontoInicial`, `EmpleadoCaja`, `FyHInicio`, `FyHCierre`, `direccionMAC`) VALUES
(20, 3, '0.00', 5, '2022-09-26 21:49:52', '2022-09-26 21:54:43', '0A-00-27-00-00-12'),
(21, 2, '0.00', 5, '2022-09-26 21:54:48', '2022-09-27 22:17:16', '0A-00-27-00-00-12'),
(22, 1, '3000.00', 5, '2022-09-27 22:18:59', '2022-09-27 22:29:29', '0A-00-27-00-00-12'),
(23, 2, '0.00', 5, '2022-10-11 01:53:25', NULL, '18-03-73-26-C3-C7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblasignacionlote`
--

CREATE TABLE `tblasignacionlote` (
  `idAsignacionLote` int(11) NOT NULL,
  `detSoliCompra` int(11) NOT NULL,
  `lote` int(11) NOT NULL,
  `asgindadoYa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblasignacionlote`
--

INSERT INTO `tblasignacionlote` (`idAsignacionLote`, `detSoliCompra`, `lote`, `asgindadoYa`) VALUES
(1, 1, 1, 50),
(2, 4, 2, 50),
(3, 3, 3, 50),
(4, 2, 4, 50);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblcita`
--

CREATE TABLE `tblcita` (
  `IDCita` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `fechaProgramada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblcita`
--

INSERT INTO `tblcita` (`IDCita`, `CodPaciente`, `fechaProgramada`) VALUES
(1, 3, '2022-10-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblclientes`
--

CREATE TABLE `tblclientes` (
  `idCliente` int(11) NOT NULL,
  `CodPersona` int(11) NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblclientes`
--

INSERT INTO `tblclientes` (`idCliente`, `CodPersona`, `FechaRegistro`) VALUES
(1, 13, '2022-10-11 07:53:54'),
(2, 15, '2022-10-11 07:54:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblcompra`
--

CREATE TABLE `tblcompra` (
  `idCompra` int(11) NOT NULL,
  `solicitudCompra` int(11) NOT NULL,
  `estadoCompra` int(11) NOT NULL,
  `nota` varchar(255) NOT NULL,
  `fechaRecibido` date NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblcompra`
--

INSERT INTO `tblcompra` (`idCompra`, `solicitudCompra`, `estadoCompra`, `nota`, `fechaRecibido`, `fechaRegistro`) VALUES
(1, 1, 6, 'Recibi mercancia completa', '2022-10-11', '2022-10-11 06:39:09'),
(2, 3, 6, 'Recibi mercancia completa', '2022-10-11', '2022-10-11 06:39:21'),
(3, 2, 6, 'Recibi mercancia completa', '2022-10-11', '2022-10-11 06:39:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblconstancia`
--

CREATE TABLE `tblconstancia` (
  `Codigo` int(11) NOT NULL,
  `CodDiagnostico` int(11) NOT NULL,
  `Razon` varchar(200) DEFAULT NULL,
  `Fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `HoraEntrada` datetime DEFAULT NULL,
  `HoraSalida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblconstancia`
--

INSERT INTO `tblconstancia` (`Codigo`, `CodDiagnostico`, `Razon`, `Fecha`, `HoraEntrada`, `HoraSalida`) VALUES
(1, 1, 'dasdasdasdasd', '2022-10-11 07:46:56', '2022-10-11 01:46:00', '2022-10-11 01:46:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblconsulta`
--

CREATE TABLE `tblconsulta` (
  `Codigo` int(11) NOT NULL,
  `CodMedico` int(11) NOT NULL,
  `IdCita` int(11) DEFAULT NULL,
  `CodPaciente` int(11) NOT NULL,
  `CodConsultorio` tinyint(4) NOT NULL,
  `Estado` tinyint(4) NOT NULL,
  `FechaYHora` datetime NOT NULL,
  `FhInicio` datetime DEFAULT NULL,
  `FhFinal` datetime DEFAULT NULL,
  `MotivoConsulta` varchar(250) DEFAULT NULL,
  `RegistradoPor` int(11) NOT NULL,
  `idServicio` int(11) NOT NULL,
  `NotasConsulta` varchar(255) DEFAULT NULL,
  `MotivoRevertida` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblconsulta`
--

INSERT INTO `tblconsulta` (`Codigo`, `CodMedico`, `IdCita`, `CodPaciente`, `CodConsultorio`, `Estado`, `FechaYHora`, `FhInicio`, `FhFinal`, `MotivoConsulta`, `RegistradoPor`, `idServicio`, `NotasConsulta`, `MotivoRevertida`) VALUES
(1, 3, 1, 3, 1, 5, '2022-10-11 01:17:50', '2022-10-11 01:38:56', NULL, 'El chatel tiene gripe, clase loquera', 4, 1, 'adafasf', NULL),
(2, 3, 0, 2, 2, 5, '2022-10-11 01:22:10', '2022-10-11 01:38:41', NULL, 'El mae es muy gay, necesita pasar por urgencias', 4, 2, 'Casi se palma', NULL),
(3, 3, 0, 1, 1, 5, '2022-10-11 01:22:52', '2022-10-11 01:39:03', NULL, 'Este mae tambien es cochon, pero, no es declarado, asi que no necesita ir a urgencias', 4, 3, 'asfasfasfasf', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetallereceta`
--

CREATE TABLE `tbldetallereceta` (
  `Codigo` int(11) NOT NULL,
  `Medicamento` int(11) DEFAULT NULL,
  `Dosis` varchar(50) DEFAULT NULL,
  `Frecuencia` varchar(40) DEFAULT NULL,
  `CodReceta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetallereceta`
--

INSERT INTO `tbldetallereceta` (`Codigo`, `Medicamento`, `Dosis`, `Frecuencia`, `CodReceta`) VALUES
(1, 1, '20', '12', 1),
(2, 2, '15', '12', 2),
(3, 3, '25', '12', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetallesdecita`
--

CREATE TABLE `tbldetallesdecita` (
  `IDDetallecita` int(11) NOT NULL,
  `IdCita` int(11) NOT NULL,
  `HoraInicio` time NOT NULL,
  `HoraFin` time DEFAULT NULL,
  `IdConsultorio` tinyint(4) DEFAULT NULL,
  `CodDoctor` int(11) DEFAULT NULL,
  `Estado` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetallesdecita`
--

INSERT INTO `tbldetallesdecita` (`IDDetallecita`, `IdCita`, `HoraInicio`, `HoraFin`, `IdConsultorio`, `CodDoctor`, `Estado`) VALUES
(1, 1, '08:10:00', '10:10:00', 1, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetalleventafarmacia`
--

CREATE TABLE `tbldetalleventafarmacia` (
  `idDetalleVentaFarmacia` int(11) NOT NULL,
  `ventaFarmacia` int(11) NOT NULL,
  `detalleRecetaMedica` int(11) NOT NULL,
  `cantidadVendida` int(11) NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetalleventafarmacia`
--

INSERT INTO `tbldetalleventafarmacia` (`idDetalleVentaFarmacia`, `ventaFarmacia`, `detalleRecetaMedica`, `cantidadVendida`, `fechaRegistro`) VALUES
(1, 1, 1, 20, '2022-10-11 07:52:10'),
(2, 2, 2, 15, '2022-10-11 07:52:24'),
(3, 3, 3, 25, '2022-10-11 07:52:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetfactconsulta`
--

CREATE TABLE `tbldetfactconsulta` (
  `Codigo` int(11) NOT NULL,
  `CodConsulta` int(11) NOT NULL,
  `CodFactura` int(11) NOT NULL,
  `Precio` decimal(10,0) DEFAULT NULL,
  `Descuento` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetfactexamen`
--

CREATE TABLE `tbldetfactexamen` (
  `Codigo` int(11) NOT NULL,
  `CodExamen` int(11) NOT NULL,
  `Precio` decimal(10,0) DEFAULT NULL,
  `Descuento` decimal(10,0) DEFAULT NULL,
  `CodFactura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetpagoservicios`
--

CREATE TABLE `tbldetpagoservicios` (
  `idDetPago` int(11) NOT NULL,
  `ServicioBrindado` int(11) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `RebajaPago` decimal(10,2) NOT NULL,
  `metodoDePago` int(11) NOT NULL,
  `NumeroRecibo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetpagoservicios`
--

INSERT INTO `tbldetpagoservicios` (`idDetPago`, `ServicioBrindado`, `Monto`, `RebajaPago`, `metodoDePago`, `NumeroRecibo`) VALUES
(1, 3, '300.00', '0.00', 1, 1),
(2, 6, '400.00', '0.00', 1, 1),
(3, 2, '300.00', '0.00', 1, 2),
(4, 4, '100.00', '0.00', 1, 2),
(5, 5, '50.00', '0.00', 1, 2),
(6, 8, '1000.00', '0.00', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetsolicitudcompra`
--

CREATE TABLE `tbldetsolicitudcompra` (
  `idDetSolicitudCompra` int(11) NOT NULL,
  `solicitudCompra` int(11) NOT NULL,
  `medicamento` int(11) NOT NULL,
  `proveedor` int(11) NOT NULL,
  `laboratorio` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `costo` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetsolicitudcompra`
--

INSERT INTO `tbldetsolicitudcompra` (`idDetSolicitudCompra`, `solicitudCompra`, `medicamento`, `proveedor`, `laboratorio`, `cantidad`, `costo`) VALUES
(1, 1, 1, 1, 2, 50, 15),
(2, 2, 2, 1, 1, 50, 12),
(3, 3, 3, 4, 2, 50, 7),
(4, 3, 4, 4, 1, 50, 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldiagnosticoconsulta`
--

CREATE TABLE `tbldiagnosticoconsulta` (
  `Codigo` int(11) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `IdEnfermedad` tinyint(4) NOT NULL,
  `CodConsulta` int(11) NOT NULL,
  `Nota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldiagnosticoconsulta`
--

INSERT INTO `tbldiagnosticoconsulta` (`Codigo`, `Descripcion`, `IdEnfermedad`, `CodConsulta`, `Nota`) VALUES
(1, 'afasfasfasfa', 10, 3, 'sfasfasfasf'),
(2, 'asxxx', 6, 1, 'afsfafsafs'),
(3, 'asdasfasfasfasf', 4, 2, 'asfasfasfafs');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblempleado`
--

CREATE TABLE `tblempleado` (
  `Codigo` int(11) NOT NULL,
  `INSS` varchar(9) NOT NULL,
  `FechaIngreso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CodPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblempleado`
--

INSERT INTO `tblempleado` (`Codigo`, `INSS`, `FechaIngreso`, `CodPersona`) VALUES
(1, '111111111', '2022-09-30 06:00:00', 1),
(2, '222222222', '2022-09-30 06:00:00', 2),
(3, '333333333', '2022-09-30 06:00:00', 3),
(4, '444444444', '2022-09-30 06:00:00', 4),
(5, '555555555', '2022-09-30 06:00:00', 5),
(6, '666666666', '2022-09-30 06:00:00', 6),
(7, '777777777', '2022-09-30 06:00:00', 7),
(8, '888888888', '2022-09-30 06:00:00', 8),
(9, '454545454', '2022-10-11 06:05:18', 9),
(10, '656565656', '2022-10-11 06:06:20', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblespecialidad`
--

CREATE TABLE `tblespecialidad` (
  `ID` int(11) NOT NULL,
  `CodDoctor` int(11) NOT NULL,
  `IDEspecialidad` tinyint(4) NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblespecialidad`
--

INSERT INTO `tblespecialidad` (`ID`, `CodDoctor`, `IDEspecialidad`, `FechaRegistro`) VALUES
(1, 3, 3, '2022-10-11 06:21:35'),
(2, 3, 5, '2022-10-11 06:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblestudioacademico`
--

CREATE TABLE `tblestudioacademico` (
  `IDEstudioAcademico` int(11) NOT NULL,
  `CodEmpleado` int(11) NOT NULL,
  `NombreEstudio` varchar(100) NOT NULL,
  `TipoEstudio` tinyint(4) NOT NULL,
  `Institucion` varchar(60) NOT NULL,
  `InicioEstudio` date NOT NULL,
  `FinEstudio` date NOT NULL,
  `Diploma` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblestudioacademico`
--

INSERT INTO `tblestudioacademico` (`IDEstudioAcademico`, `CodEmpleado`, `NombreEstudio`, `TipoEstudio`, `Institucion`, `InicioEstudio`, `FinEstudio`, `Diploma`) VALUES
(1, 9, 'Ingeniero de sistemas', 2, 'Universidad Nacional de Ingenieria', '2019-02-14', '2023-11-30', NULL),
(2, 10, 'Ingeniero en Computacion', 2, 'Universidad Nacional de Ingenieria', '2019-02-12', '2023-12-13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblexamen`
--

CREATE TABLE `tblexamen` (
  `Codigo` int(11) NOT NULL,
  `RecetaPrevia` int(11) DEFAULT NULL,
  `CodPaciente` int(11) NOT NULL,
  `SalaMedica` tinyint(4) DEFAULT NULL,
  `MaquinariaMedica` tinyint(4) DEFAULT NULL,
  `EmpleadoRealizacion` int(11) DEFAULT NULL,
  `FechaYHora` datetime DEFAULT NULL,
  `idServicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblexamen`
--

INSERT INTO `tblexamen` (`Codigo`, `RecetaPrevia`, `CodPaciente`, `SalaMedica`, `MaquinariaMedica`, `EmpleadoRealizacion`, `FechaYHora`, `idServicio`) VALUES
(1, 1, 2, 1, 1, 3, '2022-10-20 01:47:00', 4),
(2, 2, 2, 1, 2, 3, '2022-10-11 01:47:00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblfactura`
--

CREATE TABLE `tblfactura` (
  `Codigo` int(11) NOT NULL,
  `MetodoDePago` tinyint(4) DEFAULT NULL,
  `Descuento` decimal(10,0) DEFAULT NULL,
  `EmpleadoCaja` int(11) DEFAULT NULL,
  `PagadoCon` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblfamiliares`
--

CREATE TABLE `tblfamiliares` (
  `ID` int(11) NOT NULL,
  `CodPersona` int(11) NOT NULL,
  `ContactoEmergencia` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblfamiliares`
--

INSERT INTO `tblfamiliares` (`ID`, `CodPersona`, `ContactoEmergencia`) VALUES
(55, 11, 127),
(60, 12, 0),
(75, 15, 127),
(80, 16, 0),
(85, 17, 127);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblhistorialacademico`
--

CREATE TABLE `tblhistorialacademico` (
  `ID` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `Nivel_academico` tinyint(4) NOT NULL,
  `FechaObtencion` date NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Estado` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblhistorialcargos`
--

CREATE TABLE `tblhistorialcargos` (
  `ID` int(11) NOT NULL,
  `CodEmpleado` int(11) NOT NULL,
  `IdCargo` tinyint(4) NOT NULL,
  `FechaAsignacion` date NOT NULL,
  `Salario` varchar(8) DEFAULT NULL,
  `Estado` tinyint(4) DEFAULT NULL,
  `RegistradoPor` int(11) NOT NULL,
  `AprobadoPor` int(11) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblhistorialcargos`
--

INSERT INTO `tblhistorialcargos` (`ID`, `CodEmpleado`, `IdCargo`, `FechaAsignacion`, `Salario`, `Estado`, `RegistradoPor`, `AprobadoPor`, `FechaRegistro`) VALUES
(1, 1, 7, '2022-09-30', '30000', 1, 1, 1, '2022-09-30 06:00:00'),
(2, 2, 5, '2022-09-30', '50000', 1, 1, 1, '2022-09-30 06:00:00'),
(3, 3, 2, '2022-09-30', '25000', 1, 1, 2, '2022-09-30 06:00:00'),
(4, 4, 3, '2022-09-30', '12000', 1, 1, 2, '2022-09-30 06:00:00'),
(5, 5, 4, '2022-09-30', '16000', 1, 1, 2, '2022-09-30 06:00:00'),
(6, 6, 1, '2022-09-30', '15000', 1, 1, 2, '2022-09-30 06:00:00'),
(7, 7, 6, '2022-09-30', '28000', 1, 1, 2, '2022-09-30 06:00:00'),
(8, 8, 8, '2022-09-30', '40000', 1, 1, 2, '2022-09-30 06:00:00'),
(9, 9, 7, '2022-10-11', '50000', 1, 1, 2, '2022-10-11 06:27:15'),
(10, 10, 5, '2022-10-11', '70000', 1, 2, 2, '2022-10-11 06:27:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbllotemedicamento`
--

CREATE TABLE `tbllotemedicamento` (
  `idLote` int(11) NOT NULL,
  `medicamento` int(11) NOT NULL,
  `fechaVence` date NOT NULL,
  `cantidadEnlote` int(11) NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbllotemedicamento`
--

INSERT INTO `tbllotemedicamento` (`idLote`, `medicamento`, `fechaVence`, `cantidadEnlote`, `fechaRegistro`) VALUES
(1, 1, '2026-07-31', 30, '2022-10-11 07:52:10'),
(2, 4, '2022-10-30', 50, '2022-10-11 06:40:11'),
(3, 3, '2022-12-11', 25, '2022-10-11 07:52:41'),
(4, 2, '2022-10-19', 35, '2022-10-11 07:52:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblmaquinariamedica`
--

CREATE TABLE `tblmaquinariamedica` (
  `Codigo` tinyint(4) NOT NULL,
  `tipoMaquina` tinyint(4) DEFAULT NULL,
  `marca` varchar(20) DEFAULT NULL,
  `modelo` varchar(20) DEFAULT NULL,
  `numeroDeSerie` varchar(20) DEFAULT NULL,
  `Fecha_de_compra` date DEFAULT NULL,
  `ubicacion` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblmedicamentoprecio`
--

CREATE TABLE `tblmedicamentoprecio` (
  `idMedicamentoPrecio` int(11) NOT NULL,
  `medicamento` int(11) NOT NULL,
  `precioVenta` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblmedicamentoprecio`
--

INSERT INTO `tblmedicamentoprecio` (`idMedicamentoPrecio`, `medicamento`, `precioVenta`) VALUES
(1, 1, 20),
(2, 2, 30),
(3, 3, 40),
(4, 4, 50),
(5, 5, 60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblmedicamentoproveedor`
--

CREATE TABLE `tblmedicamentoproveedor` (
  `idmedicamentoproveedor` int(11) NOT NULL,
  `medicamento` int(11) NOT NULL,
  `proveedor` int(11) NOT NULL,
  `laboratorio` int(11) NOT NULL,
  `precioMedicamento` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblmedicamentoproveedor`
--

INSERT INTO `tblmedicamentoproveedor` (`idmedicamentoproveedor`, `medicamento`, `proveedor`, `laboratorio`, `precioMedicamento`) VALUES
(1, 1, 2, 1, 10),
(2, 1, 3, 1, 10),
(3, 1, 4, 1, 10),
(4, 1, 1, 2, 15),
(5, 1, 2, 2, 15),
(6, 1, 3, 3, 8),
(7, 1, 4, 3, 8),
(8, 2, 1, 1, 12),
(9, 2, 2, 1, 12),
(10, 2, 1, 2, 11),
(11, 2, 3, 2, 11),
(12, 2, 4, 2, 11),
(13, 2, 4, 3, 14),
(14, 3, 1, 1, 5),
(15, 3, 2, 1, 5),
(16, 3, 1, 2, 7),
(17, 3, 2, 2, 7),
(18, 3, 3, 2, 7),
(19, 3, 4, 2, 7),
(20, 3, 1, 3, 6),
(21, 3, 3, 3, 6),
(22, 3, 4, 3, 6),
(23, 4, 4, 1, 15),
(24, 4, 1, 2, 17),
(25, 4, 3, 2, 17),
(26, 5, 1, 1, 20),
(27, 5, 4, 1, 20),
(28, 5, 1, 2, 25),
(29, 5, 2, 2, 25),
(30, 5, 3, 3, 18),
(31, 5, 4, 3, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblocupacionpacientes`
--

CREATE TABLE `tblocupacionpacientes` (
  `ID` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `Empresa` varchar(60) DEFAULT NULL,
  `Telefono` varchar(8) DEFAULT NULL,
  `Referencia` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblpaciente`
--

CREATE TABLE `tblpaciente` (
  `CodigoP` int(11) NOT NULL,
  `CodExpediente` varchar(10) NOT NULL,
  `INSS` int(11) NOT NULL,
  `GrupoSanguineo` tinyint(4) NOT NULL,
  `CodPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblpaciente`
--

INSERT INTO `tblpaciente` (`CodigoP`, `CodExpediente`, `INSS`, `GrupoSanguineo`, `CodPersona`) VALUES
(1, '130', 606060606, 3, 13),
(2, '140', 858585858, 3, 14),
(3, '180', 0, 8, 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblpersona`
--

CREATE TABLE `tblpersona` (
  `Codigo` int(11) NOT NULL,
  `Cedula` varchar(16) NOT NULL,
  `Nombres` varchar(60) NOT NULL,
  `Apellidos` varchar(60) NOT NULL,
  `Fecha_de_nacimiento` date NOT NULL,
  `Genero` tinyint(4) NOT NULL,
  `Estado_civil` tinyint(4) DEFAULT NULL,
  `Direccion` varchar(100) DEFAULT NULL,
  `Telefono` varchar(13) DEFAULT NULL,
  `Email` varchar(40) DEFAULT NULL,
  `Estado` tinyint(4) NOT NULL,
  `Fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblpersona`
--

INSERT INTO `tblpersona` (`Codigo`, `Cedula`, `Nombres`, `Apellidos`, `Fecha_de_nacimiento`, `Genero`, `Estado_civil`, `Direccion`, `Telefono`, `Email`, `Estado`, `Fecha_registro`) VALUES
(1, '001-091001-1001k', 'Steven David', 'Espinoza Ulloa', '2001-10-09', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '88145268', 'espinozasteven659@gmail.com', 1, '2022-09-30 06:00:00'),
(2, '001-091001-1001J', 'Luis Manuel', 'Matus Ramos', '2000-10-09', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '77024746', 'espinozasteven658@gmail.com', 1, '2022-09-30 06:00:00'),
(3, '001-091001-1001A', 'Enrique Jose', 'Muños Avellan', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '78514269', 'avellanenrique@gmail.com', 1, '2022-09-30 06:00:00'),
(4, '001-091001-1001B', 'Marcos Antonio', 'Duartes', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '79451236', 'duartesmarcos@gmail.com', 1, '2022-09-30 06:00:00'),
(5, '001-091001-1001C', 'Manuel Salvador', 'Espinoza Quiroz', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '85621436', 'espinozamanuel@gmail.com', 1, '2022-09-30 06:00:00'),
(6, '001-091001-1001D', 'Stayci yahoska', 'Ramirez Zeledon', '2000-10-18', 2, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '81247569', 'zeledonstayci@gmail.com', 1, '2022-09-30 06:00:00'),
(7, '001-091001-1001E', 'Felipe David', 'Treminio Moreno', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '72157863', 'morenofelipe@gmail.com', 1, '2022-09-30 06:00:00'),
(8, '001-091001-1001F', 'Juan Pablo', 'Hernandez Quiroz', '2001-10-09', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '88141463', 'hernandezjuan@gmail.com', 1, '2022-09-30 06:00:00'),
(9, '001-091001-1000K', 'Empleado Prueba', 'Nalga Negra', '1995-07-05', 1, 1, 'ADASASFASFFSS', '88145632', 'nalganegra@gmail.com', 1, '2022-10-11 06:06:53'),
(10, '001-091111-2222L', 'Empleado Prueba', 'Nalga Blanca', '1995-06-22', 1, 1, 'safafsaffgdasgsdgsgd', '88456321', 'nalgablanca@gmail.com', 1, '2022-10-11 06:06:36'),
(11, '001-092222-3333K', 'Familiar Prueba', 'Nalga Negra', '1993-06-17', 1, 1, 'DHFHDHDFHDFHDHFDHFHDFFH', '88456932', NULL, 1, '2022-10-11 06:20:06'),
(12, '001-095555-6666K', 'Familiar Prueba', 'Nalga Blanca', '1991-07-25', 1, 1, 'adafafasfasfasfaf', NULL, 'familiarnalgablanca@gmail.com', 1, '2022-10-11 06:19:34'),
(13, '001-095555-9999K', 'Paciente', 'Nalga Negra', '1993-03-26', 1, 1, 'SGSDGSDGSGSDGSDG', '88456932', 'pacientenalganegra@gmail.com', 1, '2022-10-11 06:43:19'),
(14, '001-091085-1095K', 'Paciente', 'Nalga Blanca', '1991-06-18', 1, 1, 'fddfdfdfgdfgdfgdfgd', '84652598', 'pacientenalgablanca@gmail.com', 1, '2022-10-11 06:44:18'),
(15, '001-091008-1009k', 'Familiar Paciente', 'Nalga Blanca', '1999-06-25', 1, 1, 'FSDFSDFSDFSDFSDFFSDF', '84563248', NULL, 1, '2022-10-11 06:50:24'),
(16, '001-091007-1006K', 'Familiar Paciente', 'Nalga Negra', '1992-07-30', 1, 1, 'afasfasfasfasfasf', NULL, 'fampacnalganegra@gmail.com', 1, '2022-10-11 06:51:24'),
(17, '001-091005-1006K', 'Responsable Pacientes', 'Nalga Multicolor', '1991-07-17', 1, 1, 'asdasfasfasfasfasfasf', '87965412', NULL, 1, '2022-10-11 06:59:02'),
(18, '2015-06-18', 'Paciente', 'Menor', '2015-06-18', 1, NULL, 'sdgsdgsdgsdg', NULL, NULL, 1, '2022-10-11 07:01:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblprivilegiosusuario`
--

CREATE TABLE `tblprivilegiosusuario` (
  `Codigo` int(11) NOT NULL,
  `CodUsuario` int(11) NOT NULL,
  `CodPrivilegio` int(11) NOT NULL,
  `CodigoSubModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblprivilegiosusuario`
--

INSERT INTO `tblprivilegiosusuario` (`Codigo`, `CodUsuario`, `CodPrivilegio`, `CodigoSubModulo`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 2),
(3, 1, 3, 2),
(4, 1, 1, 3),
(5, 1, 2, 3),
(6, 1, 3, 3),
(7, 1, 1, 6),
(8, 1, 2, 6),
(9, 1, 3, 6),
(10, 1, 1, 4),
(11, 1, 2, 4),
(12, 1, 3, 4),
(13, 1, 1, 5),
(14, 1, 2, 5),
(15, 1, 3, 5),
(16, 1, 1, 7),
(17, 1, 2, 7),
(18, 1, 3, 7),
(19, 1, 1, 21),
(20, 1, 2, 21),
(21, 1, 3, 21),
(22, 1, 1, 24),
(23, 1, 2, 24),
(24, 1, 1, 23),
(25, 1, 2, 23),
(26, 2, 1, 2),
(27, 2, 2, 2),
(28, 2, 3, 2),
(29, 2, 1, 3),
(30, 2, 2, 3),
(31, 2, 3, 3),
(32, 2, 1, 6),
(33, 2, 2, 6),
(34, 2, 3, 6),
(35, 2, 1, 4),
(36, 2, 2, 4),
(37, 2, 3, 4),
(38, 2, 1, 5),
(39, 2, 2, 5),
(40, 2, 3, 5),
(41, 2, 1, 7),
(42, 2, 2, 7),
(43, 2, 3, 7),
(44, 2, 2, 8),
(45, 2, 1, 21),
(46, 2, 2, 21),
(47, 2, 3, 21),
(48, 2, 2, 24),
(49, 3, 2, 12),
(50, 3, 3, 12),
(51, 3, 2, 8),
(52, 3, 2, 1),
(53, 3, 1, 13),
(54, 3, 2, 13),
(55, 3, 3, 13),
(56, 3, 1, 14),
(57, 3, 2, 14),
(58, 3, 3, 14),
(59, 3, 1, 15),
(60, 3, 2, 15),
(61, 3, 1, 16),
(62, 3, 2, 16),
(63, 3, 1, 17),
(64, 3, 2, 17),
(65, 3, 2, 18),
(66, 3, 2, 19),
(67, 4, 1, 22),
(68, 4, 2, 22),
(69, 4, 3, 22),
(70, 4, 2, 12),
(71, 4, 3, 12),
(72, 4, 1, 8),
(73, 4, 2, 8),
(74, 4, 3, 8),
(75, 4, 1, 1),
(76, 4, 2, 1),
(77, 4, 3, 1),
(78, 5, 2, 12),
(79, 5, 2, 8),
(80, 5, 2, 18),
(81, 5, 2, 19),
(82, 5, 1, 28),
(83, 5, 2, 28),
(84, 6, 2, 12),
(85, 6, 1, 13),
(86, 6, 2, 13),
(87, 6, 3, 13),
(88, 6, 2, 8),
(89, 6, 2, 22),
(90, 7, 1, 18),
(91, 7, 2, 18),
(92, 7, 3, 18),
(93, 7, 1, 19),
(94, 7, 2, 19),
(95, 7, 2, 8),
(96, 8, 1, 25),
(97, 8, 2, 25),
(98, 8, 1, 24),
(99, 8, 2, 24),
(100, 8, 1, 23),
(101, 8, 2, 23),
(102, 4, 1, 10),
(103, 4, 2, 10),
(104, 9, 2, 2),
(105, 9, 1, 2),
(106, 9, 3, 2),
(107, 9, 2, 2),
(108, 10, 2, 2),
(109, 10, 1, 2),
(110, 10, 3, 2),
(111, 10, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblproveedores`
--

CREATE TABLE `tblproveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombreProveedor` varchar(100) NOT NULL,
  `telefonoProveedor` varchar(13) NOT NULL,
  `direccionProveedor` varchar(255) NOT NULL,
  `emailProveedor` varchar(60) NOT NULL,
  `ranking` int(11) NOT NULL,
  `tiempoEntrega` int(11) NOT NULL,
  `estadoProveedor` int(11) NOT NULL,
  `descripcionProveedor` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblproveedores`
--

INSERT INTO `tblproveedores` (`idProveedor`, `nombreProveedor`, `telefonoProveedor`, `direccionProveedor`, `emailProveedor`, `ranking`, `tiempoEntrega`, `estadoProveedor`, `descripcionProveedor`) VALUES
(1, 'Medicamentos.S.A', '85746321', 'Ciudad jardin', 'medicamentossa@gmail.com', 3, 5, 1, ''),
(2, 'Pastillas.S.A', '84365219', 'Dorado', 'pastillassa@gmail.com', 2, 6, 1, ''),
(3, 'SaludForever.S.A', '86352469', 'Ciudad sandino', 'saludforeversa@gmail.com', 4, 7, 1, ''),
(4, 'BuenaVida', '77456982', 'Plaza españa', 'buenavidasa@gmail.com', 5, 4, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblrecetaexamen`
--

CREATE TABLE `tblrecetaexamen` (
  `Codigo` int(11) NOT NULL,
  `ConsultaPrevia` int(11) NOT NULL,
  `TipoExamen` tinyint(4) NOT NULL,
  `Motivo` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblrecetaexamen`
--

INSERT INTO `tblrecetaexamen` (`Codigo`, `ConsultaPrevia`, `TipoExamen`, `Motivo`) VALUES
(1, 2, 2, 'adasdasdasd'),
(2, 2, 4, 'adasdasdasd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblrecetamedicamentos`
--

CREATE TABLE `tblrecetamedicamentos` (
  `Codigo` int(11) NOT NULL,
  `CodigoConsulta` int(11) NOT NULL,
  `FechaEmision` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblrecetamedicamentos`
--

INSERT INTO `tblrecetamedicamentos` (`Codigo`, `CodigoConsulta`, `FechaEmision`) VALUES
(1, 3, '2022-10-11 07:42:32'),
(2, 1, '2022-10-11 07:45:49'),
(3, 2, '2022-10-11 07:46:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblrecibosventa`
--

CREATE TABLE `tblrecibosventa` (
  `idRecibo` int(11) NOT NULL,
  `Cliente` int(11) NOT NULL,
  `aperturaCaja` int(11) NOT NULL,
  `FyHRegistro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblrecibosventa`
--

INSERT INTO `tblrecibosventa` (`idRecibo`, `Cliente`, `aperturaCaja`, `FyHRegistro`) VALUES
(1, 1, 23, '2022-10-11 01:53:54'),
(2, 2, 23, '2022-10-11 01:54:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblrelacionpersonafamiliar`
--

CREATE TABLE `tblrelacionpersonafamiliar` (
  `ID` int(11) NOT NULL,
  `Codigo_Persona` int(11) NOT NULL,
  `Codigo_Familiar` int(11) NOT NULL,
  `ID_Parentesco` tinyint(4) NOT NULL,
  `Tutor` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblrelacionpersonafamiliar`
--

INSERT INTO `tblrelacionpersonafamiliar` (`ID`, `Codigo_Persona`, `Codigo_Familiar`, `ID_Parentesco`, `Tutor`) VALUES
(1, 9, 55, 1, b'0'),
(2, 10, 60, 1, b'0'),
(3, 14, 75, 1, b'1'),
(4, 13, 80, 1, b'1'),
(5, 18, 85, 1, b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblresultado`
--

CREATE TABLE `tblresultado` (
  `Codigo` int(11) NOT NULL,
  `CodExamen` int(11) NOT NULL,
  `ArchivoResultado` text DEFAULT NULL,
  `FechaYHora` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblresultado`
--

INSERT INTO `tblresultado` (`Codigo`, `CodExamen`, `ArchivoResultado`, `FechaYHora`) VALUES
(1, 1, 'ArchivosExamen/1665474498_94813.pdf', '2022-10-11'),
(2, 2, 'ArchivosExamen/1665474644_94813.pdf', '2022-10-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblserviciosbrindados`
--

CREATE TABLE `tblserviciosbrindados` (
  `idServiciosBrindados` int(11) NOT NULL,
  `tipoServicio` int(11) NOT NULL,
  `estadoServicio` int(11) NOT NULL,
  `MontoServicio` decimal(10,2) NOT NULL,
  `RebajaServicio` decimal(10,2) NOT NULL,
  `fechaYHora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblserviciosbrindados`
--

INSERT INTO `tblserviciosbrindados` (`idServiciosBrindados`, `tipoServicio`, `estadoServicio`, `MontoServicio`, `RebajaServicio`, `fechaYHora`) VALUES
(1, 2, 1, '300.00', '0.00', '2022-10-11 07:17:50'),
(2, 2, 3, '300.00', '0.00', '2022-10-11 07:22:10'),
(3, 2, 3, '300.00', '0.00', '2022-10-11 07:22:52'),
(4, 1, 3, '100.00', '0.00', '2022-10-11 07:47:42'),
(5, 1, 3, '50.00', '0.00', '2022-10-11 07:47:55'),
(6, 3, 3, '400.00', '0.00', '2022-10-11 07:52:10'),
(7, 3, 1, '450.00', '0.00', '2022-10-11 07:52:24'),
(8, 3, 3, '1000.00', '0.00', '2022-10-11 07:52:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsesion`
--

CREATE TABLE `tblsesion` (
  `idSesion` int(11) NOT NULL,
  `CodUsuarioSesion` int(11) NOT NULL,
  `EstadoSesion` tinyint(4) NOT NULL,
  `tokenSesion` varchar(200) DEFAULT NULL,
  `FechayHoraSesion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblsesion`
--

INSERT INTO `tblsesion` (`idSesion`, `CodUsuarioSesion`, `EstadoSesion`, `tokenSesion`, `FechayHoraSesion`) VALUES
(51, 7, 1, NULL, '2022-10-11 07:50:20'),
(52, 7, 2, NULL, '2022-10-11 07:51:34'),
(53, 8, 1, NULL, '2022-10-11 07:51:38'),
(54, 8, 2, NULL, '2022-10-11 07:53:13'),
(55, 5, 1, NULL, '2022-10-11 07:53:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsignosvitales`
--

CREATE TABLE `tblsignosvitales` (
  `Codigo` int(11) NOT NULL,
  `CodConsulta` int(11) NOT NULL,
  `Peso` decimal(6,0) DEFAULT NULL,
  `Altura` decimal(4,0) DEFAULT NULL,
  `Presion_Arterial` varchar(6) DEFAULT NULL,
  `Frecuencia_Respiratoria` varchar(6) DEFAULT NULL,
  `Frecuencia_Cardiaca` varchar(6) DEFAULT NULL,
  `Temperatura` decimal(6,0) DEFAULT NULL,
  `HoraRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CodEnfermera` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblsignosvitales`
--

INSERT INTO `tblsignosvitales` (`Codigo`, `CodConsulta`, `Peso`, `Altura`, `Presion_Arterial`, `Frecuencia_Respiratoria`, `Frecuencia_Cardiaca`, `Temperatura`, `HoraRegistro`, `CodEnfermera`) VALUES
(1, 3, '140', '175', '20', '20', '20', '20', '2022-10-11 07:32:25', 3),
(2, 1, '85', '140', '20', '20', '20', '20', '2022-10-11 07:36:13', 6),
(3, 2, '150', '20', '20', '20', '20', '20', '2022-10-11 07:38:09', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsintomasdiagnostico`
--

CREATE TABLE `tblsintomasdiagnostico` (
  `idSintomaDiagnostico` int(11) NOT NULL,
  `sintoma` int(11) NOT NULL,
  `diagnostico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblsintomasdiagnostico`
--

INSERT INTO `tblsintomasdiagnostico` (`idSintomaDiagnostico`, `sintoma`, `diagnostico`) VALUES
(1, 4, 1),
(2, 5, 1),
(3, 1, 1),
(4, 2, 1),
(5, 1, 2),
(6, 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblsolicitudcompra`
--

CREATE TABLE `tblsolicitudcompra` (
  `idSolicitudCompra` int(11) NOT NULL,
  `solicitante` int(11) NOT NULL,
  `estadoSolicitud` int(11) NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `descripcionSolicitud` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblsolicitudcompra`
--

INSERT INTO `tblsolicitudcompra` (`idSolicitudCompra`, `solicitante`, `estadoSolicitud`, `fechaRegistro`, `descripcionSolicitud`) VALUES
(1, 1, 6, '2022-10-11 06:39:09', 'Se necesita 50 omeprazol'),
(2, 1, 6, '2022-10-11 06:39:29', 'Se necesita 50 unidades de paracetamol'),
(3, 1, 6, '2022-10-11 06:39:21', 'Se necesita 50 unidades de acetaminofen y salbutamol');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbltipodecambio`
--

CREATE TABLE `tbltipodecambio` (
  `idCambio` int(11) NOT NULL,
  `Moneda` int(11) NOT NULL,
  `CambioReferencia` decimal(10,0) NOT NULL,
  `aperturaCaja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbltipodecambio`
--

INSERT INTO `tbltipodecambio` (`idCambio`, `Moneda`, `CambioReferencia`, `aperturaCaja`) VALUES
(1, 2, '36', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblusuarios`
--

CREATE TABLE `tblusuarios` (
  `Codigo` int(11) NOT NULL,
  `NombreUsuario` varchar(14) NOT NULL,
  `Pass` varchar(200) NOT NULL,
  `CodPersonaU` int(11) NOT NULL,
  `Estado` tinyint(4) DEFAULT NULL,
  `imgUsuario` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblusuarios`
--

INSERT INTO `tblusuarios` (`Codigo`, `NombreUsuario`, `Pass`, `CodPersonaU`, `Estado`, `imgUsuario`) VALUES
(1, 'ADMINISTRADOR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 1, 1, 'FotosReferencia/MaeViolento.jpeg'),
(2, 'GERENTE', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 2, 1, 'FotosReferencia/Luis.jpeg'),
(3, 'DOCTOR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 3, 1, 'FotosReferencia/Enrique.jpeg'),
(4, 'RECEPCIONISTA', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 4, 1, 'FotosReferencia/Marcos.jpeg'),
(5, 'Cajero', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 5, 1, 'FotosReferencia/Salva.jpeg'),
(6, 'ENFERMERA', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 6, 1, 'FotosReferencia/Enfermera.png'),
(7, 'DOCTORR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 7, 1, NULL),
(8, 'FARMACEUTA', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 8, 1, 'FotosReferencia/Farmaceuta.png'),
(9, 'NalgaBlanca', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 10, 1, ''),
(10, 'NalgaNegra', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 9, 1, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblventafarmacia`
--

CREATE TABLE `tblventafarmacia` (
  `idVentaFarmacia` int(11) NOT NULL,
  `recetaMedica` int(11) NOT NULL,
  `servicio` int(11) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `fechaVenta` date NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblventafarmacia`
--

INSERT INTO `tblventafarmacia` (`idVentaFarmacia`, `recetaMedica`, `servicio`, `descripcion`, `fechaVenta`, `fechaRegistro`) VALUES
(1, 1, 6, 'xxxxxxxxxx', '2022-10-11', '2022-10-11 07:52:10'),
(2, 2, 7, 'cccccccccccc', '2022-10-11', '2022-10-11 07:52:24'),
(3, 3, 8, 'sssssssssssss', '2022-10-11', '2022-10-11 07:52:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `catcaja`
--
ALTER TABLE `catcaja`
  ADD PRIMARY KEY (`idCaja`),
  ADD KEY `EstadoCaja` (`EstadoCaja`);

--
-- Indices de la tabla `catcargos`
--
ALTER TABLE `catcargos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catconsultorio`
--
ALTER TABLE `catconsultorio`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catenfermedades`
--
ALTER TABLE `catenfermedades`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catespecialidades`
--
ALTER TABLE `catespecialidades`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catestado`
--
ALTER TABLE `catestado`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catestadocita`
--
ALTER TABLE `catestadocita`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catestadocivil`
--
ALTER TABLE `catestadocivil`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catestadocompra`
--
ALTER TABLE `catestadocompra`
  ADD PRIMARY KEY (`idEstadoCompra`);

--
-- Indices de la tabla `catestadoconsulta`
--
ALTER TABLE `catestadoconsulta`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catestadoservicios`
--
ALTER TABLE `catestadoservicios`
  ADD PRIMARY KEY (`idEstadoServicio`);

--
-- Indices de la tabla `catestadosmedicos`
--
ALTER TABLE `catestadosmedicos`
  ADD PRIMARY KEY (`idEstadoMedico`);

--
-- Indices de la tabla `catexamenesmedicos`
--
ALTER TABLE `catexamenesmedicos`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catgenero`
--
ALTER TABLE `catgenero`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catgruposanguineo`
--
ALTER TABLE `catgruposanguineo`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catlaboratorio`
--
ALTER TABLE `catlaboratorio`
  ADD PRIMARY KEY (`idLaboratorio`);

--
-- Indices de la tabla `catmaquinaria`
--
ALTER TABLE `catmaquinaria`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catmedicamentos`
--
ALTER TABLE `catmedicamentos`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `catmetodosdepago`
--
ALTER TABLE `catmetodosdepago`
  ADD PRIMARY KEY (`idMetodoPago`);

--
-- Indices de la tabla `catmodulos`
--
ALTER TABLE `catmodulos`
  ADD PRIMARY KEY (`CodModulo`);

--
-- Indices de la tabla `catmoneda`
--
ALTER TABLE `catmoneda`
  ADD PRIMARY KEY (`idMoneda`),
  ADD KEY `EsReferencia` (`EsReferencia`);

--
-- Indices de la tabla `catnivelacademico`
--
ALTER TABLE `catnivelacademico`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catparentesco`
--
ALTER TABLE `catparentesco`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catprivilegio`
--
ALTER TABLE `catprivilegio`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `catsalaexamen`
--
ALTER TABLE `catsalaexamen`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `catservicios`
--
ALTER TABLE `catservicios`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indices de la tabla `catsintomas`
--
ALTER TABLE `catsintomas`
  ADD PRIMARY KEY (`idSintoma`),
  ADD KEY `estadoSintoma` (`estadoSintoma`);

--
-- Indices de la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD PRIMARY KEY (`CodSubModulo`),
  ADD KEY `CodigoModulo` (`CodigoModulo`);

--
-- Indices de la tabla `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `Enfermedad` (`Enfermedad`),
  ADD KEY `EstadoAntecedente` (`EstadoAntecedente`);

--
-- Indices de la tabla `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  ADD PRIMARY KEY (`idApertura`),
  ADD KEY `EmpleadoCaja` (`EmpleadoCaja`),
  ADD KEY `Caja` (`Caja`);

--
-- Indices de la tabla `tblasignacionlote`
--
ALTER TABLE `tblasignacionlote`
  ADD PRIMARY KEY (`idAsignacionLote`),
  ADD KEY `detSoliCompra` (`detSoliCompra`),
  ADD KEY `lote` (`lote`);

--
-- Indices de la tabla `tblcita`
--
ALTER TABLE `tblcita`
  ADD PRIMARY KEY (`IDCita`),
  ADD KEY `CodPaciente` (`CodPaciente`);

--
-- Indices de la tabla `tblclientes`
--
ALTER TABLE `tblclientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `CodPersona` (`CodPersona`);

--
-- Indices de la tabla `tblcompra`
--
ALTER TABLE `tblcompra`
  ADD PRIMARY KEY (`idCompra`),
  ADD KEY `estadoCompra` (`estadoCompra`);

--
-- Indices de la tabla `tblconstancia`
--
ALTER TABLE `tblconstancia`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodDiagnostico` (`CodDiagnostico`);

--
-- Indices de la tabla `tblconsulta`
--
ALTER TABLE `tblconsulta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodMedico` (`CodMedico`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `CodConsultorio` (`CodConsultorio`),
  ADD KEY `Estado` (`Estado`),
  ADD KEY `RegistradoPor` (`RegistradoPor`),
  ADD KEY `idServicio` (`idServicio`);

--
-- Indices de la tabla `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `Medicamento` (`Medicamento`),
  ADD KEY `CodReceta` (`CodReceta`);

--
-- Indices de la tabla `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  ADD PRIMARY KEY (`IDDetallecita`),
  ADD KEY `IdCita` (`IdCita`),
  ADD KEY `IdConsultorio` (`IdConsultorio`),
  ADD KEY `CodDoctor` (`CodDoctor`),
  ADD KEY `Estado` (`Estado`);

--
-- Indices de la tabla `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  ADD PRIMARY KEY (`idDetalleVentaFarmacia`),
  ADD KEY `ventaFarmacia` (`ventaFarmacia`),
  ADD KEY `detalleRecetaMedica` (`detalleRecetaMedica`);

--
-- Indices de la tabla `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `CodFactura` (`CodFactura`);

--
-- Indices de la tabla `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodExamen` (`CodExamen`),
  ADD KEY `CodFactura` (`CodFactura`);

--
-- Indices de la tabla `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  ADD PRIMARY KEY (`idDetPago`),
  ADD KEY `ServicioBrindado` (`ServicioBrindado`),
  ADD KEY `metodoDePago` (`metodoDePago`),
  ADD KEY `NumeroRecibo` (`NumeroRecibo`);

--
-- Indices de la tabla `tbldetsolicitudcompra`
--
ALTER TABLE `tbldetsolicitudcompra`
  ADD PRIMARY KEY (`idDetSolicitudCompra`),
  ADD KEY `solicitudCompra` (`solicitudCompra`),
  ADD KEY `medicamento` (`medicamento`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `laboratorio` (`laboratorio`);

--
-- Indices de la tabla `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `IdEnfermedad` (`IdEnfermedad`);

--
-- Indices de la tabla `tblempleado`
--
ALTER TABLE `tblempleado`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodPersona` (`CodPersona`);

--
-- Indices de la tabla `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodDoctor` (`CodDoctor`),
  ADD KEY `IDEspecialidad` (`IDEspecialidad`);

--
-- Indices de la tabla `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  ADD PRIMARY KEY (`IDEstudioAcademico`),
  ADD KEY `CodEmpleado` (`CodEmpleado`),
  ADD KEY `TipoEstudio` (`TipoEstudio`);

--
-- Indices de la tabla `tblexamen`
--
ALTER TABLE `tblexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `RecetaPrevia` (`RecetaPrevia`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `SalaMedica` (`SalaMedica`),
  ADD KEY `EmpleadoRealizacion` (`EmpleadoRealizacion`),
  ADD KEY `idServicio` (`idServicio`);

--
-- Indices de la tabla `tblfactura`
--
ALTER TABLE `tblfactura`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPersona` (`CodPersona`);

--
-- Indices de la tabla `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `Nivel_academico` (`Nivel_academico`),
  ADD KEY `Estado` (`Estado`);

--
-- Indices de la tabla `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodEmpleado` (`CodEmpleado`),
  ADD KEY `IdCargo` (`IdCargo`),
  ADD KEY `Estado` (`Estado`),
  ADD KEY `RegistradoPor` (`RegistradoPor`),
  ADD KEY `AprobadoPor` (`AprobadoPor`);

--
-- Indices de la tabla `tbllotemedicamento`
--
ALTER TABLE `tbllotemedicamento`
  ADD PRIMARY KEY (`idLote`),
  ADD KEY `medicamento` (`medicamento`);

--
-- Indices de la tabla `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `tipoMaquina` (`tipoMaquina`),
  ADD KEY `ubicacion` (`ubicacion`);

--
-- Indices de la tabla `tblmedicamentoprecio`
--
ALTER TABLE `tblmedicamentoprecio`
  ADD PRIMARY KEY (`idMedicamentoPrecio`),
  ADD KEY `medicamento` (`medicamento`);

--
-- Indices de la tabla `tblmedicamentoproveedor`
--
ALTER TABLE `tblmedicamentoproveedor`
  ADD PRIMARY KEY (`idmedicamentoproveedor`),
  ADD KEY `medicamento` (`medicamento`),
  ADD KEY `proveedor` (`proveedor`),
  ADD KEY `laboratorio` (`laboratorio`);

--
-- Indices de la tabla `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`);

--
-- Indices de la tabla `tblpaciente`
--
ALTER TABLE `tblpaciente`
  ADD PRIMARY KEY (`CodigoP`),
  ADD KEY `CodPersona` (`CodPersona`),
  ADD KEY `GrupoSanguineo` (`GrupoSanguineo`);

--
-- Indices de la tabla `tblpersona`
--
ALTER TABLE `tblpersona`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `Genero` (`Genero`),
  ADD KEY `Estado_civil` (`Estado_civil`),
  ADD KEY `Estado` (`Estado`);

--
-- Indices de la tabla `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodPrivilegio` (`CodPrivilegio`),
  ADD KEY `CodUsuario` (`CodUsuario`),
  ADD KEY `CodigoSubModulo` (`CodigoSubModulo`);

--
-- Indices de la tabla `tblproveedores`
--
ALTER TABLE `tblproveedores`
  ADD PRIMARY KEY (`idProveedor`),
  ADD KEY `estadoProveedor` (`estadoProveedor`);

--
-- Indices de la tabla `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `TipoExamen` (`TipoExamen`),
  ADD KEY `ConsultaPrevia` (`ConsultaPrevia`);

--
-- Indices de la tabla `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodigoConsulta` (`CodigoConsulta`);

--
-- Indices de la tabla `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  ADD PRIMARY KEY (`idRecibo`),
  ADD KEY `Cliente` (`Cliente`),
  ADD KEY `aperturaCaja` (`aperturaCaja`);

--
-- Indices de la tabla `tblrelacionpersonafamiliar`
--
ALTER TABLE `tblrelacionpersonafamiliar`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Codigo_Persona` (`Codigo_Persona`),
  ADD KEY `Codigo_Familiar` (`Codigo_Familiar`),
  ADD KEY `ID_Parentesco` (`ID_Parentesco`);

--
-- Indices de la tabla `tblresultado`
--
ALTER TABLE `tblresultado`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodExamen` (`CodExamen`);

--
-- Indices de la tabla `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  ADD PRIMARY KEY (`idServiciosBrindados`),
  ADD KEY `tipoServicio` (`tipoServicio`),
  ADD KEY `estadoServicio` (`estadoServicio`);

--
-- Indices de la tabla `tblsesion`
--
ALTER TABLE `tblsesion`
  ADD PRIMARY KEY (`idSesion`),
  ADD KEY `CodUsuarioSesion` (`CodUsuarioSesion`),
  ADD KEY `EstadoSesion` (`EstadoSesion`);

--
-- Indices de la tabla `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `CodEnfermera` (`CodEnfermera`);

--
-- Indices de la tabla `tblsintomasdiagnostico`
--
ALTER TABLE `tblsintomasdiagnostico`
  ADD PRIMARY KEY (`idSintomaDiagnostico`),
  ADD KEY `sintoma` (`sintoma`),
  ADD KEY `diagnostico` (`diagnostico`);

--
-- Indices de la tabla `tblsolicitudcompra`
--
ALTER TABLE `tblsolicitudcompra`
  ADD PRIMARY KEY (`idSolicitudCompra`),
  ADD KEY `solicitante` (`solicitante`),
  ADD KEY `estadoSolicitud` (`estadoSolicitud`);

--
-- Indices de la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD PRIMARY KEY (`idCambio`),
  ADD KEY `aperturaCaja` (`aperturaCaja`);

--
-- Indices de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodPersonaU` (`CodPersonaU`),
  ADD KEY `Estado` (`Estado`);

--
-- Indices de la tabla `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  ADD PRIMARY KEY (`idVentaFarmacia`),
  ADD KEY `recetaMedica` (`recetaMedica`),
  ADD KEY `servicio` (`servicio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `catcaja`
--
ALTER TABLE `catcaja`
  MODIFY `idCaja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catcargos`
--
ALTER TABLE `catcargos`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `catconsultorio`
--
ALTER TABLE `catconsultorio`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catenfermedades`
--
ALTER TABLE `catenfermedades`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `catespecialidades`
--
ALTER TABLE `catespecialidades`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `catestado`
--
ALTER TABLE `catestado`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `catestadocita`
--
ALTER TABLE `catestadocita`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catestadocivil`
--
ALTER TABLE `catestadocivil`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catestadocompra`
--
ALTER TABLE `catestadocompra`
  MODIFY `idEstadoCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `catestadoconsulta`
--
ALTER TABLE `catestadoconsulta`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `catestadoservicios`
--
ALTER TABLE `catestadoservicios`
  MODIFY `idEstadoServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catestadosmedicos`
--
ALTER TABLE `catestadosmedicos`
  MODIFY `idEstadoMedico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catexamenesmedicos`
--
ALTER TABLE `catexamenesmedicos`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `catgenero`
--
ALTER TABLE `catgenero`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catgruposanguineo`
--
ALTER TABLE `catgruposanguineo`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `catlaboratorio`
--
ALTER TABLE `catlaboratorio`
  MODIFY `idLaboratorio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catmaquinaria`
--
ALTER TABLE `catmaquinaria`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catmedicamentos`
--
ALTER TABLE `catmedicamentos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `catmetodosdepago`
--
ALTER TABLE `catmetodosdepago`
  MODIFY `idMetodoPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `catmodulos`
--
ALTER TABLE `catmodulos`
  MODIFY `CodModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `catmoneda`
--
ALTER TABLE `catmoneda`
  MODIFY `idMoneda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catnivelacademico`
--
ALTER TABLE `catnivelacademico`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `catparentesco`
--
ALTER TABLE `catparentesco`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catprivilegio`
--
ALTER TABLE `catprivilegio`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catsalaexamen`
--
ALTER TABLE `catsalaexamen`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `catservicios`
--
ALTER TABLE `catservicios`
  MODIFY `idServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `catsintomas`
--
ALTER TABLE `catsintomas`
  MODIFY `idSintoma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  MODIFY `CodSubModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  MODIFY `idApertura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tblasignacionlote`
--
ALTER TABLE `tblasignacionlote`
  MODIFY `idAsignacionLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tblcita`
--
ALTER TABLE `tblcita`
  MODIFY `IDCita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tblclientes`
--
ALTER TABLE `tblclientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblcompra`
--
ALTER TABLE `tblcompra`
  MODIFY `idCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tblconstancia`
--
ALTER TABLE `tblconstancia`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tblconsulta`
--
ALTER TABLE `tblconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  MODIFY `IDDetallecita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  MODIFY `idDetalleVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  MODIFY `idDetPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbldetsolicitudcompra`
--
ALTER TABLE `tbldetsolicitudcompra`
  MODIFY `idDetSolicitudCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tblempleado`
--
ALTER TABLE `tblempleado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  MODIFY `IDEstudioAcademico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblexamen`
--
ALTER TABLE `tblexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblfactura`
--
ALTER TABLE `tblfactura`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT de la tabla `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbllotemedicamento`
--
ALTER TABLE `tbllotemedicamento`
  MODIFY `idLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tblmedicamentoprecio`
--
ALTER TABLE `tblmedicamentoprecio`
  MODIFY `idMedicamentoPrecio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tblmedicamentoproveedor`
--
ALTER TABLE `tblmedicamentoproveedor`
  MODIFY `idmedicamentoproveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblpaciente`
--
ALTER TABLE `tblpaciente`
  MODIFY `CodigoP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `tblproveedores`
--
ALTER TABLE `tblproveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  MODIFY `idRecibo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblrelacionpersonafamiliar`
--
ALTER TABLE `tblrelacionpersonafamiliar`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tblresultado`
--
ALTER TABLE `tblresultado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  MODIFY `idServiciosBrindados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tblsesion`
--
ALTER TABLE `tblsesion`
  MODIFY `idSesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tblsintomasdiagnostico`
--
ALTER TABLE `tblsintomasdiagnostico`
  MODIFY `idSintomaDiagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tblsolicitudcompra`
--
ALTER TABLE `tblsolicitudcompra`
  MODIFY `idSolicitudCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  MODIFY `idCambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  MODIFY `idVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `catcaja`
--
ALTER TABLE `catcaja`
  ADD CONSTRAINT `catcaja_ibfk_1` FOREIGN KEY (`EstadoCaja`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `catmoneda`
--
ALTER TABLE `catmoneda`
  ADD CONSTRAINT `catmoneda_ibfk_1` FOREIGN KEY (`EsReferencia`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `catsintomas`
--
ALTER TABLE `catsintomas`
  ADD CONSTRAINT `catsintomas_ibfk_1` FOREIGN KEY (`estadoSintoma`) REFERENCES `catestadosmedicos` (`idEstadoMedico`);

--
-- Filtros para la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD CONSTRAINT `catsubmodulos_ibfk_1` FOREIGN KEY (`CodigoModulo`) REFERENCES `catmodulos` (`CodModulo`);

--
-- Filtros para la tabla `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  ADD CONSTRAINT `tblantecedentes_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblantecedentes_ibfk_2` FOREIGN KEY (`Enfermedad`) REFERENCES `catenfermedades` (`ID`),
  ADD CONSTRAINT `tblantecedentes_ibfk_3` FOREIGN KEY (`EstadoAntecedente`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  ADD CONSTRAINT `tblaperturacaja_ibfk_1` FOREIGN KEY (`EmpleadoCaja`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblaperturacaja_ibfk_2` FOREIGN KEY (`Caja`) REFERENCES `catcaja` (`idCaja`);

--
-- Filtros para la tabla `tblasignacionlote`
--
ALTER TABLE `tblasignacionlote`
  ADD CONSTRAINT `tblasignacionlote_ibfk_1` FOREIGN KEY (`detSoliCompra`) REFERENCES `tbldetsolicitudcompra` (`idDetSolicitudCompra`),
  ADD CONSTRAINT `tblasignacionlote_ibfk_2` FOREIGN KEY (`lote`) REFERENCES `tbllotemedicamento` (`idLote`);

--
-- Filtros para la tabla `tblcita`
--
ALTER TABLE `tblcita`
  ADD CONSTRAINT `tblcita_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`);

--
-- Filtros para la tabla `tblclientes`
--
ALTER TABLE `tblclientes`
  ADD CONSTRAINT `tblclientes_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`);

--
-- Filtros para la tabla `tblcompra`
--
ALTER TABLE `tblcompra`
  ADD CONSTRAINT `tblcompra_ibfk_1` FOREIGN KEY (`estadoCompra`) REFERENCES `catestadocompra` (`idEstadoCompra`);

--
-- Filtros para la tabla `tblconstancia`
--
ALTER TABLE `tblconstancia`
  ADD CONSTRAINT `tblconstancia_ibfk_1` FOREIGN KEY (`CodDiagnostico`) REFERENCES `tbldiagnosticoconsulta` (`Codigo`);

--
-- Filtros para la tabla `tblconsulta`
--
ALTER TABLE `tblconsulta`
  ADD CONSTRAINT `tblconsulta_ibfk_1` FOREIGN KEY (`CodMedico`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblconsulta_ibfk_2` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblconsulta_ibfk_3` FOREIGN KEY (`CodConsultorio`) REFERENCES `catconsultorio` (`ID`),
  ADD CONSTRAINT `tblconsulta_ibfk_4` FOREIGN KEY (`Estado`) REFERENCES `catestadoconsulta` (`ID`),
  ADD CONSTRAINT `tblconsulta_ibfk_5` FOREIGN KEY (`RegistradoPor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblconsulta_ibfk_6` FOREIGN KEY (`idServicio`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`);

--
-- Filtros para la tabla `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  ADD CONSTRAINT `tbldetallereceta_ibfk_1` FOREIGN KEY (`Medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tbldetallereceta_ibfk_2` FOREIGN KEY (`CodReceta`) REFERENCES `tblrecetamedicamentos` (`Codigo`);

--
-- Filtros para la tabla `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  ADD CONSTRAINT `tbldetallesdecita_ibfk_1` FOREIGN KEY (`IdCita`) REFERENCES `tblcita` (`IDCita`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_2` FOREIGN KEY (`IdConsultorio`) REFERENCES `catconsultorio` (`ID`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_3` FOREIGN KEY (`CodDoctor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_4` FOREIGN KEY (`Estado`) REFERENCES `catestadocita` (`ID`);

--
-- Filtros para la tabla `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_1` FOREIGN KEY (`ventaFarmacia`) REFERENCES `tblventafarmacia` (`idVentaFarmacia`),
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_2` FOREIGN KEY (`detalleRecetaMedica`) REFERENCES `tbldetallereceta` (`Codigo`);

--
-- Filtros para la tabla `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  ADD CONSTRAINT `tbldetfactconsulta_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tbldetfactconsulta_ibfk_2` FOREIGN KEY (`CodFactura`) REFERENCES `tblfactura` (`Codigo`);

--
-- Filtros para la tabla `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  ADD CONSTRAINT `tbldetfactexamen_ibfk_1` FOREIGN KEY (`CodExamen`) REFERENCES `tblexamen` (`Codigo`),
  ADD CONSTRAINT `tbldetfactexamen_ibfk_2` FOREIGN KEY (`CodFactura`) REFERENCES `tblfactura` (`Codigo`);

--
-- Filtros para la tabla `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  ADD CONSTRAINT `tbldetpagoservicios_ibfk_1` FOREIGN KEY (`ServicioBrindado`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`),
  ADD CONSTRAINT `tbldetpagoservicios_ibfk_2` FOREIGN KEY (`metodoDePago`) REFERENCES `catmetodosdepago` (`idMetodoPago`),
  ADD CONSTRAINT `tbldetpagoservicios_ibfk_3` FOREIGN KEY (`NumeroRecibo`) REFERENCES `tblrecibosventa` (`idRecibo`);

--
-- Filtros para la tabla `tbldetsolicitudcompra`
--
ALTER TABLE `tbldetsolicitudcompra`
  ADD CONSTRAINT `tbldetsolicitudcompra_ibfk_1` FOREIGN KEY (`solicitudCompra`) REFERENCES `tblsolicitudcompra` (`idSolicitudCompra`),
  ADD CONSTRAINT `tbldetsolicitudcompra_ibfk_2` FOREIGN KEY (`medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tbldetsolicitudcompra_ibfk_3` FOREIGN KEY (`proveedor`) REFERENCES `tblproveedores` (`idProveedor`),
  ADD CONSTRAINT `tbldetsolicitudcompra_ibfk_4` FOREIGN KEY (`laboratorio`) REFERENCES `catlaboratorio` (`idLaboratorio`);

--
-- Filtros para la tabla `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  ADD CONSTRAINT `tbldiagnosticoconsulta_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tbldiagnosticoconsulta_ibfk_2` FOREIGN KEY (`IdEnfermedad`) REFERENCES `catenfermedades` (`ID`);

--
-- Filtros para la tabla `tblempleado`
--
ALTER TABLE `tblempleado`
  ADD CONSTRAINT `tblempleado_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`);

--
-- Filtros para la tabla `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  ADD CONSTRAINT `tblespecialidad_ibfk_1` FOREIGN KEY (`CodDoctor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblespecialidad_ibfk_2` FOREIGN KEY (`IDEspecialidad`) REFERENCES `catespecialidades` (`ID`);

--
-- Filtros para la tabla `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  ADD CONSTRAINT `tblestudioacademico_ibfk_1` FOREIGN KEY (`CodEmpleado`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblestudioacademico_ibfk_2` FOREIGN KEY (`TipoEstudio`) REFERENCES `catnivelacademico` (`ID`);

--
-- Filtros para la tabla `tblexamen`
--
ALTER TABLE `tblexamen`
  ADD CONSTRAINT `tblexamen_ibfk_1` FOREIGN KEY (`RecetaPrevia`) REFERENCES `tblrecetaexamen` (`Codigo`),
  ADD CONSTRAINT `tblexamen_ibfk_2` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblexamen_ibfk_3` FOREIGN KEY (`SalaMedica`) REFERENCES `catsalaexamen` (`ID`),
  ADD CONSTRAINT `tblexamen_ibfk_4` FOREIGN KEY (`EmpleadoRealizacion`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblexamen_ibfk_5` FOREIGN KEY (`idServicio`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`);

--
-- Filtros para la tabla `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  ADD CONSTRAINT `tblfamiliares_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`);

--
-- Filtros para la tabla `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  ADD CONSTRAINT `tblhistorialacademico_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblhistorialacademico_ibfk_2` FOREIGN KEY (`Nivel_academico`) REFERENCES `catnivelacademico` (`ID`),
  ADD CONSTRAINT `tblhistorialacademico_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  ADD CONSTRAINT `tblhistorialcargos_ibfk_1` FOREIGN KEY (`CodEmpleado`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_2` FOREIGN KEY (`IdCargo`) REFERENCES `catcargos` (`ID`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_4` FOREIGN KEY (`RegistradoPor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_5` FOREIGN KEY (`AprobadoPor`) REFERENCES `tblempleado` (`Codigo`);

--
-- Filtros para la tabla `tbllotemedicamento`
--
ALTER TABLE `tbllotemedicamento`
  ADD CONSTRAINT `tbllotemedicamento_ibfk_1` FOREIGN KEY (`medicamento`) REFERENCES `catmedicamentos` (`Codigo`);

--
-- Filtros para la tabla `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_1` FOREIGN KEY (`tipoMaquina`) REFERENCES `catmaquinaria` (`ID`),
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_2` FOREIGN KEY (`ubicacion`) REFERENCES `catsalaexamen` (`ID`);

--
-- Filtros para la tabla `tblmedicamentoprecio`
--
ALTER TABLE `tblmedicamentoprecio`
  ADD CONSTRAINT `tblmedicamentoprecio_ibfk_1` FOREIGN KEY (`medicamento`) REFERENCES `catmedicamentos` (`Codigo`);

--
-- Filtros para la tabla `tblmedicamentoproveedor`
--
ALTER TABLE `tblmedicamentoproveedor`
  ADD CONSTRAINT `tblmedicamentoproveedor_ibfk_1` FOREIGN KEY (`medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tblmedicamentoproveedor_ibfk_2` FOREIGN KEY (`proveedor`) REFERENCES `tblproveedores` (`idProveedor`),
  ADD CONSTRAINT `tblmedicamentoproveedor_ibfk_3` FOREIGN KEY (`laboratorio`) REFERENCES `catlaboratorio` (`idLaboratorio`);

--
-- Filtros para la tabla `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  ADD CONSTRAINT `tblocupacionpacientes_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`);

--
-- Filtros para la tabla `tblpaciente`
--
ALTER TABLE `tblpaciente`
  ADD CONSTRAINT `tblpaciente_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblpaciente_ibfk_2` FOREIGN KEY (`GrupoSanguineo`) REFERENCES `catgruposanguineo` (`ID`);

--
-- Filtros para la tabla `tblpersona`
--
ALTER TABLE `tblpersona`
  ADD CONSTRAINT `tblpersona_ibfk_1` FOREIGN KEY (`Genero`) REFERENCES `catgenero` (`ID`),
  ADD CONSTRAINT `tblpersona_ibfk_2` FOREIGN KEY (`Estado_civil`) REFERENCES `catestadocivil` (`ID`),
  ADD CONSTRAINT `tblpersona_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_1` FOREIGN KEY (`CodPrivilegio`) REFERENCES `catprivilegio` (`Codigo`),
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_2` FOREIGN KEY (`CodUsuario`) REFERENCES `tblusuarios` (`Codigo`),
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_3` FOREIGN KEY (`CodigoSubModulo`) REFERENCES `catsubmodulos` (`CodSubModulo`);

--
-- Filtros para la tabla `tblproveedores`
--
ALTER TABLE `tblproveedores`
  ADD CONSTRAINT `tblproveedores_ibfk_1` FOREIGN KEY (`estadoProveedor`) REFERENCES `catestadocompra` (`idEstadoCompra`);

--
-- Filtros para la tabla `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  ADD CONSTRAINT `tblrecetaexamen_ibfk_1` FOREIGN KEY (`TipoExamen`) REFERENCES `catexamenesmedicos` (`ID`),
  ADD CONSTRAINT `tblrecetaexamen_ibfk_2` FOREIGN KEY (`ConsultaPrevia`) REFERENCES `tblconsulta` (`Codigo`);

--
-- Filtros para la tabla `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  ADD CONSTRAINT `tblrecetamedicamentos_ibfk_1` FOREIGN KEY (`CodigoConsulta`) REFERENCES `tblconsulta` (`Codigo`);

--
-- Filtros para la tabla `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  ADD CONSTRAINT `tblrecibosventa_ibfk_1` FOREIGN KEY (`Cliente`) REFERENCES `tblclientes` (`idCliente`),
  ADD CONSTRAINT `tblrecibosventa_ibfk_2` FOREIGN KEY (`aperturaCaja`) REFERENCES `tblaperturacaja` (`idApertura`);

--
-- Filtros para la tabla `tblrelacionpersonafamiliar`
--
ALTER TABLE `tblrelacionpersonafamiliar`
  ADD CONSTRAINT `tblrelacionpersonafamiliar_ibfk_1` FOREIGN KEY (`Codigo_Persona`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblrelacionpersonafamiliar_ibfk_2` FOREIGN KEY (`Codigo_Familiar`) REFERENCES `tblfamiliares` (`ID`),
  ADD CONSTRAINT `tblrelacionpersonafamiliar_ibfk_3` FOREIGN KEY (`ID_Parentesco`) REFERENCES `catparentesco` (`ID`);

--
-- Filtros para la tabla `tblresultado`
--
ALTER TABLE `tblresultado`
  ADD CONSTRAINT `tblresultado_ibfk_1` FOREIGN KEY (`CodExamen`) REFERENCES `tblexamen` (`Codigo`);

--
-- Filtros para la tabla `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  ADD CONSTRAINT `tblserviciosbrindados_ibfk_1` FOREIGN KEY (`tipoServicio`) REFERENCES `catservicios` (`idServicio`),
  ADD CONSTRAINT `tblserviciosbrindados_ibfk_2` FOREIGN KEY (`estadoServicio`) REFERENCES `catestadoservicios` (`idEstadoServicio`);

--
-- Filtros para la tabla `tblsesion`
--
ALTER TABLE `tblsesion`
  ADD CONSTRAINT `tblsesion_ibfk_1` FOREIGN KEY (`CodUsuarioSesion`) REFERENCES `tblusuarios` (`Codigo`),
  ADD CONSTRAINT `tblsesion_ibfk_2` FOREIGN KEY (`EstadoSesion`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  ADD CONSTRAINT `tblsignosvitales_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tblsignosvitales_ibfk_2` FOREIGN KEY (`CodEnfermera`) REFERENCES `tblempleado` (`Codigo`);

--
-- Filtros para la tabla `tblsintomasdiagnostico`
--
ALTER TABLE `tblsintomasdiagnostico`
  ADD CONSTRAINT `tblsintomasdiagnostico_ibfk_1` FOREIGN KEY (`sintoma`) REFERENCES `catsintomas` (`idSintoma`),
  ADD CONSTRAINT `tblsintomasdiagnostico_ibfk_2` FOREIGN KEY (`diagnostico`) REFERENCES `tbldiagnosticoconsulta` (`Codigo`);

--
-- Filtros para la tabla `tblsolicitudcompra`
--
ALTER TABLE `tblsolicitudcompra`
  ADD CONSTRAINT `tblsolicitudcompra_ibfk_1` FOREIGN KEY (`solicitante`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblsolicitudcompra_ibfk_2` FOREIGN KEY (`estadoSolicitud`) REFERENCES `catestadocompra` (`idEstadoCompra`);

--
-- Filtros para la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD CONSTRAINT `tbltipodecambio_ibfk_1` FOREIGN KEY (`aperturaCaja`) REFERENCES `tblaperturacaja` (`idApertura`);

--
-- Filtros para la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD CONSTRAINT `tblusuarios_ibfk_1` FOREIGN KEY (`CodPersonaU`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblusuarios_ibfk_2` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Filtros para la tabla `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  ADD CONSTRAINT `tblventafarmacia_ibfk_1` FOREIGN KEY (`recetaMedica`) REFERENCES `tblrecetamedicamentos` (`Codigo`),
  ADD CONSTRAINT `tblventafarmacia_ibfk_2` FOREIGN KEY (`servicio`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



