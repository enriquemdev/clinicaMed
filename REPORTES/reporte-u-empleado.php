<?php
function insertarSeparadorEstrellas($pdf)
{
	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 8, utf8_decode("********************************************************************************************************"), 0, 0, 'C');
	$pdf->Ln(8);
}

function ValidarSaltoLinea($pdf, $footeSize)
{
	if ($pdf->GetY() > ($pdf->GetPageHeight() - $footeSize)) {
		return true;
	} else {
		return false;
	}
}

function forzarSalto($pdf, $footeMargin)
{
	$alto = $pdf->GetPageHeight();
	$pdf->SetY($alto - ($alto * $footeMargin));

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(0, 6, utf8_decode(COMPANY), 0, 0, 'C');
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(0, 6, utf8_decode(COMPANY_DIRECTION), 0, 0, 'C');
	$pdf->Ln(6);
	$pdf->Cell(0, 6, utf8_decode("Teléfono: " . COMPANY_PHONE), 0, 0, 'C');
	$pdf->Ln(6);
	$pdf->Cell(0, 6, utf8_decode("Correo: " . COMPANY_EMAIL), 0, 0, 'C');
	/*--------------------------------------*/

	$pdf->SetY(-15);
	$pdf->SetX(-15);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Write(5, $pdf->PageNo());

	$pdf->AddPage();
}

function validarYsaltar($pdf, $footeSize, $footeMargin)
{
	if ($pdf->GetY() > ($pdf->GetPageHeight() - $footeSize)) {
		$alto = $pdf->GetPageHeight();
		$pdf->SetY($alto - ($alto * $footeMargin));

		/*----------  INFO. EMPRESA  ----------*/
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->SetTextColor(33, 33, 33);
		$pdf->Cell(0, 6, utf8_decode(COMPANY), 0, 0, 'C');
		$pdf->Ln(6);
		$pdf->SetFont('Arial', '', 9);
		$pdf->Cell(0, 6, utf8_decode(COMPANY_DIRECTION), 0, 0, 'C');
		$pdf->Ln(6);
		$pdf->Cell(0, 6, utf8_decode("Teléfono: " . COMPANY_PHONE), 0, 0, 'C');
		$pdf->Ln(6);
		$pdf->Cell(0, 6, utf8_decode("Correo: " . COMPANY_EMAIL), 0, 0, 'C');
		$pdf->Ln(6);
		/*--------------------------------------*/

		$pdf->SetY(-15);
		$pdf->SetX(-15);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Write(5, $pdf->PageNo());

		$pdf->AddPage();
	}
}

//Acceso al usuario actual
session_start(['name' => 'SPM']);
$usuario = $_SESSION['usuario_spm'];

//Acceso a la configuracion del sistema
$peticionAjax = true;
require_once "../config/APP.php";

//Recibida de datos
$idEmpleado = (isset($_GET['idEmpleado'])) ? $_GET['idEmpleado'] : 0;
$idPersona = (isset($_GET['idPersona'])) ? $_GET['idPersona'] : 0;

//Instancia al controlador
require_once "../controladores/empleadosControlador.php";
$ins_empleado = new empleadosControlador();
$datos_empleado = $ins_empleado->datos_empleado_controlador($idEmpleado);
$datos_familiares_empleado = $ins_empleado->datos_familiares_empleado_controlador($idPersona);
$datos_especialidades_empleado = $ins_empleado->datos_especialidades_empleado_controlador($idEmpleado);
$cargoActual = $ins_empleado->obtener_ultimo_cargo_activo_empleado_controlador($idEmpleado);
$datos_estudios_academicos_empleado = $ins_empleado->datos_estudios_academicos_empleado_controlador($idEmpleado);
$cargos_empleado = $ins_empleado->obtener_cargos_empleado_controlador($idEmpleado);

if ($datos_empleado->rowCount() >= 1) {

	$codigoEmpleado;

	$datos_empleado = $datos_empleado->fetch();
	$datos_familiares_empleado = $datos_familiares_empleado->fetchAll();
	$datos_especialidades_empleado = $datos_especialidades_empleado->fetchAll();
	$cargoActual = $cargoActual->fetch();
	$datos_estudios_academicos_empleado = $datos_estudios_academicos_empleado->fetchAll();
	$cargos_empleado = $cargos_empleado->fetchAll();

	require "./fpdf.php";

	//Definición de constantes
	$leftTitleMargin = 50;
	$rightTitleMargin = 25;
	$descriptionOfLeftTitle = 80;
	$descriptionOfRightTitle = 60;
	$leftMarginOfDoc = 10;
	$TopMarginOfDoc = 17;
	$RightMarginOfDoc = 10;
	$footeMargin = 0.10; //Expresado en porcentajes 
	$footeSize = 55;

	$pdf = new FPDF('P', 'mm', 'Letter');

	$pdf->SetAutoPageBreak(false);

	$pdf->SetMargins($leftMarginOfDoc, $TopMarginOfDoc, $RightMarginOfDoc);

	$pdf->AddPage();

	$pdf->Image('../vistas/assets/img/logo_clinica.png', 10, 10, 30, 30, 'PNG');

	$pdf->SetFont('Arial', 'B', 20);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(strtoupper("Reporte de empleado")), 0, 0, 'C');

	$pdf->SetFont('Arial', '', 14);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(-35, 10, utf8_decode('Codigo de reporte'), '', 0, 'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(COMPANY), 0, 0, 'C');

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(-35, 10, utf8_decode("R_E-00001"), '', 0, 'C');

	$pdf->Ln(10);

	//Para obtener la fecha loco
	date_default_timezone_set("America/Managua");
	$date = date("d-m-y");

	insertarSeparadorEstrellas($pdf);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(40, 8, utf8_decode('Fecha de emisión:'), 0, 0);

	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(90, 8, $date, 0, 0);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(30, 8, utf8_decode('Emitido por:'), "", 0, 0);

	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(40, 8, utf8_decode($usuario), 0, 0);

	$pdf->Ln(8 + 2);

	insertarSeparadorEstrellas($pdf);

	$pdf->SetFont('Arial', 'B', 14);
	$pdf->SetTextColor(56, 151, 0);
	$pdf->Cell(0, 8, utf8_decode('Datos del empleado'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Nombre del empleado:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_empleado['NombresEmpleado'] . ' ' . $datos_empleado['ApellidosEmpleado']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Cedula:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_empleado['Cedula']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('INSS:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_empleado['INSS']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Teléfono:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_empleado['Telefono']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Cargo actual:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	if ($cargoActual == null) {
		$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode("Actualmente no ejerce algun cargo"), 0, 0);
	} else {
		$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($cargoActual['NombreCargo']), 0, 0);
	}
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(25, 8, utf8_decode('Dirección:'), 0, 0);
	$pdf->Ln(8);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(200, 8, utf8_decode($datos_empleado['Direccion']), 0, 0);
	$pdf->Ln(8 + 2);

	if (count($datos_familiares_empleado) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$pdf->SetFillColor(26, 85, 155);
		$pdf->SetDrawColor(26, 85, 155);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(200, 7, utf8_decode('Familiares'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$pdf->SetFillColor(38, 151, 208);
		$pdf->SetDrawColor(38, 151, 208);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(90, 7, utf8_decode('Nombre completo'), 1, 0, 'C', true);
		$pdf->Cell(60, 7, utf8_decode('Contacto'), 1, 0, 'C', true);
		$pdf->Cell(30, 7, utf8_decode('Parentesco'), 1, 0, 'C', true);
		$pdf->Cell(20, 7, utf8_decode('Tutor?'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$contador = 0;
		foreach ($datos_familiares_empleado as $datos_familiar) {
			$pdf->SetFont('Arial', '', 11);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell(90, 10, utf8_decode($datos_familiar['Nombres'] . ' ' . $datos_familiar['Apellidos']), 'LR', 0, 'C');
			if ($datos_familiar['Telefono'] != null) {
				$pdf->Cell(60, 10, utf8_decode($datos_familiar['Telefono']), 'LR', 0, 'C');
			} else {
				$pdf->Cell(60, 10, utf8_decode($datos_familiar['Email']), 'LR', 0, 'C');
			}
			$pdf->Cell(30, 10, utf8_decode($datos_familiar['Parentesco']), 'LR', 0, 'C');

			if ($datos_familiar['EsTutor'] == 1) {
				$esTutor = 'Si';
			} else {
				$esTutor = 'No';
			}

			$pdf->Cell(20, 10, utf8_decode($esTutor), 'LR', 0, 'C');
			$pdf->Ln(10);
			$pdf->Cell(200, null, null, 'T', null, null);
			$pdf->Ln(0);
			
			if ($contador == count($datos_familiares_empleado) - 1) {
				$pdf->Ln(2);
			} else {

				if (ValidarSaltoLinea($pdf, $footeSize)) {
					forzarSalto($pdf, $footeMargin);
					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 7, utf8_decode('Familiares'), 1, 0, 'C', true);
					$pdf->Ln(7);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Cell(90, 7, utf8_decode('Nombre completo'), 1, 0, 'C', true);
					$pdf->Cell(60, 7, utf8_decode('Contacto'), 1, 0, 'C', true);
					$pdf->Cell(30, 7, utf8_decode('Parentesco'), 1, 0, 'C', true);
					$pdf->Cell(20, 7, utf8_decode('Tutor?'), 1, 0, 'C', true);
					$pdf->Ln(7);
				}
			}
			$contador++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('Este empleado no tiene familiares'), 0, 0);
		$pdf->Ln(8 + 2);
		validarYsaltar($pdf, $footeSize, $footeMargin);
	}

	if (count($datos_especialidades_empleado) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$pdf->SetFillColor(26, 85, 155);
		$pdf->SetDrawColor(26, 85, 155);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(200, 7, utf8_decode('Especialidades'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$pdf->SetFillColor(38, 151, 208);
		$pdf->SetDrawColor(38, 151, 208);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(30, 7, utf8_decode('Especialidad'), 1, 0, 'C', true);
		$pdf->Cell(170, 7, utf8_decode('Descripción especialidad'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$contador = 0;
		foreach ($datos_especialidades_empleado as $datos_especialidad) {
			$pdf->SetFont('Arial', '', 11);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell(30, 10, utf8_decode($datos_especialidad['NombreEspecialidad']), 'LR', 0, 'C');
			$pdf->Cell(170, 10, utf8_decode($datos_especialidad['Descripcion']), 'LR', 0, 'C');
			$pdf->Ln(10);
			$pdf->Cell(200, null, null, 'T', null, null);
			$pdf->Ln(2);

			if ($contador == count($datos_especialidades_empleado) - 1) {
				$pdf->Ln(2);
			} else {
				if (ValidarSaltoLinea($pdf, $footeSize)) {
					forzarSalto($pdf, $footeMargin);

					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 7, utf8_decode('Familiares'), 1, 0, 'C', true);
					$pdf->Ln(7);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Cell(30, 7, utf8_decode('Especialidad'), 1, 0, 'C', true);
					$pdf->Cell(170, 7, utf8_decode('Descripción especialidad'), 1, 0, 'C', true);
					$pdf->Ln(7);
				}
			}
			$contador++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('Este empleado no tiene especialidades'), 0, 0);
		$pdf->Ln(8 + 2);
		validarYsaltar($pdf, $footeSize, $footeMargin);
	}

	if (count($datos_estudios_academicos_empleado) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$pdf->SetFillColor(26, 85, 155);
		$pdf->SetDrawColor(26, 85, 155);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(200, 7, utf8_decode('Estudios academicos'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$pdf->SetFillColor(38, 151, 208);
		$pdf->SetDrawColor(38, 151, 208);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(80, 7, utf8_decode('Estudio'), 1, 0, 'C', true);
		$pdf->Cell(50, 7, utf8_decode('Tipo estudio'), 1, 0, 'C', true);
		$pdf->Cell(70, 7, utf8_decode('Institución'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$contador = 0;
		foreach ($datos_estudios_academicos_empleado as $estudio_academico) {
			$pdf->SetFont('Arial', '', 11);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell(80, 10, utf8_decode($estudio_academico['NombreEstudio']), 'LR', 0, 'C');
			$pdf->Cell(50, 10, utf8_decode($estudio_academico['NombreNivelAcademico']), 'LR', 0, 'C');
			$pdf->Cell(70, 10, utf8_decode($estudio_academico['Institucion']), 'LR', 0, 'C');

			$pdf->Ln(10);
			$pdf->Cell(200, null, null, 'T', null, null);
			$pdf->Ln(2);
			if ($contador == count($datos_estudios_academicos_empleado) - 1) {
				$pdf->Ln(2);
			} else {
				if (ValidarSaltoLinea($pdf, $footeSize)) {
					forzarSalto($pdf, $footeMargin);

					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 7, utf8_decode('Estudios academicos'), 1, 0, 'C', true);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Ln(7);

					$pdf->Cell(80, 7, utf8_decode('Estudio'), 1, 0, 'C', true);
					$pdf->Cell(50, 7, utf8_decode('Tipo estudio'), 1, 0, 'C', true);
					$pdf->Cell(70, 7, utf8_decode('Institución'), 1, 0, 'C', true);
					$pdf->Ln(7);
				}
			}
			$contador++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('Este empleado no tiene estudios academicos'), 0, 0);
		$pdf->Ln(8 + 2);
		validarYsaltar($pdf, $footeSize, $footeMargin);
	}

	if (count($cargos_empleado) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$pdf->SetFillColor(26, 85, 155);
		$pdf->SetDrawColor(26, 85, 155);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(200, 7, utf8_decode('Historial de cargos del empleado'), 1, 0, 'C', true);

		$pdf->SetFillColor(38, 151, 208);
		$pdf->SetDrawColor(38, 151, 208);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Ln(7);

		$pdf->Cell(80, 7, utf8_decode('Nombre cargo'), 1, 0, 'C', true);
		$pdf->Cell(50, 7, utf8_decode('Fecha asignación'), 1, 0, 'C', true);
		$pdf->Cell(40, 7, utf8_decode('Salario'), 1, 0, 'C', true);
		$pdf->Cell(30, 7, utf8_decode('Estado'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$contador = 0;
		foreach ($cargos_empleado as $cargo) {
			$pdf->SetFont('Arial', '', 11);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell(80, 10, utf8_decode($cargo['NombreCargo']), 'LR', 0, 'C');
			$pdf->Cell(50, 10, utf8_decode($cargo['FechaAsignacion']), 'LR', 0, 'C');
			$pdf->Cell(40, 10, utf8_decode($cargo['Salario']), 'LR', 0, 'C');
			if ($cargo['Estado'] == 1) {
				$pdf->Cell(30, 10, utf8_decode('Activo'), 'LR', 0, 'C');
			} else {
				$pdf->Cell(30, 10, utf8_decode('Inactivo'), 'LR', 0, 'C');
			}

			$pdf->Ln(10);
			$pdf->Cell(200, null, null, 'T', null, null);
			$pdf->Ln(2);

			if ($contador == count($cargos_empleado) - 1) {
				$pdf->Ln(2);
			} else {

				if (ValidarSaltoLinea($pdf, $footeSize)) {
					forzarSalto($pdf, $footeMargin);

					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 7, utf8_decode('Historial de cargos del empleado'), 1, 0, 'C', true);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Ln(7);

					$pdf->Cell(80, 7, utf8_decode('Nombre cargo'), 1, 0, 'C', true);
					$pdf->Cell(50, 7, utf8_decode('Fecha asignación'), 1, 0, 'C', true);
					$pdf->Cell(40, 7, utf8_decode('Salario'), 1, 0, 'C', true);
					$pdf->Cell(30, 7, utf8_decode('Estado'), 1, 0, 'C', true);
					$pdf->Ln(7);
				}
			}
			$contador++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('Este empleado no tiene cargos que mostrar'), 0, 0);
		$pdf->Ln(8 + 2);
		validarYsaltar($pdf, $footeSize, $footeMargin);
	}

	$alto = $pdf->GetPageHeight();
	$pdf->SetY($alto - ($alto * $footeMargin));

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(0, 6, utf8_decode(COMPANY), 0, 0, 'C');

	$pdf->Ln(6);

	$pdf->SetFont('Arial', '', 9);
	$pdf->Cell(0, 6, utf8_decode(COMPANY_DIRECTION), 0, 0, 'C');

	$pdf->Ln(6);

	$pdf->Cell(0, 6, utf8_decode("Teléfono: " . COMPANY_PHONE), 0, 0, 'C');

	$pdf->Ln(6);

	$pdf->Cell(0, 6, utf8_decode("Correo: " . COMPANY_EMAIL), 0, 0, 'C');


	$pdf->SetY(-15);
	$pdf->SetX(-15);
	$pdf->SetFont('Arial', 'B', 14);
	$pdf->Write(5, $pdf->PageNo());

	$pdf->Output("I", "Reporte de empleado (" . $datos_empleado['CodigoEmpleado'] . ").pdf", true);
} else {
?>

	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo COMPANY; ?></title>
		<?php
		include "../vistas/inc/link.php"; /* SE INCLUYE REFERENCIA A LINKS */
		?>
	</head>

	<body>
		<div class="full-box container-404">
			<div>
				<p class="text-center"><i class="fas fa-rocket fa-10x"></i></p>
				<h1 class="text-center">Ocurrio un error</h1>
				<p class="lead text-center">No se pudo generar el reporte de este empleado
					,el codigo es: <?php echo $idEmpleado; ?>
				</p>
			</div>
		</div>

		<?php
		include "../vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
		?>
	</body>

	</html>
<?php } ?>