-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2022 at 08:14 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdcleanfull`
--

DELIMITER $$
--
-- Procedures
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
-- Table structure for table `catcaja`
--

CREATE TABLE `catcaja` (
  `idCaja` int(11) NOT NULL,
  `nombreCaja` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catcaja`
--

INSERT INTO `catcaja` (`idCaja`, `nombreCaja`, `Descripcion`) VALUES
(1, 'Caja ClinicaMedica', 'Casa de clinica medica');

-- --------------------------------------------------------

--
-- Table structure for table `catcargos`
--

CREATE TABLE `catcargos` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catcargos`
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
-- Table structure for table `catconsultorio`
--

CREATE TABLE `catconsultorio` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catconsultorio`
--

INSERT INTO `catconsultorio` (`ID`, `Nombre`, `Descripcion`, `FechaRegistro`) VALUES
(1, 'Consultorio 1', 'Sala de consulta', '2022-05-24 06:00:00'),
(2, 'Consultorio 2', 'Sala de consulta', '2022-05-24 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `catenfermedades`
--

CREATE TABLE `catenfermedades` (
  `ID` tinyint(4) NOT NULL,
  `NombreEnfermedad` varchar(40) NOT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `TipoEnfermedad` varchar(30) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catenfermedades`
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
-- Table structure for table `catespecialidades`
--

CREATE TABLE `catespecialidades` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(80) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catespecialidades`
--

INSERT INTO `catespecialidades` (`ID`, `Nombre`, `Descripcion`, `FechaRegistro`) VALUES
(1, 'Pediatria', 'Especialista', '2022-05-24 06:00:00'),
(2, 'Nutriologia', 'Especialista', '2022-05-24 06:00:00'),
(3, 'Cardiologia', 'Especialista', '2022-05-24 06:00:00'),
(4, 'Gastroenterología', 'Especialista', '2022-05-24 06:00:00'),
(5, 'Rinoplastia', 'Especialista', '2022-05-24 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `catestado`
--

CREATE TABLE `catestado` (
  `ID` tinyint(4) NOT NULL,
  `NombreEstado` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catestado`
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
-- Table structure for table `catestadocita`
--

CREATE TABLE `catestadocita` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catestadocita`
--

INSERT INTO `catestadocita` (`ID`, `Nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Table structure for table `catestadocivil`
--

CREATE TABLE `catestadocivil` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catestadocivil`
--

INSERT INTO `catestadocivil` (`ID`, `Nombre`) VALUES
(1, 'Solter@'),
(2, 'Casad@');

-- --------------------------------------------------------

--
-- Table structure for table `catestadoconsulta`
--

CREATE TABLE `catestadoconsulta` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catestadoconsulta`
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
-- Table structure for table `catestadoservicios`
--

CREATE TABLE `catestadoservicios` (
  `idEstadoServicio` int(11) NOT NULL,
  `nombreEstadoServicio` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catestadoservicios`
--

INSERT INTO `catestadoservicios` (`idEstadoServicio`, `nombreEstadoServicio`, `Descripcion`) VALUES
(1, 'Activo', 'Servicio activo'),
(2, 'Inactivo', 'Servicio inactivo');

-- --------------------------------------------------------

--
-- Table structure for table `catexamenesmedicos`
--

CREATE TABLE `catexamenesmedicos` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Precio` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catexamenesmedicos`
--

INSERT INTO `catexamenesmedicos` (`ID`, `Nombre`, `Precio`) VALUES
(1, 'Radiografia', '300'),
(2, 'Sangre general', '100'),
(3, 'Tomografía', '250'),
(4, 'Orina', '50');

-- --------------------------------------------------------

--
-- Table structure for table `catgenero`
--

CREATE TABLE `catgenero` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catgenero`
--

INSERT INTO `catgenero` (`ID`, `Nombre`) VALUES
(1, 'M'),
(2, 'F');

-- --------------------------------------------------------

--
-- Table structure for table `catgruposanguineo`
--

CREATE TABLE `catgruposanguineo` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catgruposanguineo`
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
-- Table structure for table `catmaquinaria`
--

CREATE TABLE `catmaquinaria` (
  `ID` tinyint(4) NOT NULL,
  `NombreMaquinaria` varchar(80) DEFAULT NULL,
  `Descripcion` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catmaquinaria`
--

INSERT INTO `catmaquinaria` (`ID`, `NombreMaquinaria`, `Descripcion`) VALUES
(1, 'Esterilizadores', 'Equipo medico'),
(2, 'Desfibriladores', 'Equipo medico');

-- --------------------------------------------------------

--
-- Table structure for table `catmedicamentos`
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
-- Dumping data for table `catmedicamentos`
--

INSERT INTO `catmedicamentos` (`Codigo`, `NombreComercial`, `NombreGenerico`, `Formula`, `Presentacion`, `stockMinimo`, `precioUnidad`) VALUES
(1, 'Omeprazol', 'Omeprazol', '', '', 50, '10'),
(2, 'Paracetamol', 'Paracetamol', '', '', 50, '5'),
(3, 'Acetaminofen', 'Acetaminofen', '', '', 50, '20'),
(4, 'Salbutamol', 'Salbutamol', '', '', 50, '15'),
(5, 'Ketotifeno', 'Ketotifeno', '', '', 50, '30');

-- --------------------------------------------------------

--
-- Table structure for table `catmetodosdepago`
--

CREATE TABLE `catmetodosdepago` (
  `idMetodoPago` int(11) NOT NULL,
  `NombreMetodoPago` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catmetodosdepago`
--

INSERT INTO `catmetodosdepago` (`idMetodoPago`, `NombreMetodoPago`, `Descripcion`) VALUES
(1, 'Contado', 'Pago en efectivo'),
(2, 'Credito', 'Pago acumulado'),
(3, 'Targeta bancaria', 'Pago por medio de targeta bancaria');

-- --------------------------------------------------------

--
-- Table structure for table `catmodulos`
--

CREATE TABLE `catmodulos` (
  `CodModulo` int(11) NOT NULL,
  `NombreModulo` varchar(30) NOT NULL,
  `DescripcionModulo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catmodulos`
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
-- Table structure for table `catmoneda`
--

CREATE TABLE `catmoneda` (
  `idMoneda` int(11) NOT NULL,
  `nombreMoneda` varchar(100) NOT NULL,
  `simbolo` varchar(2) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `EsReferencia` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catmoneda`
--

INSERT INTO `catmoneda` (`idMoneda`, `nombreMoneda`, `simbolo`, `Descripcion`, `EsReferencia`) VALUES
(1, 'Cordoba', 'C$', 'Moneda nicaraguense', 1),
(2, 'Dolar', '$', 'Moneda estado unidence', 1);

-- --------------------------------------------------------

--
-- Table structure for table `catnivelacademico`
--

CREATE TABLE `catnivelacademico` (
  `ID` tinyint(4) NOT NULL,
  `NombreNivelAcademico` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catnivelacademico`
--

INSERT INTO `catnivelacademico` (`ID`, `NombreNivelAcademico`) VALUES
(1, 'Secundaria'),
(2, 'Universitario'),
(3, 'Maestria'),
(4, 'Doctorado');

-- --------------------------------------------------------

--
-- Table structure for table `catparentesco`
--

CREATE TABLE `catparentesco` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catparentesco`
--

INSERT INTO `catparentesco` (`ID`, `Nombre`) VALUES
(1, 'No relación'),
(2, 'Padre'),
(3, 'Madre');

-- --------------------------------------------------------

--
-- Table structure for table `catprivilegio`
--

CREATE TABLE `catprivilegio` (
  `Codigo` int(11) NOT NULL,
  `NombrePrivilegio` varchar(30) NOT NULL,
  `Descripcion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catprivilegio`
--

INSERT INTO `catprivilegio` (`Codigo`, `NombrePrivilegio`, `Descripcion`) VALUES
(1, 'Agregar', 'Agregar Registros'),
(2, 'Ver', 'Ver Registros'),
(3, 'Actualizar', 'Actualizar Registros');

-- --------------------------------------------------------

--
-- Table structure for table `catsalaexamen`
--

CREATE TABLE `catsalaexamen` (
  `ID` tinyint(4) NOT NULL,
  `Nombre` varchar(60) DEFAULT NULL,
  `Dimensiones` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catsalaexamen`
--

INSERT INTO `catsalaexamen` (`ID`, `Nombre`, `Dimensiones`) VALUES
(1, 'Sala examen 1', '2x2'),
(2, 'Sala examen 2', '3x3');

-- --------------------------------------------------------

--
-- Table structure for table `catservicios`
--

CREATE TABLE `catservicios` (
  `idServicio` int(11) NOT NULL,
  `nombreServicio` varchar(100) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `PrecioGeneral` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catservicios`
--

INSERT INTO `catservicios` (`idServicio`, `nombreServicio`, `Descripcion`, `PrecioGeneral`) VALUES
(1, 'Examen corazon', 'Un examen alcorazon', '500'),
(2, 'Consulta general', 'Una consulta general', '300'),
(3, 'Venta medicamentos', 'Una venta de medicamentos', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `catsubmodulos`
--

CREATE TABLE `catsubmodulos` (
  `CodSubModulo` int(11) NOT NULL,
  `NombreSubModulo` varchar(30) NOT NULL,
  `DescripcionSubModulo` varchar(100) DEFAULT NULL,
  `CodigoModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `catsubmodulos`
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
(27, 'DetalleVentaFarmacia', 'Detalle de la venta de la farmacia', 9);

-- --------------------------------------------------------

--
-- Table structure for table `loteproductos`
--

CREATE TABLE `loteproductos` (
  `idLote` int(11) NOT NULL,
  `DetalleDeCompra` int(11) NOT NULL,
  `FechaVencimiento` date NOT NULL,
  `CantidadRestante` int(11) NOT NULL,
  `Nota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loteproductos`
--

INSERT INTO `loteproductos` (`idLote`, `DetalleDeCompra`, `FechaVencimiento`, `CantidadRestante`, `Nota`) VALUES
(1, 1, '2022-12-12', 100, NULL),
(2, 2, '2022-12-12', 50, NULL),
(3, 3, '2022-12-12', 50, NULL),
(4, 4, '2022-12-12', 20, NULL),
(5, 5, '2022-12-12', 10, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblantecedentes`
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
-- Table structure for table `tblaperturacaja`
--

CREATE TABLE `tblaperturacaja` (
  `idApertura` int(11) NOT NULL,
  `Caja` int(11) NOT NULL,
  `MontoInicial` decimal(10,0) NOT NULL,
  `EmpleadoCaja` int(11) NOT NULL,
  `FyHInicio` datetime NOT NULL,
  `FyHCierre` datetime DEFAULT NULL,
  `EstadoApertura` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblaperturacaja`
--

INSERT INTO `tblaperturacaja` (`idApertura`, `Caja`, `MontoInicial`, `EmpleadoCaja`, `FyHInicio`, `FyHCierre`, `EstadoApertura`) VALUES
(1, 1, '100000', 5, '2022-05-24 00:00:00', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tblcita`
--

CREATE TABLE `tblcita` (
  `IDCita` int(11) NOT NULL,
  `CodPaciente` int(11) NOT NULL,
  `fechaProgramada` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblclientes`
--

CREATE TABLE `tblclientes` (
  `idCliente` int(11) NOT NULL,
  `CodPersona` int(11) NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblcomprasfarmacia`
--

CREATE TABLE `tblcomprasfarmacia` (
  `idCompras` int(11) NOT NULL,
  `Proveedor` int(11) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `fechaCompra` date NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblcomprasfarmacia`
--

INSERT INTO `tblcomprasfarmacia` (`idCompras`, `Proveedor`, `Descripcion`, `fechaCompra`, `FechaRegistro`) VALUES
(1, 1, 'Se compro Omeprazol', '2021-12-12', '2021-12-12 06:00:00'),
(2, 1, 'Se compro Paracetamol', '2021-12-12', '2021-12-12 06:00:00'),
(3, 1, 'Se compro Acetaminofen', '2021-12-12', '2021-12-12 06:00:00'),
(4, 1, 'Se compro Salbutamol', '2021-12-12', '2021-12-12 06:00:00'),
(5, 1, 'Se compro Ketotifeno', '2021-12-12', '2021-12-12 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblconstancia`
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
-- Table structure for table `tblconsulta`
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
-- Dumping data for table `tblconsulta`
--

INSERT INTO `tblconsulta` (`Codigo`, `CodMedico`, `IdCita`, `CodPaciente`, `CodConsultorio`, `Estado`, `FechaYHora`, `FhInicio`, `FhFinal`, `MotivoConsulta`, `RegistradoPor`, `idServicio`, `NotasConsulta`, `MotivoRevertida`) VALUES
(1, 3, 0, 1, 1, 5, '2021-01-01 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Motivo', 4, 1, '', ''),
(4, 3, 0, 1, 1, 5, '2022-05-30 12:58:42', '2022-05-30 13:27:22', NULL, '', 4, 6, 'sobre', NULL),
(5, 3, 0, 2, 1, 5, '2022-05-30 13:46:01', '2022-05-30 14:11:10', NULL, '', 4, 7, 'Cambios que funcionan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbldetallereceta`
--

CREATE TABLE `tbldetallereceta` (
  `Codigo` int(11) NOT NULL,
  `Medicamento` int(11) DEFAULT NULL,
  `Dosis` varchar(50) DEFAULT NULL,
  `Frecuencia` varchar(40) DEFAULT NULL,
  `CodReceta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbldetallereceta`
--

INSERT INTO `tbldetallereceta` (`Codigo`, `Medicamento`, `Dosis`, `Frecuencia`, `CodReceta`) VALUES
(1, 1, '10', '100', 1),
(2, 2, '10', '100', 2),
(3, 3, '10', '100', 3),
(4, 4, '10', '100', 4),
(5, 5, '10', '100', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbldetallesdecita`
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
-- Table structure for table `tbldetalleventafarmacia`
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
-- Dumping data for table `tbldetalleventafarmacia`
--

INSERT INTO `tbldetalleventafarmacia` (`idDetalleVentaFarmacia`, `idVentaFarmacia`, `idMedicamento`, `cantidadVendida`, `nota`, `fechaRegistro`, `idDetalleReceta`) VALUES
(1, 1, 1, 10, 'Todo bien', '2022-05-29 06:00:00', 1),
(2, 2, 2, 10, 'Todo bien', '2022-05-29 06:00:00', 2),
(3, 3, 3, 10, 'Todo bien', '2022-05-29 06:00:00', 3),
(4, 4, 4, 10, 'Todo bien', '2022-05-29 06:00:00', 4),
(5, 5, 5, 10, 'Todo bien', '2022-05-29 06:00:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbldetcomprasfarmacia`
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
-- Dumping data for table `tbldetcomprasfarmacia`
--

INSERT INTO `tbldetcomprasfarmacia` (`idDetalleCompra`, `Medicamento`, `Cantidad`, `CostoUnidad`, `CodigoCompra`, `Notas`) VALUES
(1, 1, 100, 10, 1, 'Todo bien'),
(2, 2, 50, 15, 2, 'Todo bien'),
(3, 3, 50, 20, 3, 'Todo bien'),
(4, 4, 20, 25, 4, 'Todo bien'),
(5, 5, 10, 30, 5, 'Todo bien');

-- --------------------------------------------------------

--
-- Table structure for table `tbldetfactconsulta`
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
-- Table structure for table `tbldetfactexamen`
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
-- Table structure for table `tbldetpagoservicios`
--

CREATE TABLE `tbldetpagoservicios` (
  `idDetPago` int(11) NOT NULL,
  `ServicioBrindado` int(11) NOT NULL,
  `Monto` decimal(10,0) NOT NULL,
  `metodoDePago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbldiagnosticoconsulta`
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
-- Dumping data for table `tbldiagnosticoconsulta`
--

INSERT INTO `tbldiagnosticoconsulta` (`Codigo`, `Sintoma`, `Descripcion`, `IdEnfermedad`, `CodConsulta`, `Nota`) VALUES
(1, 'Tos y flema', '', 1, 1, ''),
(3, 'Calentura', 'Todo se derrumbó', 4, 4, 'Dentro de ti'),
(4, 'Calentura', 'Sisisisis', 6, 5, 'Aproximadamente');

-- --------------------------------------------------------

--
-- Table structure for table `tblempleado`
--

CREATE TABLE `tblempleado` (
  `Codigo` int(11) NOT NULL,
  `INSS` varchar(9) NOT NULL,
  `FechaIngreso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CodPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblempleado`
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
-- Table structure for table `tblespecialidad`
--

CREATE TABLE `tblespecialidad` (
  `ID` int(11) NOT NULL,
  `CodDoctor` int(11) NOT NULL,
  `IDEspecialidad` tinyint(4) NOT NULL,
  `FechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblestudioacademico`
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
-- Table structure for table `tblexamen`
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

-- --------------------------------------------------------

--
-- Table structure for table `tblfactura`
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
-- Table structure for table `tblfamiliares`
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
-- Table structure for table `tblhistorialacademico`
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
-- Table structure for table `tblhistorialcargos`
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
-- Dumping data for table `tblhistorialcargos`
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
-- Table structure for table `tblmaquinariamedica`
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
-- Table structure for table `tblmovimientolote`
--

CREATE TABLE `tblmovimientolote` (
  `idMovimientoLote` int(11) NOT NULL,
  `idDetalleVenta` int(11) NOT NULL,
  `idLote` int(11) NOT NULL,
  `unidades` int(11) NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblmovimientolote`
--

INSERT INTO `tblmovimientolote` (`idMovimientoLote`, `idDetalleVenta`, `idLote`, `unidades`, `fechaRegistro`) VALUES
(1, 1, 1, 10, '2022-01-01 06:00:00'),
(2, 2, 2, 10, '2022-01-01 06:00:00'),
(3, 3, 3, 10, '2022-01-01 06:00:00'),
(4, 4, 4, 10, '2022-01-01 06:00:00'),
(5, 5, 5, 10, '2022-01-01 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblocupacionpacientes`
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
-- Table structure for table `tblpaciente`
--

CREATE TABLE `tblpaciente` (
  `CodigoP` int(11) NOT NULL,
  `CodExpediente` varchar(10) NOT NULL,
  `INSS` int(11) NOT NULL,
  `GrupoSanguineo` tinyint(4) NOT NULL,
  `CodPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblpaciente`
--

INSERT INTO `tblpaciente` (`CodigoP`, `CodExpediente`, `INSS`, `GrupoSanguineo`, `CodPersona`) VALUES
(1, '80', 123123132, 4, 8),
(2, '90', 188888888, 5, 9);

-- --------------------------------------------------------

--
-- Table structure for table `tblpersona`
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
-- Dumping data for table `tblpersona`
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
(9, '121-121299-1000V', 'Ejemplo', 'Dos', '1999-12-12', 1, 1, 'Managua Nicaragua', '182818281', 'sobreton@gmail.com', 1, '2022-05-28 18:50:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblprivilegiosusuario`
--

CREATE TABLE `tblprivilegiosusuario` (
  `Codigo` int(11) NOT NULL,
  `CodUsuario` int(11) NOT NULL,
  `CodPrivilegio` int(11) NOT NULL,
  `CodigoSubModulo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblprivilegiosusuario`
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
(101, 7, 2, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tblproveedores`
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
-- Dumping data for table `tblproveedores`
--

INSERT INTO `tblproveedores` (`idProveedor`, `nombreProvedor`, `TelefonoProveedor`, `DireccionProvedor`, `EmailProveedor`, `EstadoProveedor`, `Ranking`, `Fecha_registro`, `leadtime`) VALUES
(1, 'Medicamentos.S.A', '85746321', 'Ciudad jardin', 'medicamentossa@gmail.com', 1, 5, '2022-05-24 06:00:00', 30),
(2, 'Proveedor2', '12371371231', 'no este jodiendo', 'puntocom@com', 1, 2, '2022-05-25 00:36:56', 20),
(3, 'Proveedorestrella', '18317238123', 'ksjgbkjehaoifiehf', 'liwehgowfhiohefiwhf@gmail.com', 1, 1, '2022-05-25 00:37:48', 2),
(4, 'Proveedor4', '7133723132', 'managua', 'adjsdbajkd@gmail.com', 1, 4, '2022-05-25 00:39:01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblrecetaexamen`
--

CREATE TABLE `tblrecetaexamen` (
  `Codigo` int(11) NOT NULL,
  `ConsultaPrevia` int(11) NOT NULL,
  `TipoExamen` tinyint(4) NOT NULL,
  `Motivo` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblrecetamedicamentos`
--

CREATE TABLE `tblrecetamedicamentos` (
  `Codigo` int(11) NOT NULL,
  `CodigoConsulta` int(11) NOT NULL,
  `FechaEmision` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblrecetamedicamentos`
--

INSERT INTO `tblrecetamedicamentos` (`Codigo`, `CodigoConsulta`, `FechaEmision`) VALUES
(1, 1, '2021-01-12 06:00:00'),
(2, 1, '2021-02-12 06:00:00'),
(3, 1, '2021-03-12 06:00:00'),
(4, 1, '2021-04-12 06:00:00'),
(5, 1, '2021-05-12 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tblrecibosventa`
--

CREATE TABLE `tblrecibosventa` (
  `idRecibo` int(11) NOT NULL,
  `Cliente` int(11) NOT NULL,
  `RebajaTotal` decimal(10,0) DEFAULT NULL,
  `aperturaCaja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblresultado`
--

CREATE TABLE `tblresultado` (
  `Codigo` int(11) NOT NULL,
  `CodExamen` int(11) NOT NULL,
  `ArchivoResultado` text DEFAULT NULL,
  `FechaYHora` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tblserviciosbrindados`
--

CREATE TABLE `tblserviciosbrindados` (
  `idServiciosBrindados` int(11) NOT NULL,
  `tipoServicio` int(11) NOT NULL,
  `estadoServicio` int(11) NOT NULL,
  `MontoServicio` decimal(10,0) NOT NULL,
  `RebajaServicio` decimal(10,0) NOT NULL,
  `NumeroRecibo` int(11) DEFAULT NULL,
  `fechaYHora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblserviciosbrindados`
--

INSERT INTO `tblserviciosbrindados` (`idServiciosBrindados`, `tipoServicio`, `estadoServicio`, `MontoServicio`, `RebajaServicio`, `NumeroRecibo`, `fechaYHora`) VALUES
(1, 2, 1, '0', '0', NULL, '2022-05-25 00:22:15'),
(2, 2, 1, '0', '0', NULL, '2022-05-28 18:51:33'),
(3, 2, 1, '0', '0', NULL, '2022-05-28 18:54:03'),
(4, 2, 1, '0', '0', NULL, '2022-05-28 18:54:16'),
(5, 2, 1, '0', '0', NULL, '2022-05-28 18:54:49'),
(6, 2, 1, '0', '0', NULL, '2022-05-30 18:58:42'),
(7, 2, 1, '0', '0', NULL, '2022-05-30 19:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `tblsesion`
--

CREATE TABLE `tblsesion` (
  `idSesion` int(11) NOT NULL,
  `CodUsuarioSesion` int(11) NOT NULL,
  `EstadoSesion` tinyint(4) NOT NULL,
  `tokenSesion` varchar(200) DEFAULT NULL,
  `FechayHoraSesion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblsesion`
--

INSERT INTO `tblsesion` (`idSesion`, `CodUsuarioSesion`, `EstadoSesion`, `tokenSesion`, `FechayHoraSesion`) VALUES
(1, 1, 1, NULL, '2022-05-24 23:56:05'),
(2, 1, 2, NULL, '2022-05-25 00:01:17'),
(3, 1, 1, NULL, '2022-05-25 00:01:20'),
(4, 1, 2, NULL, '2022-05-25 00:21:12'),
(5, 4, 1, NULL, '2022-05-25 00:21:17'),
(6, 4, 2, NULL, '2022-05-25 00:22:19'),
(7, 3, 1, NULL, '2022-05-25 00:22:22'),
(8, 3, 2, NULL, '2022-05-25 00:24:09'),
(9, 1, 1, NULL, '2022-05-25 00:24:15'),
(10, 1, 2, NULL, '2022-05-25 00:40:01'),
(11, 1, 1, NULL, '2022-05-25 00:40:36'),
(12, 1, 2, NULL, '2022-05-25 00:43:40'),
(13, 1, 1, NULL, '2022-05-25 00:43:45'),
(14, 1, 2, NULL, '2022-05-25 00:43:53'),
(15, 3, 1, NULL, '2022-05-25 00:44:01'),
(16, 3, 2, NULL, '2022-05-25 00:44:30'),
(17, 1, 1, NULL, '2022-05-25 00:44:34'),
(18, 1, 2, NULL, '2022-05-25 00:48:33'),
(19, 3, 1, NULL, '2022-05-25 00:48:40'),
(20, 3, 2, NULL, '2022-05-25 00:49:25'),
(21, 1, 1, NULL, '2022-05-25 00:49:29'),
(22, 1, 2, NULL, '2022-05-25 16:31:56'),
(23, 3, 1, NULL, '2022-05-28 18:49:39'),
(24, 3, 2, NULL, '2022-05-28 18:49:43'),
(25, 6, 1, NULL, '2022-05-28 18:49:48'),
(26, 6, 2, NULL, '2022-05-28 18:50:08'),
(27, 4, 1, NULL, '2022-05-28 18:50:12'),
(28, 4, 2, NULL, '2022-05-28 18:55:30'),
(29, 6, 1, NULL, '2022-05-28 18:55:36'),
(30, 6, 2, NULL, '2022-05-28 19:04:35'),
(31, 3, 1, NULL, '2022-05-28 19:04:38'),
(32, 3, 2, NULL, '2022-05-28 20:49:07'),
(33, 2, 1, NULL, '2022-05-28 20:49:12'),
(34, 2, 2, NULL, '2022-05-28 20:49:16'),
(35, 1, 1, NULL, '2022-05-28 20:49:21'),
(36, 1, 2, NULL, '2022-05-28 22:07:58'),
(37, 1, 1, NULL, '2022-05-29 22:18:01'),
(38, 1, 2, NULL, '2022-05-30 00:14:26'),
(39, 1, 1, NULL, '2022-05-30 00:43:37'),
(40, 1, 2, NULL, '2022-05-30 02:02:41'),
(41, 1, 1, NULL, '2022-05-30 18:50:44'),
(42, 1, 2, NULL, '2022-05-30 18:50:56'),
(43, 6, 1, NULL, '2022-05-30 18:51:01'),
(44, 6, 2, NULL, '2022-05-30 18:57:57'),
(45, 3, 1, NULL, '2022-05-30 18:57:59'),
(46, 3, 2, NULL, '2022-05-30 18:58:12'),
(47, 4, 1, NULL, '2022-05-30 18:58:18'),
(48, 4, 2, NULL, '2022-05-30 18:58:47'),
(49, 3, 1, NULL, '2022-05-30 18:58:50'),
(50, 3, 2, NULL, '2022-05-30 19:06:05'),
(51, 6, 1, NULL, '2022-05-30 19:06:11'),
(52, 6, 2, NULL, '2022-05-30 19:06:50'),
(53, 3, 1, NULL, '2022-05-30 19:06:59'),
(54, 3, 2, NULL, '2022-05-30 19:34:13'),
(55, 6, 1, NULL, '2022-05-30 19:34:23'),
(56, 6, 2, NULL, '2022-05-30 19:38:17'),
(57, 4, 1, NULL, '2022-05-30 19:38:23'),
(58, 4, 2, NULL, '2022-05-30 19:47:07'),
(59, 3, 1, NULL, '2022-05-30 19:47:14'),
(60, 3, 2, NULL, '2022-05-30 20:15:46'),
(61, 2, 1, NULL, '2022-05-30 20:15:53'),
(62, 2, 2, NULL, '2022-05-30 20:15:58'),
(63, 3, 1, NULL, '2022-05-30 20:16:02'),
(64, 3, 2, NULL, '2022-05-30 20:30:34'),
(65, 4, 1, NULL, '2022-05-30 20:30:41'),
(66, 4, 2, NULL, '2022-05-30 20:33:04'),
(67, 3, 1, NULL, '2022-05-30 20:33:06'),
(68, 3, 2, NULL, '2022-05-31 02:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `tblsignosvitales`
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
-- Dumping data for table `tblsignosvitales`
--

INSERT INTO `tblsignosvitales` (`Codigo`, `CodConsulta`, `Peso`, `Altura`, `Presion_Arterial`, `Frecuencia_Respiratoria`, `Frecuencia_Cardiaca`, `Temperatura`, `HoraRegistro`, `CodEnfermera`) VALUES
(6, 4, '190', '190', '190', '190', '190', '40', '2022-05-30 19:24:41', 3),
(7, 5, '190', '190', '190', '190', '190', '40', '2022-05-30 19:47:35', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbltipodecambio`
--

CREATE TABLE `tbltipodecambio` (
  `idCambio` int(11) NOT NULL,
  `Moneda` int(11) NOT NULL,
  `CambioReferencia` decimal(10,0) NOT NULL,
  `aperturaCaja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbltipodecambio`
--

INSERT INTO `tbltipodecambio` (`idCambio`, `Moneda`, `CambioReferencia`, `aperturaCaja`) VALUES
(1, 2, '36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblusuarios`
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
-- Dumping data for table `tblusuarios`
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
-- Table structure for table `tblventafarmacia`
--

CREATE TABLE `tblventafarmacia` (
  `idVentaFarmacia` int(11) NOT NULL,
  `idRecetaMedica` int(11) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `fechaVenta` date NOT NULL,
  `fechaRegistro` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblventafarmacia`
--

INSERT INTO `tblventafarmacia` (`idVentaFarmacia`, `idRecetaMedica`, `descripcion`, `fechaVenta`, `fechaRegistro`) VALUES
(1, 1, 'Venta', '2021-01-12', '2022-05-29 06:00:00'),
(2, 2, 'Venta', '2022-02-12', '2022-05-29 06:00:00'),
(3, 3, 'Venta', '2022-03-12', '2022-05-29 06:00:00'),
(4, 4, 'Venta', '2022-04-12', '2022-05-29 06:00:00'),
(5, 5, 'Venta', '2022-05-12', '2022-05-29 06:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `catcaja`
--
ALTER TABLE `catcaja`
  ADD PRIMARY KEY (`idCaja`);

--
-- Indexes for table `catcargos`
--
ALTER TABLE `catcargos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catconsultorio`
--
ALTER TABLE `catconsultorio`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catenfermedades`
--
ALTER TABLE `catenfermedades`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catespecialidades`
--
ALTER TABLE `catespecialidades`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catestado`
--
ALTER TABLE `catestado`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catestadocita`
--
ALTER TABLE `catestadocita`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catestadocivil`
--
ALTER TABLE `catestadocivil`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catestadoconsulta`
--
ALTER TABLE `catestadoconsulta`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catestadoservicios`
--
ALTER TABLE `catestadoservicios`
  ADD PRIMARY KEY (`idEstadoServicio`);

--
-- Indexes for table `catexamenesmedicos`
--
ALTER TABLE `catexamenesmedicos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catgenero`
--
ALTER TABLE `catgenero`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catgruposanguineo`
--
ALTER TABLE `catgruposanguineo`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catmaquinaria`
--
ALTER TABLE `catmaquinaria`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catmedicamentos`
--
ALTER TABLE `catmedicamentos`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indexes for table `catmetodosdepago`
--
ALTER TABLE `catmetodosdepago`
  ADD PRIMARY KEY (`idMetodoPago`);

--
-- Indexes for table `catmodulos`
--
ALTER TABLE `catmodulos`
  ADD PRIMARY KEY (`CodModulo`);

--
-- Indexes for table `catmoneda`
--
ALTER TABLE `catmoneda`
  ADD PRIMARY KEY (`idMoneda`),
  ADD KEY `EsReferencia` (`EsReferencia`);

--
-- Indexes for table `catnivelacademico`
--
ALTER TABLE `catnivelacademico`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catparentesco`
--
ALTER TABLE `catparentesco`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catprivilegio`
--
ALTER TABLE `catprivilegio`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indexes for table `catsalaexamen`
--
ALTER TABLE `catsalaexamen`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `catservicios`
--
ALTER TABLE `catservicios`
  ADD PRIMARY KEY (`idServicio`);

--
-- Indexes for table `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD PRIMARY KEY (`CodSubModulo`),
  ADD KEY `CodigoModulo` (`CodigoModulo`);

--
-- Indexes for table `loteproductos`
--
ALTER TABLE `loteproductos`
  ADD PRIMARY KEY (`idLote`),
  ADD KEY `DetalleDeCompra` (`DetalleDeCompra`);

--
-- Indexes for table `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `Enfermedad` (`Enfermedad`),
  ADD KEY `EstadoAntecedente` (`EstadoAntecedente`);

--
-- Indexes for table `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  ADD PRIMARY KEY (`idApertura`),
  ADD KEY `EmpleadoCaja` (`EmpleadoCaja`),
  ADD KEY `Caja` (`Caja`),
  ADD KEY `EstadoApertura` (`EstadoApertura`);

--
-- Indexes for table `tblcita`
--
ALTER TABLE `tblcita`
  ADD PRIMARY KEY (`IDCita`),
  ADD KEY `CodPaciente` (`CodPaciente`);

--
-- Indexes for table `tblclientes`
--
ALTER TABLE `tblclientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `CodPersona` (`CodPersona`);

--
-- Indexes for table `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  ADD PRIMARY KEY (`idCompras`),
  ADD KEY `Proveedor` (`Proveedor`);

--
-- Indexes for table `tblconstancia`
--
ALTER TABLE `tblconstancia`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodDiagnostico` (`CodDiagnostico`);

--
-- Indexes for table `tblconsulta`
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
-- Indexes for table `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `Medicamento` (`Medicamento`),
  ADD KEY `CodReceta` (`CodReceta`);

--
-- Indexes for table `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  ADD PRIMARY KEY (`IDDetallecita`),
  ADD KEY `IdCita` (`IdCita`),
  ADD KEY `IdConsultorio` (`IdConsultorio`),
  ADD KEY `CodDoctor` (`CodDoctor`),
  ADD KEY `Estado` (`Estado`);

--
-- Indexes for table `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  ADD PRIMARY KEY (`idDetalleVentaFarmacia`),
  ADD KEY `idVentaFarmacia` (`idVentaFarmacia`),
  ADD KEY `idMedicamento` (`idMedicamento`);

--
-- Indexes for table `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  ADD PRIMARY KEY (`idDetalleCompra`),
  ADD KEY `Medicamento` (`Medicamento`),
  ADD KEY `CodigoCompra` (`CodigoCompra`);

--
-- Indexes for table `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `CodFactura` (`CodFactura`);

--
-- Indexes for table `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodExamen` (`CodExamen`),
  ADD KEY `CodFactura` (`CodFactura`);

--
-- Indexes for table `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  ADD PRIMARY KEY (`idDetPago`),
  ADD KEY `ServicioBrindado` (`ServicioBrindado`),
  ADD KEY `metodoDePago` (`metodoDePago`);

--
-- Indexes for table `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `IdEnfermedad` (`IdEnfermedad`);

--
-- Indexes for table `tblempleado`
--
ALTER TABLE `tblempleado`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodPersona` (`CodPersona`);

--
-- Indexes for table `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodDoctor` (`CodDoctor`),
  ADD KEY `IDEspecialidad` (`IDEspecialidad`);

--
-- Indexes for table `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  ADD PRIMARY KEY (`IDEstudioAcademico`),
  ADD KEY `CodEmpleado` (`CodEmpleado`),
  ADD KEY `TipoEstudio` (`TipoEstudio`);

--
-- Indexes for table `tblexamen`
--
ALTER TABLE `tblexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `RecetaPrevia` (`RecetaPrevia`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `SalaMedica` (`SalaMedica`),
  ADD KEY `EmpleadoRealizacion` (`EmpleadoRealizacion`),
  ADD KEY `idServicio` (`idServicio`);

--
-- Indexes for table `tblfactura`
--
ALTER TABLE `tblfactura`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `MetodoDePago` (`MetodoDePago`),
  ADD KEY `EmpleadoCaja` (`EmpleadoCaja`);

--
-- Indexes for table `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPersona` (`CodPersona`),
  ADD KEY `FamiliarDe` (`FamiliarDe`),
  ADD KEY `Parentesco` (`Parentesco`);

--
-- Indexes for table `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`),
  ADD KEY `Nivel_academico` (`Nivel_academico`),
  ADD KEY `Estado` (`Estado`);

--
-- Indexes for table `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodEmpleado` (`CodEmpleado`),
  ADD KEY `IdCargo` (`IdCargo`),
  ADD KEY `Estado` (`Estado`),
  ADD KEY `RegistradoPor` (`RegistradoPor`),
  ADD KEY `AprobadoPor` (`AprobadoPor`);

--
-- Indexes for table `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `tipoMaquina` (`tipoMaquina`),
  ADD KEY `ubicacion` (`ubicacion`);

--
-- Indexes for table `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  ADD PRIMARY KEY (`idMovimientoLote`),
  ADD KEY `idDetalleVenta` (`idDetalleVenta`),
  ADD KEY `idLote` (`idLote`);

--
-- Indexes for table `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CodPaciente` (`CodPaciente`);

--
-- Indexes for table `tblpaciente`
--
ALTER TABLE `tblpaciente`
  ADD PRIMARY KEY (`CodigoP`),
  ADD KEY `CodPersona` (`CodPersona`),
  ADD KEY `GrupoSanguineo` (`GrupoSanguineo`);

--
-- Indexes for table `tblpersona`
--
ALTER TABLE `tblpersona`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `Cedula` (`Cedula`),
  ADD KEY `Genero` (`Genero`),
  ADD KEY `Estado_civil` (`Estado_civil`),
  ADD KEY `Estado` (`Estado`);

--
-- Indexes for table `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodPrivilegio` (`CodPrivilegio`),
  ADD KEY `CodUsuario` (`CodUsuario`),
  ADD KEY `CodigoSubModulo` (`CodigoSubModulo`);

--
-- Indexes for table `tblproveedores`
--
ALTER TABLE `tblproveedores`
  ADD PRIMARY KEY (`idProveedor`),
  ADD KEY `EstadoProveedor` (`EstadoProveedor`);

--
-- Indexes for table `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `TipoExamen` (`TipoExamen`),
  ADD KEY `ConsultaPrevia` (`ConsultaPrevia`);

--
-- Indexes for table `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodigoConsulta` (`CodigoConsulta`);

--
-- Indexes for table `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  ADD PRIMARY KEY (`idRecibo`),
  ADD KEY `Cliente` (`Cliente`),
  ADD KEY `aperturaCaja` (`aperturaCaja`);

--
-- Indexes for table `tblresultado`
--
ALTER TABLE `tblresultado`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodExamen` (`CodExamen`);

--
-- Indexes for table `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  ADD PRIMARY KEY (`idServiciosBrindados`),
  ADD KEY `tipoServicio` (`tipoServicio`),
  ADD KEY `estadoServicio` (`estadoServicio`),
  ADD KEY `NumeroRecibo` (`NumeroRecibo`);

--
-- Indexes for table `tblsesion`
--
ALTER TABLE `tblsesion`
  ADD PRIMARY KEY (`idSesion`),
  ADD KEY `CodUsuarioSesion` (`CodUsuarioSesion`),
  ADD KEY `EstadoSesion` (`EstadoSesion`);

--
-- Indexes for table `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `CodConsulta` (`CodConsulta`),
  ADD KEY `CodEnfermera` (`CodEnfermera`);

--
-- Indexes for table `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD PRIMARY KEY (`idCambio`),
  ADD KEY `aperturaCaja` (`aperturaCaja`),
  ADD KEY `Moneda` (`Moneda`);

--
-- Indexes for table `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `NombreUsuario` (`NombreUsuario`),
  ADD UNIQUE KEY `CodPersonaU` (`CodPersonaU`),
  ADD KEY `Estado` (`Estado`);

--
-- Indexes for table `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  ADD PRIMARY KEY (`idVentaFarmacia`),
  ADD KEY `idRecetaMedica` (`idRecetaMedica`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `catcaja`
--
ALTER TABLE `catcaja`
  MODIFY `idCaja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `catcargos`
--
ALTER TABLE `catcargos`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `catconsultorio`
--
ALTER TABLE `catconsultorio`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catenfermedades`
--
ALTER TABLE `catenfermedades`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `catespecialidades`
--
ALTER TABLE `catespecialidades`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `catestado`
--
ALTER TABLE `catestado`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `catestadocita`
--
ALTER TABLE `catestadocita`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catestadocivil`
--
ALTER TABLE `catestadocivil`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catestadoconsulta`
--
ALTER TABLE `catestadoconsulta`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `catestadoservicios`
--
ALTER TABLE `catestadoservicios`
  MODIFY `idEstadoServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catexamenesmedicos`
--
ALTER TABLE `catexamenesmedicos`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `catgenero`
--
ALTER TABLE `catgenero`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catgruposanguineo`
--
ALTER TABLE `catgruposanguineo`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `catmaquinaria`
--
ALTER TABLE `catmaquinaria`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catmedicamentos`
--
ALTER TABLE `catmedicamentos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `catmetodosdepago`
--
ALTER TABLE `catmetodosdepago`
  MODIFY `idMetodoPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `catmodulos`
--
ALTER TABLE `catmodulos`
  MODIFY `CodModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `catmoneda`
--
ALTER TABLE `catmoneda`
  MODIFY `idMoneda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catnivelacademico`
--
ALTER TABLE `catnivelacademico`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `catparentesco`
--
ALTER TABLE `catparentesco`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `catprivilegio`
--
ALTER TABLE `catprivilegio`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `catsalaexamen`
--
ALTER TABLE `catsalaexamen`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `catservicios`
--
ALTER TABLE `catservicios`
  MODIFY `idServicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  MODIFY `CodSubModulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `loteproductos`
--
ALTER TABLE `loteproductos`
  MODIFY `idLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  MODIFY `idApertura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblcita`
--
ALTER TABLE `tblcita`
  MODIFY `IDCita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblclientes`
--
ALTER TABLE `tblclientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  MODIFY `idCompras` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblconstancia`
--
ALTER TABLE `tblconstancia`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblconsulta`
--
ALTER TABLE `tblconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  MODIFY `IDDetallecita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  MODIFY `idDetalleVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  MODIFY `idDetalleCompra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  MODIFY `idDetPago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblempleado`
--
ALTER TABLE `tblempleado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  MODIFY `IDEstudioAcademico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblexamen`
--
ALTER TABLE `tblexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblfactura`
--
ALTER TABLE `tblfactura`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  MODIFY `idMovimientoLote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblpaciente`
--
ALTER TABLE `tblpaciente`
  MODIFY `CodigoP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `tblproveedores`
--
ALTER TABLE `tblproveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  MODIFY `idRecibo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblresultado`
--
ALTER TABLE `tblresultado`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  MODIFY `idServiciosBrindados` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblsesion`
--
ALTER TABLE `tblsesion`
  MODIFY `idSesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  MODIFY `idCambio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblusuarios`
--
ALTER TABLE `tblusuarios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  MODIFY `idVentaFarmacia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `catmoneda`
--
ALTER TABLE `catmoneda`
  ADD CONSTRAINT `catmoneda_ibfk_1` FOREIGN KEY (`EsReferencia`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `catsubmodulos`
--
ALTER TABLE `catsubmodulos`
  ADD CONSTRAINT `catsubmodulos_ibfk_1` FOREIGN KEY (`CodigoModulo`) REFERENCES `catmodulos` (`CodModulo`);

--
-- Constraints for table `loteproductos`
--
ALTER TABLE `loteproductos`
  ADD CONSTRAINT `loteproductos_ibfk_1` FOREIGN KEY (`DetalleDeCompra`) REFERENCES `tbldetcomprasfarmacia` (`idDetalleCompra`);

--
-- Constraints for table `tblantecedentes`
--
ALTER TABLE `tblantecedentes`
  ADD CONSTRAINT `tblantecedentes_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblantecedentes_ibfk_2` FOREIGN KEY (`Enfermedad`) REFERENCES `catenfermedades` (`ID`),
  ADD CONSTRAINT `tblantecedentes_ibfk_3` FOREIGN KEY (`EstadoAntecedente`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblaperturacaja`
--
ALTER TABLE `tblaperturacaja`
  ADD CONSTRAINT `tblaperturacaja_ibfk_1` FOREIGN KEY (`EmpleadoCaja`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblaperturacaja_ibfk_2` FOREIGN KEY (`Caja`) REFERENCES `catcaja` (`idCaja`),
  ADD CONSTRAINT `tblaperturacaja_ibfk_3` FOREIGN KEY (`EstadoApertura`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblcita`
--
ALTER TABLE `tblcita`
  ADD CONSTRAINT `tblcita_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`);

--
-- Constraints for table `tblclientes`
--
ALTER TABLE `tblclientes`
  ADD CONSTRAINT `tblclientes_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`);

--
-- Constraints for table `tblcomprasfarmacia`
--
ALTER TABLE `tblcomprasfarmacia`
  ADD CONSTRAINT `tblcomprasfarmacia_ibfk_1` FOREIGN KEY (`Proveedor`) REFERENCES `tblproveedores` (`idProveedor`);

--
-- Constraints for table `tblconstancia`
--
ALTER TABLE `tblconstancia`
  ADD CONSTRAINT `tblconstancia_ibfk_1` FOREIGN KEY (`CodDiagnostico`) REFERENCES `tbldiagnosticoconsulta` (`Codigo`);

--
-- Constraints for table `tblconsulta`
--
ALTER TABLE `tblconsulta`
  ADD CONSTRAINT `tblconsulta_ibfk_1` FOREIGN KEY (`CodMedico`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblconsulta_ibfk_2` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblconsulta_ibfk_3` FOREIGN KEY (`CodConsultorio`) REFERENCES `catconsultorio` (`ID`),
  ADD CONSTRAINT `tblconsulta_ibfk_4` FOREIGN KEY (`Estado`) REFERENCES `catestadoconsulta` (`ID`),
  ADD CONSTRAINT `tblconsulta_ibfk_5` FOREIGN KEY (`RegistradoPor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblconsulta_ibfk_6` FOREIGN KEY (`idServicio`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`);

--
-- Constraints for table `tbldetallereceta`
--
ALTER TABLE `tbldetallereceta`
  ADD CONSTRAINT `tbldetallereceta_ibfk_1` FOREIGN KEY (`Medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tbldetallereceta_ibfk_2` FOREIGN KEY (`CodReceta`) REFERENCES `tblrecetamedicamentos` (`Codigo`);

--
-- Constraints for table `tbldetallesdecita`
--
ALTER TABLE `tbldetallesdecita`
  ADD CONSTRAINT `tbldetallesdecita_ibfk_1` FOREIGN KEY (`IdCita`) REFERENCES `tblcita` (`IDCita`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_2` FOREIGN KEY (`IdConsultorio`) REFERENCES `catconsultorio` (`ID`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_3` FOREIGN KEY (`CodDoctor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tbldetallesdecita_ibfk_4` FOREIGN KEY (`Estado`) REFERENCES `catestadocita` (`ID`);

--
-- Constraints for table `tbldetalleventafarmacia`
--
ALTER TABLE `tbldetalleventafarmacia`
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_1` FOREIGN KEY (`idVentaFarmacia`) REFERENCES `tblventafarmacia` (`idVentaFarmacia`),
  ADD CONSTRAINT `tbldetalleventafarmacia_ibfk_2` FOREIGN KEY (`idMedicamento`) REFERENCES `catmedicamentos` (`Codigo`);

--
-- Constraints for table `tbldetcomprasfarmacia`
--
ALTER TABLE `tbldetcomprasfarmacia`
  ADD CONSTRAINT `tbldetcomprasfarmacia_ibfk_1` FOREIGN KEY (`Medicamento`) REFERENCES `catmedicamentos` (`Codigo`),
  ADD CONSTRAINT `tbldetcomprasfarmacia_ibfk_2` FOREIGN KEY (`CodigoCompra`) REFERENCES `tblcomprasfarmacia` (`idCompras`);

--
-- Constraints for table `tbldetfactconsulta`
--
ALTER TABLE `tbldetfactconsulta`
  ADD CONSTRAINT `tbldetfactconsulta_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tbldetfactconsulta_ibfk_2` FOREIGN KEY (`CodFactura`) REFERENCES `tblfactura` (`Codigo`);

--
-- Constraints for table `tbldetfactexamen`
--
ALTER TABLE `tbldetfactexamen`
  ADD CONSTRAINT `tbldetfactexamen_ibfk_1` FOREIGN KEY (`CodExamen`) REFERENCES `tblexamen` (`Codigo`),
  ADD CONSTRAINT `tbldetfactexamen_ibfk_2` FOREIGN KEY (`CodFactura`) REFERENCES `tblfactura` (`Codigo`);

--
-- Constraints for table `tbldetpagoservicios`
--
ALTER TABLE `tbldetpagoservicios`
  ADD CONSTRAINT `tbldetpagoservicios_ibfk_1` FOREIGN KEY (`ServicioBrindado`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`),
  ADD CONSTRAINT `tbldetpagoservicios_ibfk_2` FOREIGN KEY (`metodoDePago`) REFERENCES `catmetodosdepago` (`idMetodoPago`);

--
-- Constraints for table `tbldiagnosticoconsulta`
--
ALTER TABLE `tbldiagnosticoconsulta`
  ADD CONSTRAINT `tbldiagnosticoconsulta_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tbldiagnosticoconsulta_ibfk_2` FOREIGN KEY (`IdEnfermedad`) REFERENCES `catenfermedades` (`ID`);

--
-- Constraints for table `tblempleado`
--
ALTER TABLE `tblempleado`
  ADD CONSTRAINT `tblempleado_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`);

--
-- Constraints for table `tblespecialidad`
--
ALTER TABLE `tblespecialidad`
  ADD CONSTRAINT `tblespecialidad_ibfk_1` FOREIGN KEY (`CodDoctor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblespecialidad_ibfk_2` FOREIGN KEY (`IDEspecialidad`) REFERENCES `catespecialidades` (`ID`);

--
-- Constraints for table `tblestudioacademico`
--
ALTER TABLE `tblestudioacademico`
  ADD CONSTRAINT `tblestudioacademico_ibfk_1` FOREIGN KEY (`CodEmpleado`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblestudioacademico_ibfk_2` FOREIGN KEY (`TipoEstudio`) REFERENCES `catnivelacademico` (`ID`);

--
-- Constraints for table `tblexamen`
--
ALTER TABLE `tblexamen`
  ADD CONSTRAINT `tblexamen_ibfk_1` FOREIGN KEY (`RecetaPrevia`) REFERENCES `tblrecetaexamen` (`Codigo`),
  ADD CONSTRAINT `tblexamen_ibfk_2` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblexamen_ibfk_3` FOREIGN KEY (`SalaMedica`) REFERENCES `catsalaexamen` (`ID`),
  ADD CONSTRAINT `tblexamen_ibfk_4` FOREIGN KEY (`EmpleadoRealizacion`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblexamen_ibfk_5` FOREIGN KEY (`idServicio`) REFERENCES `tblserviciosbrindados` (`idServiciosBrindados`);

--
-- Constraints for table `tblfamiliares`
--
ALTER TABLE `tblfamiliares`
  ADD CONSTRAINT `tblfamiliares_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblfamiliares_ibfk_2` FOREIGN KEY (`FamiliarDe`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblfamiliares_ibfk_3` FOREIGN KEY (`Parentesco`) REFERENCES `catparentesco` (`ID`);

--
-- Constraints for table `tblhistorialacademico`
--
ALTER TABLE `tblhistorialacademico`
  ADD CONSTRAINT `tblhistorialacademico_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`),
  ADD CONSTRAINT `tblhistorialacademico_ibfk_2` FOREIGN KEY (`Nivel_academico`) REFERENCES `catnivelacademico` (`ID`),
  ADD CONSTRAINT `tblhistorialacademico_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblhistorialcargos`
--
ALTER TABLE `tblhistorialcargos`
  ADD CONSTRAINT `tblhistorialcargos_ibfk_1` FOREIGN KEY (`CodEmpleado`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_2` FOREIGN KEY (`IdCargo`) REFERENCES `catcargos` (`ID`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_4` FOREIGN KEY (`RegistradoPor`) REFERENCES `tblempleado` (`Codigo`),
  ADD CONSTRAINT `tblhistorialcargos_ibfk_5` FOREIGN KEY (`AprobadoPor`) REFERENCES `tblempleado` (`Codigo`);

--
-- Constraints for table `tblmaquinariamedica`
--
ALTER TABLE `tblmaquinariamedica`
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_1` FOREIGN KEY (`tipoMaquina`) REFERENCES `catmaquinaria` (`ID`),
  ADD CONSTRAINT `tblmaquinariamedica_ibfk_2` FOREIGN KEY (`ubicacion`) REFERENCES `catsalaexamen` (`ID`);

--
-- Constraints for table `tblmovimientolote`
--
ALTER TABLE `tblmovimientolote`
  ADD CONSTRAINT `tblmovimientolote_ibfk_1` FOREIGN KEY (`idDetalleVenta`) REFERENCES `tbldetalleventafarmacia` (`idDetalleVentaFarmacia`),
  ADD CONSTRAINT `tblmovimientolote_ibfk_2` FOREIGN KEY (`idLote`) REFERENCES `loteproductos` (`idLote`);

--
-- Constraints for table `tblocupacionpacientes`
--
ALTER TABLE `tblocupacionpacientes`
  ADD CONSTRAINT `tblocupacionpacientes_ibfk_1` FOREIGN KEY (`CodPaciente`) REFERENCES `tblpaciente` (`CodigoP`);

--
-- Constraints for table `tblpaciente`
--
ALTER TABLE `tblpaciente`
  ADD CONSTRAINT `tblpaciente_ibfk_1` FOREIGN KEY (`CodPersona`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblpaciente_ibfk_2` FOREIGN KEY (`GrupoSanguineo`) REFERENCES `catgruposanguineo` (`ID`);

--
-- Constraints for table `tblpersona`
--
ALTER TABLE `tblpersona`
  ADD CONSTRAINT `tblpersona_ibfk_1` FOREIGN KEY (`Genero`) REFERENCES `catgenero` (`ID`),
  ADD CONSTRAINT `tblpersona_ibfk_2` FOREIGN KEY (`Estado_civil`) REFERENCES `catestadocivil` (`ID`),
  ADD CONSTRAINT `tblpersona_ibfk_3` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblprivilegiosusuario`
--
ALTER TABLE `tblprivilegiosusuario`
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_1` FOREIGN KEY (`CodPrivilegio`) REFERENCES `catprivilegio` (`Codigo`),
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_2` FOREIGN KEY (`CodUsuario`) REFERENCES `tblusuarios` (`Codigo`),
  ADD CONSTRAINT `tblprivilegiosusuario_ibfk_3` FOREIGN KEY (`CodigoSubModulo`) REFERENCES `catsubmodulos` (`CodSubModulo`);

--
-- Constraints for table `tblproveedores`
--
ALTER TABLE `tblproveedores`
  ADD CONSTRAINT `tblproveedores_ibfk_1` FOREIGN KEY (`EstadoProveedor`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblrecetaexamen`
--
ALTER TABLE `tblrecetaexamen`
  ADD CONSTRAINT `tblrecetaexamen_ibfk_1` FOREIGN KEY (`TipoExamen`) REFERENCES `catexamenesmedicos` (`ID`),
  ADD CONSTRAINT `tblrecetaexamen_ibfk_2` FOREIGN KEY (`ConsultaPrevia`) REFERENCES `tblconsulta` (`Codigo`);

--
-- Constraints for table `tblrecetamedicamentos`
--
ALTER TABLE `tblrecetamedicamentos`
  ADD CONSTRAINT `tblrecetamedicamentos_ibfk_1` FOREIGN KEY (`CodigoConsulta`) REFERENCES `tblconsulta` (`Codigo`);

--
-- Constraints for table `tblrecibosventa`
--
ALTER TABLE `tblrecibosventa`
  ADD CONSTRAINT `tblrecibosventa_ibfk_1` FOREIGN KEY (`Cliente`) REFERENCES `tblclientes` (`idCliente`),
  ADD CONSTRAINT `tblrecibosventa_ibfk_2` FOREIGN KEY (`aperturaCaja`) REFERENCES `tblaperturacaja` (`idApertura`);

--
-- Constraints for table `tblresultado`
--
ALTER TABLE `tblresultado`
  ADD CONSTRAINT `tblresultado_ibfk_1` FOREIGN KEY (`CodExamen`) REFERENCES `tblexamen` (`Codigo`);

--
-- Constraints for table `tblserviciosbrindados`
--
ALTER TABLE `tblserviciosbrindados`
  ADD CONSTRAINT `tblserviciosbrindados_ibfk_1` FOREIGN KEY (`tipoServicio`) REFERENCES `catservicios` (`idServicio`),
  ADD CONSTRAINT `tblserviciosbrindados_ibfk_2` FOREIGN KEY (`estadoServicio`) REFERENCES `catestadoservicios` (`idEstadoServicio`),
  ADD CONSTRAINT `tblserviciosbrindados_ibfk_3` FOREIGN KEY (`NumeroRecibo`) REFERENCES `tblrecibosventa` (`idRecibo`);

--
-- Constraints for table `tblsesion`
--
ALTER TABLE `tblsesion`
  ADD CONSTRAINT `tblsesion_ibfk_1` FOREIGN KEY (`CodUsuarioSesion`) REFERENCES `tblusuarios` (`Codigo`),
  ADD CONSTRAINT `tblsesion_ibfk_2` FOREIGN KEY (`EstadoSesion`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblsignosvitales`
--
ALTER TABLE `tblsignosvitales`
  ADD CONSTRAINT `tblsignosvitales_ibfk_1` FOREIGN KEY (`CodConsulta`) REFERENCES `tblconsulta` (`Codigo`),
  ADD CONSTRAINT `tblsignosvitales_ibfk_2` FOREIGN KEY (`CodEnfermera`) REFERENCES `tblempleado` (`Codigo`);

--
-- Constraints for table `tbltipodecambio`
--
ALTER TABLE `tbltipodecambio`
  ADD CONSTRAINT `tbltipodecambio_ibfk_1` FOREIGN KEY (`aperturaCaja`) REFERENCES `tblaperturacaja` (`idApertura`),
  ADD CONSTRAINT `tbltipodecambio_ibfk_2` FOREIGN KEY (`Moneda`) REFERENCES `catmoneda` (`idMoneda`);

--
-- Constraints for table `tblusuarios`
--
ALTER TABLE `tblusuarios`
  ADD CONSTRAINT `tblusuarios_ibfk_1` FOREIGN KEY (`CodPersonaU`) REFERENCES `tblpersona` (`Codigo`),
  ADD CONSTRAINT `tblusuarios_ibfk_2` FOREIGN KEY (`Estado`) REFERENCES `catestado` (`ID`);

--
-- Constraints for table `tblventafarmacia`
--
ALTER TABLE `tblventafarmacia`
  ADD CONSTRAINT `tblventafarmacia_ibfk_1` FOREIGN KEY (`idRecetaMedica`) REFERENCES `tblrecetamedicamentos` (`Codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
