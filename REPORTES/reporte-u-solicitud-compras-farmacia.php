<?php

function insertarSeparadorEstrellas($pdf)
{
	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 8, utf8_decode("************************************************************************************************"), 0, 0, 'C');
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
$idSolicitud = (isset($_GET['idSolicitud'])) ? $_GET['idSolicitud'] : 0;

//Instancia al controlador
require_once "../controladores/comprasFarmaciaControlador.php";
$controlador = new comprasFarmaciaControlador();
$solicitud = $controlador->obtener_solicitud_compra_controlador($idSolicitud);

if ($solicitud->rowCount() >= 1) {

	$solicitud = $solicitud->fetchAll();

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
	$pdf->Cell(0, 10, utf8_decode(strtoupper("SOLICITUD DE COMPRA")), 0, 0, 'C');
	$pdf->SetFont('Arial', '', 14);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(-35, 10, utf8_decode('Codigo de reporte'), '', 0, 'C');
	$pdf->Ln(10);

	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(COMPANY), 0, 0, 'C');
	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(-35, 10, utf8_decode("R_SC-00001"), '', 0, 'C');
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
	$pdf->Cell(0, 8, utf8_decode('Datos de la solicitud'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Solicitante: '), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($solicitud[0]['Nombres'] . ' ' . $solicitud[0]['Apellidos']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Estado:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($solicitud[0]['nombreEstado']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Fecha:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($solicitud[0]['fechaRegistro']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(25, 8, utf8_decode('Descripción:'), 0, 0);
	$pdf->Ln(8);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(200, 8, utf8_decode($solicitud[0]['descripcionSolicitud']), 0, 0);
	$pdf->Ln(8 + 2);

	$medicamentos = 0;
	$costoTotal = 0;
	foreach ($solicitud as $soli) {
		$medicamentos++;
		$costoTotal = $costoTotal + ($soli['costo'] * $soli['cantidad']);
	}

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(25, 8, utf8_decode('Valoración:'), 0, 0);
	$pdf->Ln(8);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(200, 8, utf8_decode("Esta compra significara un costo total de C$" . $costoTotal . " por la adquisición de " . $medicamentos . " diferentes medicamentos"), 0, 0);
	$pdf->Ln(8);
	$pdf->Cell(200, 8, utf8_decode("Acontinuacion se detalla la solicitud"), 0, 0);
	$pdf->Ln(8 + 2);

	if (count($solicitud) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$contadorConsultas = 0;
		if (count($solicitud) >= 1) {

			validarYsaltar($pdf, $footeSize, $footeMargin);

			if ($contadorConsultas == 0) {
				forzarSalto($pdf, $footeMargin);
			}

			insertarSeparadorEstrellas($pdf);
			$pdf->SetFont('Arial', 'B', 16);
			$pdf->SetTextColor(0, 107, 181);
			$pdf->Cell(0, 8, utf8_decode('Detalle'), 0, 0, 'C');
			$pdf->Ln(8 + 2);
			insertarSeparadorEstrellas($pdf);


			$contador = 0;
			foreach ($solicitud as $soli) {

				if ($contadorConsultas != 0) {
					/* forzarSalto($pdf, $footeMargin); */
					insertarSeparadorEstrellas($pdf);
					/* $pdf->SetFont('Arial', 'B', 16);
					$pdf->SetTextColor(0, 107, 181);
					$pdf->Cell(0, 8, utf8_decode('Detalle'), 0, 0, 'C');
					$pdf->Ln(8 + 2); */
				}



				/* if ($contadorConsultas != 0) {
					insertarSeparadorEstrellas($pdf);
				} */

				$pdf->SetFont('Arial', 'B', 12);
				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($leftTitleMargin, 8, utf8_decode('Medicamento:'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($soli['nombreComercial']), 0, 0);
				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($rightTitleMargin, 8, utf8_decode('Costo:'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode("C$".$soli['costo']), 0, 0);
				$pdf->Ln(8 + 2);


				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($leftTitleMargin, 8, utf8_decode('Laboratorio'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($soli['nombreLaboratorio']), 0, 0);
				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($rightTitleMargin, 8, utf8_decode('Cantidad:'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($soli['cantidad']), 0, 0);
				$pdf->Ln(8 + 2);

				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($leftTitleMargin, 8, utf8_decode('Proveedor:'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($soli['nombreProveedor']), 0, 0);
				$pdf->SetTextColor(33, 33, 33);
				$pdf->Cell($rightTitleMargin, 8, utf8_decode('Sub total:'), 0, 0);
				$pdf->SetTextColor(97, 97, 97);
				$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode("C$".$soli['costo'] * $soli['cantidad']), 0, 0);
				$pdf->Ln(8 + 2);

				$contadorConsultas++;
			}
		} else {
			validarYsaltar($pdf, $footeSize, $footeMargin);
			$pdf->SetFont('Arial', 'B', 14);
			$pdf->SetTextColor(155, 0, 0);
			$pdf->Cell(60, 8, utf8_decode('No hay nada que mostrar'), 0, 0);
			$pdf->Ln(8 + 2);
			validarYsaltar($pdf, $footeSize, $footeMargin);
		}
	}
	insertarSeparadorEstrellas($pdf);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Total:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode("C$".$costoTotal), 0, 0);
	$pdf->Ln(8+2);
	/* ############################################################## */


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

	$pdf->Output("I", "SOLICITUD DE COMPRA (" . $solicitud[0]['idSolicitudCompra'] . ").pdf", true);
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
					,el codigo es: <?php echo $idSolicitud; ?>
				</p>
			</div>
		</div>

		<?php
		include "../vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
		?>
	</body>

	</html>
<?php } ?>