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
$id = (isset($_GET['idVenta'])) ? $_GET['idVenta'] : 0;

//Instancia al controlador
require_once "../controladores/ventasFarmaciaControlador.php";
$controlador = new ventasFarmacia();
$datosRecibo = $controlador->obtener_recibo_controlador($id);

if ($datosRecibo->rowCount() >= 1) {
	/* $datosReciboUnico = $datosRecibo->fetch(); */
	$datosRecibo = $datosRecibo->fetchAll();

	require "./fpdf.php";

	//Definición de constantes
	$leftTitleMargin = 25;
	$rightTitleMargin = 35;
	$descriptionOfLeftTitle = 100;
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
	$pdf->Cell(0, 10, utf8_decode(strtoupper("Recibo de venta")), 0, 0, 'C');

	$pdf->SetFont('Arial', '', 14);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(-35, 10, utf8_decode('Codigo de reporte'), '', 0, 'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(COMPANY), 0, 0, 'C');

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(-35, 10, utf8_decode("R_RV-00001"), '', 0, 'C');

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
	$pdf->Cell(0, 8, utf8_decode('Datos del recibo'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Cliente:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datosRecibo[0]['Nombres'] . ' ' . $datosRecibo[0]['Apellidos']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Recibo:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datosRecibo[0]['idRecibo']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Fecha:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datosRecibo[0]['FyHRegistro']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Apertura caja:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datosRecibo[0]['aperturaCaja']), 0, 0);
	$pdf->Ln(8);



	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Cajero:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datosRecibo[0]['NombreEmpleado'] . ' ' . $datosRecibo[0]['ApellidosEmpleado']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Caja:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datosRecibo[0]['nombreCaja']), 0, 0);
	$pdf->Ln(8 + 10);

	if (count($datosRecibo) >= 1) {

		validarYsaltar($pdf, $footeSize, $footeMargin);

		$pdf->SetFillColor(26, 85, 155);
		$pdf->SetDrawColor(26, 85, 155);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->Cell(200, 7, utf8_decode('Servicios brindados'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$pdf->SetFillColor(38, 151, 208);
		$pdf->SetDrawColor(38, 151, 208);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->SetFont('Arial', 'B', 12);
		$pdf->Cell(45, 7, utf8_decode('Servicio'), 1, 0, 'C', true);
		$pdf->Cell(30, 7, utf8_decode('Monto'), 1, 0, 'C', true);
		$pdf->Cell(25, 7, utf8_decode('Rebaja'), 1, 0, 'C', true);
		$pdf->Cell(50, 7, utf8_decode('Método pago'), 1, 0, 'C', true);
		$pdf->Cell(25, 7, utf8_decode('Fecha'), 1, 0, 'C', true);
		$pdf->Cell(25, 7, utf8_decode('Total'), 1, 0, 'C', true);
		$pdf->Ln(7);

		$contador = 0;
		$total = 0;
		foreach ($datosRecibo as $recibo) {
			$total += $recibo['montoServicio'] - $recibo['RebajaPago'];
			$pdf->SetFont('Arial', '', 11);
			$pdf->SetTextColor(97, 97, 97);
			$fec = explode(" ", $recibo['fechaYHora'])[0];
			$pdf->Cell(45, 10, utf8_decode($recibo['nombreServicio']), 'LR', 0, 'C');
			$pdf->Cell(30, 10, utf8_decode("C$" . $recibo['montoServicio']), 'LR', 0, 'C');
			$pdf->Cell(25, 10, utf8_decode("C$" . $recibo['RebajaPago']), 'LR', 0, 'C');
			$pdf->Cell(50, 10, utf8_decode($recibo['NombreMetodoPago']), 'LR', 0, 'C');
			$pdf->Cell(25, 10, utf8_decode($fec), 'LR', 0, 'C');
			$pdf->Cell(25, 10, utf8_decode("C$" . $recibo['montoServicio'] - $recibo['RebajaPago']), 'LR', 0, 'C');
			$pdf->Ln(10);
			$pdf->Cell(200, null, null, 'T', null, null);
			$pdf->Ln(0);

			if ($contador == count($datosRecibo) - 1) {
				/* $pdf->Ln(2); */
				$pdf->SetFillColor(38, 151, 208);
				$pdf->SetDrawColor(38, 151, 208);
				$pdf->SetTextColor(255, 255, 255);
				$pdf->SetFont('Arial', 'B', 12);
				$pdf->SetX(160);
				$pdf->Cell(25, 7, utf8_decode('Total neto'), 1, 0, 'C', true);
				$pdf->Cell(25, 7, utf8_decode("C$" . $total), 1, 0, 'C', true);
			} else {

				if (ValidarSaltoLinea($pdf, $footeSize)) {
					forzarSalto($pdf, $footeMargin);
					$pdf->SetFillColor(26, 85, 155);
					$pdf->SetDrawColor(26, 85, 155);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 14);
					$pdf->Cell(200, 7, utf8_decode('Servicios brindados'), 1, 0, 'C', true);
					$pdf->Ln(7);

					$pdf->SetFillColor(38, 151, 208);
					$pdf->SetDrawColor(38, 151, 208);
					$pdf->SetTextColor(255, 255, 255);
					$pdf->SetFont('Arial', 'B', 12);
					$pdf->Cell(45, 7, utf8_decode('Servicio'), 1, 0, 'C', true);
					$pdf->Cell(30, 7, utf8_decode('Monto'), 1, 0, 'C', true);
					$pdf->Cell(25, 7, utf8_decode('Rebaja'), 1, 0, 'C', true);
					$pdf->Cell(50, 7, utf8_decode('Método pago'), 1, 0, 'C', true);
					$pdf->Cell(25, 7, utf8_decode('Fecha'), 1, 0, 'C', true);
					$pdf->Cell(25, 7, utf8_decode('Total'), 1, 0, 'C', true);
					$pdf->Ln(7);
				}
			}
			$contador++;
		}
	} else {
		validarYsaltar($pdf, $footeSize, $footeMargin);
		$pdf->SetFont('Arial', 'B', 14);
		$pdf->SetTextColor(155, 0, 0);
		$pdf->Cell(60, 8, utf8_decode('No hay servicios brindados que mostrar'), 0, 0);
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

	$pdf->Output("I", "Recibo de venta (" . $id . ").pdf", true);
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
				<p class="lead text-center">No se pudo generar el recibo de venta
					,el codigo es: <?php echo $id; ?>
				</p>
			</div>
		</div>

		<?php
		include "../vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
		?>
	</body>

	</html>
<?php } ?>