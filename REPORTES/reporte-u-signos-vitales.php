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
$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

//Instancia al controlador
require_once "../controladores/signosvitalesControlador.php";
$ins_signos_vitales = new signosvitalesControlador();
$datos_signos_vitales = $ins_signos_vitales->reporte_signos_vitales_controlador($id);

if ($datos_signos_vitales->rowCount() >= 1) {

	$datos_signos_vitales = $datos_signos_vitales->fetch();

	require "./fpdf.php";

	//Definición de constantes
	$leftTitleMargin = 55;
	$rightTitleMargin = 30;
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
	$pdf->Cell(0, 10, utf8_decode(strtoupper("Reporte de signos vitales")), 0, 0, 'C');

	$pdf->SetFont('Arial', '', 14);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(-35, 10, utf8_decode('Codigo de reporte'), '', 0, 'C');

	$pdf->Ln(10);

	$pdf->SetFont('Arial', '', 15);
	$pdf->SetTextColor(0, 107, 181);
	$pdf->Cell(0, 10, utf8_decode(COMPANY), 0, 0, 'C');

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(-35, 10, utf8_decode("R_SV-00001"), '', 0, 'C');

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
	$pdf->Cell(0, 8, utf8_decode('Datos del paciente'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Nombre del paciente:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['NombresPaciente'] . ' ' . $datos_signos_vitales['ApellidosPaciente']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Cedula:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_signos_vitales['Cedula']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('INSS:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['INSS']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Teléfono:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_signos_vitales['Telefono']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Email:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['Email']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Edad:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode(mainModel::calculaedad($datos_signos_vitales['Fecha_de_nacimiento'])), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Grupo sanguineo:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['GrupoSanguineo']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell(25, 8, utf8_decode('Dirección:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell(200, 8, utf8_decode($datos_signos_vitales['Direccion']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 14);
	$pdf->SetTextColor(56, 151, 0);
	$pdf->Cell(0, 8, utf8_decode('Signos vitales'), 0, 0, 'C');
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Frecuencia respiratoria:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['Frecuencia_Respiratoria']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Peso:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_signos_vitales['Peso']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Frecuencia cardiaca:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['Frecuencia_Cardiaca']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Altura:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_signos_vitales['Altura']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Presión arterial:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['Presion_Arterial']), 0, 0);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($rightTitleMargin, 8, utf8_decode('Temperatura:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfRightTitle, 8, utf8_decode($datos_signos_vitales['Temperatura']), 0, 0);
	$pdf->Ln(8);

	$pdf->SetFont('Arial', 'B', 12);
	$pdf->SetTextColor(33, 33, 33);
	$pdf->Cell($leftTitleMargin, 8, utf8_decode('Tomo signos vitales:'), 0, 0);
	$pdf->SetTextColor(97, 97, 97);
	$pdf->Cell($descriptionOfLeftTitle, 8, utf8_decode($datos_signos_vitales['NombresTomoSignos'] . ' ' . $datos_signos_vitales['ApellidosTomoSignos']), 0, 0);
	$pdf->Ln(8);

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

	$pdf->Output("I", "Reporte de paciente (" . $datos_signos_vitales['CodigoP'] . ").pdf", true);
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
				<p class="lead text-center">No se pudo generar el reporte de estos signos vitales
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