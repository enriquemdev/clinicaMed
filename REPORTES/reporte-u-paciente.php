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
$idPaciente = (isset($_GET['idPaciente'])) ? $_GET['idPaciente'] : 0;
$idPersona = (isset($_GET['idPersona'])) ? $_GET['idPersona'] : 0;

//Instancia al controlador
require_once "../controladores/pacienteControlador.php";
$ins_paciente = new pacienteControlador();
$datos_paciente = $ins_paciente->datos_paciente_controlador($idPaciente);
$datos_familiares_paciente = $ins_paciente->datos_familiares_paciente_controlador($idPersona);
$consultas_paciente = $ins_paciente->consultas_paciente_controlador($idPaciente);

if ($datos_paciente->rowCount() >= 1) {

	$datos_paciente = $datos_paciente->fetch();
	$datos_familiares_paciente = $datos_familiares_paciente->fetchAll();
	$consultas_paciente = $consultas_paciente->fetchAll();

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
	$pdf->Cell(0, 10, utf8_decode(strtoupper("Reporte de paciente")), 0, 0, 'C');
	$pdf->SetFont('Arial', '', 14);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(-35, 10, utf8_decode('Codigo de reporte'), '', 0, 'C');
	$pdf->Ln(10);

	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(COMPANY), 0, 0, 'C');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(-35, 10, utf8_decode("R_P-00001"), '', 0, 'C');
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
	$pdf->Ln(10);

	insertarSeparadorEstrellas($pdf);

	$pdf->SetFont('Arial', 'B', 14);
	$pdf->SetTextColor(56, 151, 0);
	$pdf->Cell(0, 8, utf8_decode('Datos del paciente'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Nombre del paciente:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_paciente['Nombres'] . ' ' . $datos_paciente['Apellidos']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Cedula:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_paciente['Cedula']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('INSS:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_paciente['INSS']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Teléfono:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_paciente['Telefono']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Email:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_paciente['Email']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Edad:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode(mainModel::calculaedad($datos_paciente['Fecha_de_nacimiento'])), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Grupo sanguineo:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_paciente['GrupoSanguineo']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(25, 8, utf8_decode('Dirección:'), 0, 0);
	$pdf->Ln(8);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(200, 8, utf8_decode($datos_paciente['Direccion']), 0, 0);
	$pdf->Ln(8 + 2);

	if (count($datos_familiares_paciente) >= 1) {

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
		foreach ($datos_familiares_paciente as $datos_familiar) {
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

			if ($contador == count($datos_familiares_paciente) - 1) {
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
		$pdf->Cell(60, 8, utf8_decode('Este paciente no tiene familiares'), 0, 0);
		$pdf->Ln(8 + 2);
		validarYsaltar($pdf, $footeSize, $footeMargin);
	}

	$contadorConsultas = 0;
	if (count($consultas_paciente) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		if ($contadorConsultas == 0) {
			forzarSalto($pdf, $footeMargin);
		}

		insertarSeparadorEstrellas($pdf);
		$pdf->SetFont('Arial', 'B', 16);
		$pdf->SetTextColor(0, 107, 181);
		$pdf->Cell(0, 8, utf8_decode('Expediente clinico del paciente'), 0, 0, 'C');
		$pdf->Ln(8 + 2);
		insertarSeparadorEstrellas($pdf);


		$contador = 0;
		foreach ($consultas_paciente as $consulta_paciente) {

			if ($contadorConsultas != 0) {
				forzarSalto($pdf, $footeMargin);
				insertarSeparadorEstrellas($pdf);
			}

			$pdf->SetFont('Arial', 'B', 14);
			$pdf->SetTextColor(56, 151, 0);
			$pdf->Cell(0, 8, utf8_decode('Consulta con codigo: ' . $consulta_paciente['Codigo']), 0, 0, 'C');
			$pdf->Ln(8 + 2);

			if ($contadorConsultas != 0) {
				insertarSeparadorEstrellas($pdf);
			}

			$pdf->SetFont('Arial', 'B', 12);
			$pdf->SetTextColor(33, 33, 33);
			$pdf->Cell($leftTitleMargin, 8, utf8_decode('Medico atendio:'), 0, 0);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($consulta_paciente['NombresMedico'] . ' ' . $consulta_paciente['ApellidosMedico']), 0, 0);
			$pdf->SetTextColor(33, 33, 33);
			$pdf->Cell($rightTitleMargin, 8, utf8_decode('Cita:'), 0, 0);
			$pdf->SetTextColor(97, 97, 97);
			if ($consulta_paciente['IdCita'] == 0) {
				$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode('No'), 0, 0);
			} else {
				$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode('Si'), 0, 0);
			}
			$pdf->Ln(8 + 2);

			$pdf->SetTextColor(33, 33, 33);
			$pdf->Cell($leftTitleMargin, 8, utf8_decode('Consultorio:'), 0, 0);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($consulta_paciente['NombreConsultorio']), 0, 0);
			$pdf->SetTextColor(33, 33, 33);
			$pdf->Cell($rightTitleMargin, 8, utf8_decode('Fecha:'), 0, 0);
			$pdf->SetTextColor(97, 97, 97);
			$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($consulta_paciente['FechaYHora']), 0, 0);
			$pdf->Ln(8 + 2);

			$diagnostico_paciente = $ins_paciente->diagnostico_paciente_controlador($consulta_paciente['Codigo']);
			if ($diagnostico_paciente->rowCount() >= 1) {
				$diagnostico_paciente = $diagnostico_paciente->fetchAll();
				if (count($diagnostico_paciente) >= 1) {

					validarYsaltar($pdf, $footeSize, $footeMargin);

					$contador = 0;
					foreach ($diagnostico_paciente as $diagnostico) {

						if ($contador != 0) {
							forzarSalto($pdf, $footeMargin);
							insertarSeparadorEstrellas($pdf);
						}

						$pdf->SetFont('Arial', 'B', 14);
						$pdf->SetTextColor(33, 33, 33);
						$titulo = 'Diagnostico: "' . $diagnostico['Codigo'] . '" para la consulta: "' . $consulta_paciente['Codigo'] . '"';
						$pdf->Cell(0, 8, utf8_decode($titulo), 0, 0, 'C');
						$pdf->Ln(8 + 2);

						if($contador!=0){
							insertarSeparadorEstrellas($pdf);
						}

						$pdf->SetFont('Arial', 'B', 12);
						$pdf->SetTextColor(33, 33, 33);
						$pdf->Cell($leftTitleMargin, 8, utf8_decode('Enfermedad: '), 0, 0);
						$pdf->SetTextColor(97, 97, 97);
						$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($diagnostico['NombreEnfermedad']), 0, 0);
						$pdf->Ln(8 + 2);

						$pdf->SetTextColor(33, 33, 33);
						$pdf->Cell($leftTitleMargin, 8, utf8_decode('Nota del diagnostico:'), 0, 0);
						$pdf->Ln(8);
						$pdf->SetTextColor(97, 97, 97);
						if ($diagnostico['Nota'] != null) {
							$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($diagnostico['Nota']), 0, 0);
							$pdf->Ln(8);
						}

						$diagnostico_sintomas_paciente = $ins_paciente->diagnostico_sintomas_paciente_controlador($diagnostico['Codigo']);
						if ($diagnostico_sintomas_paciente->rowCount() >= 1) {
							$diagnostico_sintomas_paciente = $diagnostico_sintomas_paciente->fetchAll();
							if (count($diagnostico_sintomas_paciente) >= 1) {

								validarYsaltar($pdf, $footeSize, $footeMargin);

								$pdf->SetFillColor(26, 85, 155);
								$pdf->SetDrawColor(26, 85, 155);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 14);
								$titulo = 'Sintomas del diagnostico "' . $diagnostico['Codigo'] . '"';
								$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
								$pdf->Ln(7);

								$pdf->SetFillColor(38, 151, 208);
								$pdf->SetDrawColor(38, 151, 208);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 12);
								$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
								$pdf->Cell(90, 7, utf8_decode('Sintoma'), 1, 0, 'C', true);
								$pdf->Cell(100, 7, utf8_decode('Descripcion del sintoma'), 1, 0, 'C', true);
								$pdf->Ln(7);

								$contador = 0;
								foreach ($diagnostico_sintomas_paciente as $sintoma) {


									$pdf->SetFont('Arial', '', 11);
									$pdf->SetTextColor(97, 97, 97);
									$pdf->Cell(10, 7, utf8_decode($sintoma['sintoma']), 'LR', 0, 'C');
									$pdf->Cell(90, 7, utf8_decode($sintoma['nombreSintoma']), 'LR', 0, 'C');
									$pdf->Cell(100, 7, utf8_decode($sintoma['descripcionSintoma']), 'LR', 0, 'C');
									$pdf->Ln(7);
									$pdf->Cell(200, null, null, 'T', null, null);
									$pdf->Ln(0);

									if ($contador == count($diagnostico_sintomas_paciente) - 1) {
										//La linea de cierre de la tabla mi loco
										/* $pdf->Cell(200, null, null, 'T', null, null); */
										$pdf->Ln(0 + 2);
									} else {
										if (ValidarSaltoLinea($pdf, $footeSize, $footeMargin)) {
											/* $pdf->Cell(200, null, null, 'T', null, null);
											$pdf->Ln(0 + 2); */
											forzarSalto($pdf, $footeMargin);

											$pdf->SetFillColor(26, 85, 155);
											$pdf->SetDrawColor(26, 85, 155);
											$pdf->SetTextColor(255, 255, 255);
											$pdf->SetFont('Arial', 'B', 14);
											$titulo = 'Sintomas del diagnostico "' . $diagnostico['Codigo'] . '"';
											$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
											$pdf->Ln(7);

											$pdf->SetFillColor(38, 151, 208);
											$pdf->SetDrawColor(38, 151, 208);
											$pdf->SetTextColor(255, 255, 255);
											$pdf->SetFont('Arial', 'B', 12);
											$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
											$pdf->Cell(90, 7, utf8_decode('Sintoma'), 1, 0, 'C', true);
											$pdf->Cell(100, 7, utf8_decode('Descripcion del sintoma'), 1, 0, 'C', true);
											$pdf->Ln(7);
										}
									}
									$contador++;
								}
							} else {
								validarYsaltar($pdf, $footeSize, $footeMargin);
								$pdf->SetFont('Arial', 'B', 14);
								$pdf->SetTextColor(155, 0, 0);
								$titulo = 'El diagnostico con codigo "' . $diagnostico['Codigo'] . '" no tiene sintomas';
								$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
								$pdf->Ln(8 + 2);
								validarYsaltar($pdf, $footeSize, $footeMargin);
							}
						} else {
							validarYsaltar($pdf, $footeSize, $footeMargin);
							$pdf->SetFont('Arial', 'B', 14);
							$pdf->SetTextColor(155, 0, 0);
							$titulo = 'El diagnostico con codigo "' . $diagnostico['Codigo'] . '" no tiene sintomas';
							$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
							$pdf->Ln(8 + 2);
							validarYsaltar($pdf, $footeSize, $footeMargin);
						}
						if ($contador == count($diagnostico_paciente) - 1) {
							//La linea de cierre de la tabla mi loco
							/* $pdf->Ln(5); */
							//$pdf->Cell(200,null,null,'T',null,null);
						} else {
							validarYsaltar($pdf, $footeSize, $footeMargin);
						}
						$contador++;
					}
				} else {
					ValidarSaltoLinea($pdf, $footeSize, $footeMargin);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->SetTextColor(155, 0, 0);
					$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene diagnosticos';
					$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
					$pdf->Ln(8 + 2);
					ValidarSaltoLinea($pdf, $footeSize, $footeMargin);
				}
			} else {
				ValidarSaltoLinea($pdf, $footeSize, $footeMargin);
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->SetTextColor(155, 0, 0);
				$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene diagnosticos';
				$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
				$pdf->Ln(8 + 2);
				ValidarSaltoLinea($pdf, $footeSize, $footeMargin);
			}

			$recetas_medicamento_paciente = $ins_paciente->recetas_medicamento_paciente_controlador($consulta_paciente['Codigo']);
			if ($recetas_medicamento_paciente->rowCount() >= 1) {
				$recetas_medicamento_paciente = $recetas_medicamento_paciente->fetchAll();
				if (count($recetas_medicamento_paciente) >= 1) {

					validarYsaltar($pdf, $footeSize, $footeMargin);


					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);

					$titulo = 'Recetas medicas del diagnostico "' . $diagnostico['Codigo'] . '"';
					$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
					$pdf->Ln(7);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
					$pdf->Cell(40, 7, utf8_decode('Fecha'), 1, 0, 'C', true);
					$pdf->Cell(60, 7, utf8_decode('Medicamento'), 1, 0, 'C', true);
					$pdf->Cell(90, 7, utf8_decode('Dosis'), 1, 0, 'C', true);
					$pdf->Ln(7);

					$contador = 0;
					foreach ($recetas_medicamento_paciente as $receta) {

						$pdf->SetFont('Arial', '', 11);
						$pdf->SetTextColor(97, 97, 97);
						$pdf->Cell(10, 7, utf8_decode($receta['Codigo']), 'LR', 0, 'C');
						$pdf->Cell(40, 7, utf8_decode($receta['FechaEmision']), 'LR', 0, 'C');
						$pdf->Cell(60, 7, utf8_decode($receta['NombreComercial']), 'LR', 0, 'C');
						$pdf->Cell(90, 7, utf8_decode($receta['Dosis'] . ' cada ' . $receta['Frecuencia']), 'LR', 0, 'C');
						$pdf->Ln(7);
						$pdf->Cell(200, null, null, 'T', null, null);
						$pdf->Ln(0);
						if ($contador == count($recetas_medicamento_paciente) - 1) {
							//La linea de cierre de la tabla mi loco
							/* $pdf->Cell(200, null, null, 'T', null, null); */
							$pdf->Ln(0 + 2);
						} else {
							if (ValidarSaltoLinea($pdf, $footeSize, $footeMargin)) {
								/* $pdf->Cell(200, null, null, 'T', null, null);
								$pdf->Ln(0 + 2); */
								forzarSalto($pdf, $footeMargin);
								$pdf->SetFillColor(26, 85, 155);
								$pdf->SetDrawColor(26, 85, 155);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 14);

								$titulo = 'Recetas medicas del diagnostico "' . $diagnostico['Codigo'] . '"';
								$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
								$pdf->Ln(7);

								$pdf->SetFillColor(38, 151, 208);
								$pdf->SetDrawColor(38, 151, 208);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 12);
								$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
								$pdf->Cell(40, 7, utf8_decode('Fecha'), 1, 0, 'C', true);
								$pdf->Cell(60, 7, utf8_decode('Medicamento'), 1, 0, 'C', true);
								$pdf->Cell(90, 7, utf8_decode('Dosis'), 1, 0, 'C', true);
								$pdf->Ln(7);
							}
						}
						$contador++;
					}
				} else {
					validarYsaltar($pdf, $footeSize, $footeMargin);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->SetTextColor(155, 0, 0);
					$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene recetas medicas';
					$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
					$pdf->Ln(8 + 2);
					validarYsaltar($pdf, $footeSize, $footeMargin);
				}
			} else {
				validarYsaltar($pdf, $footeSize, $footeMargin);
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->SetTextColor(155, 0, 0);
				$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene recetas medicas';
				$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
				$pdf->Ln(8 + 2);
				validarYsaltar($pdf, $footeSize, $footeMargin);
			}

			$recetas_examen_paciente = $ins_paciente->recetas_examen_paciente_controlador($consulta_paciente['Codigo']);
			if ($recetas_examen_paciente->rowCount() >= 1) {
				$recetas_examen_paciente = $recetas_examen_paciente->fetchAll();
				if (count($recetas_examen_paciente) >= 1) {

					validarYsaltar($pdf, $footeSize, $footeMargin);

					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$titulo = 'Recetas de examen del diagnostico: "' . $diagnostico['Codigo'] . '"';
					$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
					$pdf->Ln(7);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
					$pdf->Cell(70, 7, utf8_decode('Examen'), 1, 0, 'C', true);
					$pdf->Cell(120, 7, utf8_decode('Motivo'), 1, 0, 'C', true);
					$pdf->Ln(7);

					$contador = 0;
					foreach ($recetas_examen_paciente as $recetaExamen) {

						$pdf->SetFont('Arial', '', 11);
						$pdf->SetTextColor(97, 97, 97);
						$pdf->Cell(10, 7, utf8_decode($recetaExamen['Codigo']), 'LR', 0, 'C');
						$pdf->Cell(70, 7, utf8_decode($recetaExamen['NombreExamen']), 'LR', 0, 'C');
						$pdf->Cell(120, 7, utf8_decode($recetaExamen['Motivo']), 'LR', 0, 'C');
						$pdf->Ln(7);
						$pdf->Cell(200, null, null, 'T', null, null);
						$pdf->Ln(0);

						if ($contador == count($recetas_examen_paciente) - 1) {
							//La linea de cierre de la tabla mi loco
							/* $pdf->Cell(200, null, null, 'T', null, null); */
							$pdf->Ln(0 + 2);
						} else {
							if (ValidarSaltoLinea($pdf, $footeSize, $footeMargin)) {
								/* $pdf->Cell(200, null, null, 'T', null, null);
								$pdf->Ln(0 + 2); */
								forzarSalto($pdf, $footeMargin);
								$pdf->SetFillColor(26, 85, 155);
								$pdf->SetDrawColor(26, 85, 155);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 14);
								$titulo = 'Recetas de examen del diagnostico: "' . $recetaExamen['Codigo'] . '"';
								$pdf->Cell(200, 7, utf8_decode($titulo), 1, 0, 'C', true);
								$pdf->Ln(7);

								$pdf->SetFillColor(38, 151, 208);
								$pdf->SetDrawColor(38, 151, 208);
								$pdf->SetTextColor(255, 255, 255);
								$pdf->SetFont('Arial', 'B', 12);
								$pdf->Cell(10, 7, utf8_decode('#'), 1, 0, 'C', true);
								$pdf->Cell(70, 7, utf8_decode('Examen'), 1, 0, 'C', true);
								$pdf->Cell(120, 7, utf8_decode('Motivo'), 1, 0, 'C', true);
								$pdf->Ln(7);
							}
						}
						$contador++;
					}
				} else {
					validarYsaltar($pdf, $footeSize, $footeMargin);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->SetTextColor(155, 0, 0);
					$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene recetas de examen';
					$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
					$pdf->Ln(8 + 2);
					validarYsaltar($pdf, $footeSize, $footeMargin);
				}
			} else {
				validarYsaltar($pdf, $footeSize, $footeMargin);
				$pdf->SetFont('Arial', 'B', 14);
				$pdf->SetTextColor(155, 0, 0);
				$titulo = 'La consulta con codigo "' . $consulta_paciente['Codigo'] . '" no tiene recetas de examen';
				$pdf->Cell(60, 8, utf8_decode($titulo), 0, 0);
				$pdf->Ln(8 + 2);
				validarYsaltar($pdf, $footeSize, $footeMargin);
			}
			$contadorConsultas++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('Este paciente no ha pasado consulta'), 0, 0);
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

	$pdf->Output("I", "Reporte de paciente (" . $datos_paciente['CodigoP'] . ").pdf", true);
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
					,el codigo es: <?php echo $idPaciente; ?>
				</p>
			</div>
		</div>

		<?php
		include "../vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
		?>
	</body>

	</html>
<?php } ?>