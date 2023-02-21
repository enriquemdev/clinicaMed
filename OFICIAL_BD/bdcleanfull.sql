-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20220916.b9499b51c8
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-02-2023 a las 04:08:24
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.5

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
(2, 'Caja 2', 'Segunda caja', 2),
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
(1, 'Enfermera', 'Profesional', '2022-05-24 06:00:00'),
(2, 'Doctor', 'Profesional', '2022-05-24 06:00:00'),
(3, 'Recepcionista', 'Profesional', '2022-05-24 06:00:00'),
(4, 'Cajero', 'Profesional', '2022-05-24 06:00:00'),
(5, 'Gerente', 'Profesional', '2022-05-24 06:00:00'),
(6, 'Doctor radiologico', 'Profesional', '2022-05-24 06:00:00'),
(7, 'Administrador', 'Profesional', '2022-05-24 06:00:00');

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
(1, 'Consultorio 1', 'Sala de consulta', '2022-05-24 06:00:00'),
(2, 'Consultorio 2', 'Sala de consulta', '2022-05-24 06:00:00');

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
(1, 'Bronquitis aguda', 'Enfermedad comun', 'Respiratoria', '2022-05-24 06:00:00'),
(2, 'Resfriado común', 'Enfermedad comun', 'Respiratoria', '2022-05-24 06:00:00'),
(3, 'Influenza', 'Enfermedad comun', 'Respiratoria', '2022-05-24 06:00:00'),
(4, 'COVID 19', 'Enfermedad mortal', 'Respiratoria', '2022-05-24 06:00:00'),
(5, 'VIH', 'Enfermedad mortal', 'ITS', '2022-05-24 06:00:00'),
(6, 'SIDA', 'Enfermedad mortal', 'ITS', '2022-05-24 06:00:00'),
(7, 'Sifilis', 'Enfermedad mortal', 'ITS', '2022-05-24 06:00:00'),
(8, 'Gonorrea', 'Enfermedad mortal', 'ITS', '2022-05-24 06:00:00'),
(9, 'Evola', 'Enfermedad mortal', 'Respiratoria', '2022-05-24 06:00:00'),
(10, 'Dengue', 'Enfermedad mortal', 'Febril', '2022-05-24 06:00:00');

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
(1, 'Pediatria', 'Especialista', '2022-05-24 06:00:00'),
(2, 'Nutriologia', 'Especialista', '2022-05-24 06:00:00'),
(3, 'Cardiologia', 'Especialista', '2022-05-24 06:00:00'),
(4, 'Gastroenterología', 'Especialista', '2022-05-24 06:00:00'),
(5, 'Rinoplastia', 'Especialista', '2022-05-24 06:00:00');

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
  `NombreComercial` varchar(40) DEFAULT NULL,
  `NombreGenerico` varchar(40) DEFAULT NULL,
  `Formula` varchar(100) DEFAULT NULL,
  `Presentacion` varchar(40) NOT NULL,
  `stockMinimo` int(11) NOT NULL,
  `precioUnidad` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmedicamentos`
--

INSERT INTO `catmedicamentos` (`Codigo`, `NombreComercial`, `NombreGenerico`, `Formula`, `Presentacion`, `stockMinimo`, `precioUnidad`) VALUES
(1, 'Omeprazol', 'Omeprazol', '', '', 50, '10'),
(2, 'Paracetamol', 'Paracetamol', '', '', 50, '5'),
(3, 'Acetaminofen', 'Acetaminofen', '', '', 50, '20'),
(4, 'Salbutamol', 'Salbutamol', '', '', 50, '15'),
(5, 'Ketotifeno', 'Ketotifeno', '', '', 50, '30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `catmetodosdepago`
--

CREATE TABLE `catmetodosdepago` (
  `idMetodoPago` int(11) NOT NULL,
  `NombreMetodoPago` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `catmetodosdepago`
--

INSERT INTO `catmetodosdepago` (`idMetodoPago`, `NombreMetodoPago`, `Descripcion`) VALUES
(1, 'Contado', 'Pago en efectivo'),
(3, 'Targeta bancaria', 'Pago por medio de targeta bancaria'),
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
-- Estructura de tabla para la tabla `loteproductos`
--

CREATE TABLE `loteproductos` (
  `idLote` int(11) NOT NULL,
  `DetalleDeCompra` int(11) NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `CantidadRestante` int(11) NOT NULL,
  `Nota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `loteproductos`
--

INSERT INTO `loteproductos` (`idLote`, `DetalleDeCompra`, `FechaVencimiento`, `CantidadRestante`, `Nota`) VALUES
(1, 1, '2022-12-12', 100, NULL),
(2, 2, '2022-12-12', 50, NULL),
(3, 3, '2022-12-12', 50, NULL),
(4, 4, '2022-12-12', 20, NULL),
(5, 5, '2022-12-12', 10, NULL);

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
(1, 1, '100000.00', 5, '2022-05-24 00:00:00', NULL, NULL),
(5, 1, '2000.00', 5, '2022-09-22 23:25:59', NULL, '0A-00-27-00-00-12'),
(6, 1, '0.00', 5, '2022-09-23 21:58:58', '2022-09-23 23:21:30', '0A-00-27-00-00-12'),
(7, 1, '0.00', 5, '2022-09-23 23:24:13', '2022-09-23 23:24:29', '0A-00-27-00-00-12'),
(8, 1, '0.00', 5, '2022-09-23 23:33:54', '2022-09-23 23:34:00', '0A-00-27-00-00-12'),
(9, 1, '0.00', 5, '2022-09-23 23:36:03', '2022-09-23 23:36:09', '0A-00-27-00-00-12'),
(10, 1, '0.00', 5, '2022-09-23 23:38:56', '2022-09-23 23:39:58', '0A-00-27-00-00-12'),
(11, 1, '0.00', 5, '2022-09-23 23:40:05', '2022-09-23 23:41:16', '0A-00-27-00-00-12'),
(12, 1, '0.00', 5, '2022-09-23 23:41:25', '2022-09-23 23:42:29', '0A-00-27-00-00-12'),
(13, 1, '0.00', 5, '2022-09-23 23:42:36', '2022-09-23 23:42:39', '0A-00-27-00-00-12'),
(14, 1, '1000.00', 5, '2022-09-24 09:51:41', '2022-09-25 16:36:57', '0A-00-27-00-00-12'),
(15, 1, '0.00', 5, '2022-09-25 16:39:29', '2022-09-25 16:47:45', '0A-00-27-00-00-12'),
(16, 1, '0.00', 5, '2022-09-25 16:51:45', '2022-09-25 17:15:12', '0A-00-27-00-00-12'),
(17, 1, '0.00', 5, '2022-09-25 17:15:58', '2022-09-25 17:16:00', '0A-00-27-00-00-12'),
(18, 1, '0.00', 5, '2022-09-25 17:43:15', '2022-09-25 22:45:05', '0A-00-27-00-00-12'),
(19, 2, '0.00', 5, '2022-09-25 22:45:14', '2022-09-26 21:49:43', '0A-00-27-00-00-12'),
(20, 3, '0.00', 5, '2022-09-26 21:49:52', '2022-09-26 21:54:43', '0A-00-27-00-00-12'),
(21, 2, '0.00', 5, '2022-09-26 21:54:48', '2022-09-27 22:17:16', '0A-00-27-00-00-12'),
(22, 1, '3000.00', 5, '2022-09-27 22:18:59', '2022-09-27 22:29:29', '0A-00-27-00-00-12'),
(23, 2, '0.00', 5, '2022-09-27 22:33:24', '2022-09-28 14:18:47', '0A-00-27-00-00-12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblcita`
--

CREATE TABLE `tblcita` (
  `IDCita` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `fechaProgramada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(1, 10, '2022-09-26 01:32:46'),
(2, 8, '2022-09-27 05:26:28'),
(3, 9, '2022-09-27 06:23:56'),
(4, 3, '2022-09-27 07:08:39'),
(5, 1, '2022-09-28 04:05:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblcomprasfarmacia`
--

CREATE TABLE `tblcomprasfarmacia` (
  `idCompras` int(11) NOT NULL,
  `Proveedor` int(11) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `fechaCompra` date NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblcomprasfarmacia`
--

INSERT INTO `tblcomprasfarmacia` (`idCompras`, `Proveedor`, `Descripcion`, `fechaCompra`, `FechaRegistro`) VALUES
(1, 1, 'Se compro Omeprazol', '2021-12-12', '2021-12-12 06:00:00'),
(2, 1, 'Se compro Paracetamol', '2021-12-12', '2021-12-12 06:00:00'),
(3, 1, 'Se compro Acetaminofen', '2021-12-12', '2021-12-12 06:00:00'),
(4, 1, 'Se compro Salbutamol', '2021-12-12', '2021-12-12 06:00:00'),
(5, 1, 'Se compro Ketotifeno', '2021-12-12', '2021-12-12 06:00:00');

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
(1, 3, 0, 1, 1, 5, '2021-01-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Motivo', 4, 1, '', ''),
(4, 3, 0, 1, 1, 5, '2022-05-30 12:58:42', '2022-05-30 13:27:22', NULL, '', 4, 6, 'sobre', NULL),
(5, 3, 0, 2, 1, 5, '2022-05-30 13:46:01', '2022-05-30 14:11:10', NULL, '', 4, 7, 'Cambios que funcionan', NULL),
(6, 3, 0, 1, 1, 3, '2022-09-25 13:22:38', NULL, NULL, 'si', 4, 9, NULL, NULL);

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
(1, 1, '10', '100', 1),
(2, 2, '10', '100', 2),
(3, 3, '10', '100', 3),
(4, 4, '10', '100', 4),
(5, 5, '10', '100', 5);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetalleventafarmacia`
--

CREATE TABLE `tbldetalleventafarmacia` (
  `idDetalleVentaFarmacia` int(11) NOT NULL,
  `idVentaFarmacia` int(11) NOT NULL,
  `idMedicamento` int(11) NOT NULL,
  `cantidadVendida` int(11) NOT NULL,
  `nota` varchar(150) DEFAULT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idDetalleReceta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetalleventafarmacia`
--

INSERT INTO `tbldetalleventafarmacia` (`idDetalleVentaFarmacia`, `idVentaFarmacia`, `idMedicamento`, `cantidadVendida`, `nota`, `fechaRegistro`, `idDetalleReceta`) VALUES
(1, 1, 1, 10, 'Todo bien', '2022-05-29 06:00:00', 1),
(2, 2, 2, 10, 'Todo bien', '2022-05-29 06:00:00', 2),
(3, 3, 3, 10, 'Todo bien', '2022-05-29 06:00:00', 3),
(4, 4, 4, 10, 'Todo bien', '2022-05-29 06:00:00', 4),
(5, 5, 5, 10, 'Todo bien', '2022-05-29 06:00:00', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldetcomprasfarmacia`
--

CREATE TABLE `tbldetcomprasfarmacia` (
  `idDetalleCompra` int(11) NOT NULL,
  `Medicamento` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `CostoUnidad` double NOT NULL,
  `CodigoCompra` int(11) NOT NULL,
  `Notas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldetcomprasfarmacia`
--

INSERT INTO `tbldetcomprasfarmacia` (`idDetalleCompra`, `Medicamento`, `Cantidad`, `CostoUnidad`, `CodigoCompra`, `Notas`) VALUES
(1, 1, 100, 10, 1, 'Todo bien'),
(2, 2, 50, 15, 2, 'Todo bien'),
(3, 3, 50, 20, 3, 'Todo bien'),
(4, 4, 20, 25, 4, 'Todo bien'),
(5, 5, 10, 30, 5, 'Todo bien');

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
(42, 9, '300.00', '0.00', 1, 34),
(43, 6, '300.00', '0.00', 1, 34);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbldiagnosticoconsulta`
--

CREATE TABLE `tbldiagnosticoconsulta` (
  `Codigo` int(11) NOT NULL,
  `Sintoma` varchar(20) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `IdEnfermedad` tinyint(4) DEFAULT NULL,
  `CodConsulta` int(11) DEFAULT NULL,
  `Nota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbldiagnosticoconsulta`
--

INSERT INTO `tbldiagnosticoconsulta` (`Codigo`, `Sintoma`, `Descripcion`, `IdEnfermedad`, `CodConsulta`, `Nota`) VALUES
(1, 'Tos y flema', '', 1, 1, ''),
(3, 'Calentura', 'Todo se derrumbó', 4, 4, 'Dentro de ti'),
(4, 'Calentura', 'Sisisisis', 6, 5, 'Aproximadamente');

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
(1, '111111111', '2022-05-24 06:00:00', 1),
(2, '222222222', '2022-05-24 06:00:00', 2),
(3, '333333333', '2022-05-24 06:00:00', 3),
(4, '444444444', '2022-05-24 06:00:00', 4),
(5, '555555555', '2022-05-24 06:00:00', 5),
(6, '666666666', '2022-05-24 06:00:00', 6),
(7, '777777777', '2022-05-24 06:00:00', 7);

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
(1, NULL, 1, 1, NULL, 4, '2022-06-14 23:32:33', 8);

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
  `FamiliarDe` int(11) NOT NULL,
  `Parentesco` tinyint(4) NOT NULL,
  `EsTutor` tinyint(4) NOT NULL,
  `ContactoEmergencia` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(1, 1, 7, '2022-05-24', '30000', 1, 1, 1, '2022-05-24 06:00:00'),
(2, 2, 5, '2022-05-24', '50000', 1, 1, 1, '2022-05-24 06:00:00'),
(3, 3, 2, '2022-05-24', '25000', 1, 1, 2, '2022-05-24 06:00:00'),
(4, 4, 3, '2022-05-24', '12000', 1, 1, 2, '2022-05-24 06:00:00'),
(5, 5, 4, '2022-05-24', '16000', 1, 1, 2, '2022-05-24 06:00:00'),
(6, 6, 1, '2022-05-24', '15000', 1, 1, 2, '2022-05-24 06:00:00'),
(7, 7, 6, '2022-05-24', '28000', 1, 1, 2, '2022-05-24 06:00:00');

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
-- Estructura de tabla para la tabla `tblmovimientolote`
--

CREATE TABLE `tblmovimientolote` (
  `idMovimientoLote` int(11) NOT NULL,
  `idDetalleVenta` int(11) NOT NULL,
  `idLote` int(11) NOT NULL,
  `unidades` int(11) NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblmovimientolote`
--

INSERT INTO `tblmovimientolote` (`idMovimientoLote`, `idDetalleVenta`, `idLote`, `unidades`, `fechaRegistro`) VALUES
(1, 1, 1, 10, '2022-01-01 06:00:00'),
(2, 2, 2, 10, '2022-01-01 06:00:00'),
(3, 3, 3, 10, '2022-01-01 06:00:00'),
(4, 4, 4, 10, '2022-01-01 06:00:00'),
(5, 5, 5, 10, '2022-01-01 06:00:00');

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
(1, '80', 123123132, 4, 8),
(2, '90', 188888888, 5, 9);

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
(1, '001-091001-1001k', 'Steven David', 'Espinoza Ulloa', '2001-10-09', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '88145268', 'espinozasteven659@gmail.com', 1, '2022-05-24 06:00:00'),
(2, '001-091001-1001J', 'Luis Manuel', 'Matus Ramos', '2000-10-09', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '77024746', 'espinozasteven658@gmail.com', 1, '2022-05-24 06:00:00'),
(3, '001-091001-1001A', 'Enrique Jose', 'Muños Avellan', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '78514269', 'avellanenrique@gmail.com', 1, '2022-05-24 06:00:00'),
(4, '001-091001-1001B', 'Marcos Antonio', 'Duartes', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '79451236', 'duartesmarcos@gmail.com', 1, '2022-05-24 06:00:00'),
(5, '001-091001-1001C', 'Manuel Salvador', 'Espinoza Quiroz', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '85621436', 'espinozamanuel@gmail.com', 1, '2022-05-24 06:00:00'),
(6, '001-091001-1001D', 'Stayci yahoska', 'Ramirez Zeledon', '2000-10-18', 2, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '81247569', 'zeledonstayci@gmail.com', 1, '2022-05-24 06:00:00'),
(7, '001-091001-1001E', 'Felipe David', 'Treminio Moreno', '2000-10-18', 1, 1, 'Bo.Jorge Dimitrov. Del colegio primero de junio 20 vrs al este MI.', '72157863', 'morenofelipe@gmail.com', 1, '2022-05-24 06:00:00'),
(8, '1211212312312312', 'Ejemplo', 'Uno', '2000-05-12', 1, 1, 'KEHBAEFUH', '1231231231312', 'KUSBEFUWIF@GMAIL.COM', 1, '2022-05-25 00:21:55'),
(9, '121-121299-1000V', 'Ejemplo', 'Dos', '1999-12-12', 1, 1, 'Managua Nicaragua', '182818281', 'sobreton@gmail.com', 1, '2022-05-28 18:50:56'),
(10, '561-160602-1003K', 'Jose Roberto', 'Emblema', '2001-07-19', 1, 1, 'Cerca de su hogar', '89997451', 'joserobert@gmail.com', 1, '2022-09-26 01:32:46');

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
(22, 1, 2, 23),
(23, 1, 1, 24),
(24, 1, 2, 24),
(25, 1, 3, 24),
(26, 1, 1, 25),
(27, 1, 2, 25),
(28, 1, 3, 25),
(29, 1, 1, 26),
(30, 1, 2, 26),
(31, 1, 3, 26),
(32, 1, 1, 27),
(33, 1, 2, 27),
(34, 1, 3, 27),
(35, 2, 1, 2),
(36, 2, 2, 2),
(37, 2, 3, 2),
(38, 2, 1, 3),
(39, 2, 2, 3),
(40, 2, 3, 3),
(41, 2, 1, 6),
(42, 2, 2, 6),
(43, 2, 3, 6),
(44, 2, 1, 4),
(45, 2, 2, 4),
(46, 2, 3, 4),
(47, 2, 1, 5),
(48, 2, 2, 5),
(49, 2, 3, 5),
(50, 2, 1, 7),
(51, 2, 2, 7),
(52, 2, 3, 7),
(53, 2, 2, 8),
(54, 2, 1, 21),
(55, 2, 2, 21),
(56, 2, 3, 21),
(57, 3, 2, 12),
(58, 3, 3, 12),
(59, 3, 2, 8),
(60, 3, 2, 1),
(61, 3, 1, 13),
(62, 3, 2, 13),
(63, 3, 3, 13),
(64, 3, 1, 14),
(65, 3, 2, 14),
(66, 3, 3, 14),
(67, 3, 1, 15),
(68, 3, 2, 15),
(69, 3, 1, 16),
(70, 3, 2, 16),
(71, 3, 1, 17),
(72, 3, 2, 17),
(73, 3, 2, 18),
(74, 3, 2, 19),
(75, 4, 1, 22),
(76, 4, 2, 22),
(77, 4, 3, 22),
(78, 4, 2, 12),
(79, 4, 3, 12),
(80, 4, 1, 8),
(81, 4, 2, 8),
(82, 4, 3, 8),
(83, 4, 1, 1),
(84, 4, 2, 1),
(85, 4, 3, 1),
(86, 5, 2, 12),
(87, 5, 2, 8),
(88, 5, 2, 18),
(89, 5, 2, 19),
(90, 6, 2, 12),
(91, 6, 1, 13),
(92, 6, 2, 13),
(93, 6, 3, 13),
(94, 6, 2, 8),
(95, 6, 2, 22),
(96, 7, 1, 18),
(97, 7, 2, 18),
(98, 7, 3, 18),
(99, 7, 1, 19),
(100, 7, 2, 19),
(101, 7, 2, 8),
(102, 5, 1, 28),
(103, 5, 2, 28);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblproveedores`
--

CREATE TABLE `tblproveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombreProvedor` varchar(100) NOT NULL,
  `TelefonoProveedor` varchar(13) NOT NULL,
  `DireccionProvedor` varchar(255) DEFAULT NULL,
  `EmailProveedor` varchar(60) DEFAULT NULL,
  `EstadoProveedor` tinyint(4) NOT NULL,
  `Ranking` int(11) DEFAULT NULL,
  `Fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `leadtime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblproveedores`
--

INSERT INTO `tblproveedores` (`idProveedor`, `nombreProvedor`, `TelefonoProveedor`, `DireccionProvedor`, `EmailProveedor`, `EstadoProveedor`, `Ranking`, `Fecha_registro`, `leadtime`) VALUES
(1, 'Medicamentos.S.A', '85746321', 'Ciudad jardin', 'medicamentossa@gmail.com', 1, 5, '2022-05-24 06:00:00', 30),
(2, 'Proveedor2', '12371371231', 'no este jodiendo', 'puntocom@com', 1, 2, '2022-05-25 00:36:56', 20),
(3, 'Proveedorestrella', '18317238123', 'ksjgbkjehaoifiehf', 'liwehgowfhiohefiwhf@gmail.com', 1, 1, '2022-05-25 00:37:48', 2),
(4, 'Proveedor4', '7133723132', 'managua', 'adjsdbajkd@gmail.com', 1, 4, '2022-05-25 00:39:01', 1);

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
(1, 1, '2021-01-12 06:00:00'),
(2, 1, '2021-02-12 06:00:00'),
(3, 1, '2021-03-12 06:00:00'),
(4, 1, '2021-04-12 06:00:00'),
(5, 1, '2021-05-12 06:00:00');

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
(34, 2, 23, '2022-09-28 13:07:34');

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
(1, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(2, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(3, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(4, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(5, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(6, 2, 3, '300.00', '0.00', '2022-09-25 20:11:19'),
(7, 2, 1, '300.00', '0.00', '2022-09-25 20:11:19'),
(8, 1, 1, '500.00', '0.00', '2022-09-24 16:54:23'),
(9, 2, 3, '300.00', '0.00', '2022-09-25 19:22:38');

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
(93, 5, 2, NULL, '2022-09-28 05:25:14'),
(94, 5, 1, NULL, '2022-09-28 16:58:51'),
(95, 5, 2, NULL, '2022-09-28 17:39:12'),
(96, 5, 1, NULL, '2022-09-28 18:52:14'),
(97, 5, 2, NULL, '2022-09-29 01:01:40'),
(98, 4, 1, NULL, '2022-09-29 01:01:49'),
(99, 4, 2, NULL, '2022-09-29 01:01:55'),
(100, 4, 1, NULL, '2022-10-20 03:05:02'),
(101, 4, 2, NULL, '2022-10-20 03:05:02'),
(102, 1, 1, NULL, '2022-11-03 23:42:46'),
(103, 1, 2, NULL, '2022-11-03 23:51:18'),
(104, 3, 1, NULL, '2023-02-17 04:51:44');

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
(6, 4, '190', '190', '190', '190', '190', '40', '2022-05-30 19:24:41', 3),
(7, 5, '190', '190', '190', '190', '190', '40', '2022-05-30 19:47:35', 3);

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
(1, 2, '36', 1);

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
(1, 'ADMINISTRADOR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 1, 1, 'FotosReferencia/1651552993_Violento.jpeg'),
(2, 'GERENTE', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 2, 1, 'FotosReferencia/1651553144_Luis.jpeg'),
(3, 'DOCTOR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 3, 1, 'FotosReferencia/1651553113_Enrique.jpeg'),
(4, 'RECEPCIONISTA', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 4, 1, 'FotosReferencia/1651553170_Marcos.jpeg'),
(5, 'Cajero', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 5, 1, 'FotosReferencia/1651553190_Salva.jpeg'),
(6, 'ENFERMERA', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 6, 1, NULL),
(7, 'DOCTORR', 'NjBmZHlXY2YweHVuSUg3MVkrME0zdz09', 7, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tblventafarmacia`
--

CREATE TABLE `tblventafarmacia` (
  `idVentaFarmacia` int(11) NOT NULL,
  `idRecetaMedica` int(11) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `fechaVenta` date NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tblventafarmacia`
--

INSERT INTO `tblventafarmacia` (`idVentaFarmacia`, `idRecetaMedica`, `descripcion`, `fechaVenta`, `fechaRegistro`) VALUES
(1, 1, 'Venta', '2021-01-12', '2022-05-29 06:00:00'),
(2, 2, 'Venta', '2022-02-12', '2022-05-29 06:00:00'),
(3, 3, 'Venta', '2022-03-12', '2022-05-29 06:00:00'),
(4, 4, 'Venta', '2022-04-12', '2022-05-29 06:00:00'),
(5, 5, 'Venta', '2022-05-12', '2022-05-29 06:00:00');

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
-- Indices de la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD PRIMARY KEY (`CodSubModulo`),
  ADD KEY `CodigoModulo` (`CodigoModulo`);

--
-- Indices de la tabla `loteproductos`
--
ALTER TABLE `loteproductos`
  ADD PRIMARY KEY (`idLote`),
  ADD KEY `DetalleDeCompra` (`DetalleDeCompra`);

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
-- Indices de la tabla `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  ADD PRIMARY KEY (`idCompras`),
  ADD KEY `Proveedor` (`Proveedor`);

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
  ADD KEY `idVentaFarmacia` (`idVentaFarmacia`),
  ADD KEY `idMedicamento` (`idMedicamento`);

--
-- Indices de la tabla `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  ADD PRIMARY KEY (`idDetalleCompra`),
  ADD KEY `Medicamento` (`Medicamento`),
  ADD KEY `CodigoCompra` (`CodigoCompra`);

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
  ADD KEY `tbldetpagoservicios_ibfk_3` (`NumeroRecibo`);

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
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `MetodoDePago` (`MetodoDePago`),
  ADD KEY `EmpleadoCaja` (`EmpleadoCaja`);

--
-- Indices de la tabla `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPersona` (`CodPersona`),
  ADD KEY `FamiliarDe` (`FamiliarDe`),
  ADD KEY `Parentesco` (`Parentesco`);

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
-- Indices de la tabla `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `tipoMaquina` (`tipoMaquina`),
  ADD KEY `ubicacion` (`ubicacion`);

--
-- Indices de la tabla `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  ADD PRIMARY KEY (`idMovimientoLote`),
  ADD KEY `idDetalleVenta` (`idDetalleVenta`),
  ADD KEY `idLote` (`idLote`);

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
  ADD UNIQUE KEY `Cedula` (`Cedula`),
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
  ADD KEY `EstadoProveedor` (`EstadoProveedor`);

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
-- Indices de la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD PRIMARY KEY (`idCambio`),
  ADD KEY `aperturaCaja` (`aperturaCaja`),
  ADD KEY `Moneda` (`Moneda`);

--
-- Indices de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `NombreUsuario` (`NombreUsuario`),
  ADD UNIQUE KEY `CodPersonaU` (`CodPersonaU`),
  ADD KEY `Estado` (`Estado`);

--
-- Indices de la tabla `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  ADD PRIMARY KEY (`idVentaFarmacia`),
  ADD KEY `idRecetaMedica` (`idRecetaMedica`);

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
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `idMoneda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT de la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  MODIFY `CodSubModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `loteproductos`
--
ALTER TABLE `loteproductos`
  MODIFY `idLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
-- AUTO_INCREMENT de la tabla `tblcita`
--
ALTER TABLE `tblcita`
  MODIFY `IDCita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblclientes`
--
ALTER TABLE `tblclientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  MODIFY `idCompras` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tblconstancia`
--
ALTER TABLE `tblconstancia`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblconsulta`
--
ALTER TABLE `tblconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  MODIFY `IDDetallecita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  MODIFY `idDetalleVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  MODIFY `idDetalleCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `idDetPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tblempleado`
--
ALTER TABLE `tblempleado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  MODIFY `IDEstudioAcademico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblexamen`
--
ALTER TABLE `tblexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tblfactura`
--
ALTER TABLE `tblfactura`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  MODIFY `idMovimientoLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblpaciente`
--
ALTER TABLE `tblpaciente`
  MODIFY `CodigoP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `tblproveedores`
--
ALTER TABLE `tblproveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  MODIFY `idRecibo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `tblresultado`
--
ALTER TABLE `tblresultado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  MODIFY `idServiciosBrindados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tblsesion`
--
ALTER TABLE `tblsesion`
  MODIFY `idSesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT de la tabla `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  MODIFY `idCambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  MODIFY `idVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Filtros para la tabla `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD CONSTRAINT `catsubmodulos_ibfk_1` FOREIGN KEY (`CodigoModulo`) REFERENCES `catmodulos` (`CodModulo`);

--
-- Filtros para la tabla `loteproductos`
--
ALTER TABLE `loteproductos`
  ADD CONSTRAINT `loteproductos_ibfk_1` FOREIGN KEY (`DetalleDeCompra`) REFERENCES `tbldetcomprasfarmacia` (`idDetalleCompra`);

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
-- Filtros para la tabla `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  ADD CONSTRAINT `tblcomprasfarmacia_ibfk_1` FOREIGN KEY (`Proveedor`) REFERENCES `tblproveedores` (`idProveedor`);

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
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_1` FOREIGN KEY (`idVentaFarmacia`) REFERENCES `tblventafarmacia` (`idVentaFarmacia`),
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_2` FOREIGN KEY (`idMedicamento`) REFERENCES `catmedicamentos` (`Codigo`);

--
-- Filtros para la tabla `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  ADD CONSTRAINT `tbldetcomprasfarmacia_ibfk_1` FOREIGN KEY (`Medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tbldetcomprasfarmacia_ibfk_2` FOREIGN KEY (`CodigoCompra`) REFERENCES `tblcomprasfarmacia` (`idCompras`);

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
  ADD CONSTRAINT `tblfamiliares_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblfamiliares_ibfk_2` FOREIGN KEY (`FamiliarDe`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblfamiliares_ibfk_3` FOREIGN KEY (`Parentesco`) REFERENCES `catparentesco` (`ID`);

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
-- Filtros para la tabla `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_1` FOREIGN KEY (`tipoMaquina`) REFERENCES `catmaquinaria` (`ID`),
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_2` FOREIGN KEY (`ubicacion`) REFERENCES `catsalaexamen` (`ID`);

--
-- Filtros para la tabla `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  ADD CONSTRAINT `tblmovimientolote_ibfk_1` FOREIGN KEY (`idDetalleVenta`) REFERENCES `tbldetalleventafarmacia` (`idDetalleVentaFarmacia`),
  ADD CONSTRAINT `tblmovimientolote_ibfk_2` FOREIGN KEY (`idLote`) REFERENCES `loteproductos` (`idLote`);

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
  ADD CONSTRAINT `tblproveedores_ibfk_1` FOREIGN KEY (`EstadoProveedor`) REFERENCES `catestado` (`ID`);

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
-- Filtros para la tabla `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD CONSTRAINT `tbltipodecambio_ibfk_1` FOREIGN KEY (`aperturaCaja`) REFERENCES `tblaperturacaja` (`idApertura`),
  ADD CONSTRAINT `tbltipodecambio_ibfk_2` FOREIGN KEY (`Moneda`) REFERENCES `catmoneda` (`idMoneda`);

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
  ADD CONSTRAINT `tblventafarmacia_ibfk_1` FOREIGN KEY (`idRecetaMedica`) REFERENCES `tblrecetamedicamentos` (`Codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
