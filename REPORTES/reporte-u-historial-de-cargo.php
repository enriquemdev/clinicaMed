<?php
	//Acceso al usuario actual
	session_start(['name'=>'SPM']);
	$usuario=$_SESSION['usuario_spm'];

	//Acceso a la configuracion del sistema
	$peticionAjax = true;
    require_once "../config/APP.php";

	//Recibida de datos
	$idEmpleado=(isset($_GET['idEmpleado'])) ? $_GET['idEmpleado'] : 0;
	$idPersona=(isset($_GET['idPersona'])) ? $_GET['idPersona'] : 0;

	//Instancia al controlador
	require_once "../controladores/empleadosControlador.php";
	$ins_empleado = new empleadosControlador();
	$datos_empleado=$ins_empleado->datos_empleado_controlador($idEmpleado);
	$datos_familiares_empleado=$ins_empleado->datos_familiares_empleado_controlador($idPersona);
	$datos_especialidades_empleado=$ins_empleado->datos_especialidades_empleado_controlador($idEmpleado);
	$cargoActual=$ins_empleado->obtener_ultimo_cargo_activo_empleado_controlador($idEmpleado);
	$datos_estudios_academicos_empleado=$ins_empleado->datos_estudios_academicos_empleado_controlador($idEmpleado);

	if($datos_empleado->rowCount()>=1){

		$codigoEmpleado;

		$datos_empleado=$datos_empleado->fetch();
		$datos_familiares_empleado=$datos_familiares_empleado->fetchAll();
		$datos_especialidades_empleado=$datos_especialidades_empleado->fetchAll();
		$cargoActual=$cargoActual->fetch();
		$datos_estudios_academicos_empleado=$datos_estudios_academicos_empleado->fetchAll();
		

		require "./fpdf.php";

		//Definición de constantes
		$leftTitleMargin=50;
		$rightTitleMargin=25;
		$descriptionOfLeftTitle=80;
		$descriptionOfRightTitle=60;
		$leftMarginOfDoc=10;
		$TopMarginOfDoc=17;
		$RightMarginOfDoc=10;
		$footeMargin=0.12;//Expresado en porcentajes 

		$pdf = new FPDF('P','mm','Letter');

		$pdf->SetAutoPageBreak(false);
		
		$pdf->SetMargins($leftMarginOfDoc,$TopMarginOfDoc,$RightMarginOfDoc);
		$pdf->AddPage();

		$pdf->Image('../vistas/assets/img/logo_clinica.png',10,10,30,30,'PNG');

		$pdf->SetFont('Arial','B',20);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,10,utf8_decode(strtoupper("Reporte de empleado")),0,0,'C');
		
		$pdf->SetFont('Arial','',14);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(-35,10,utf8_decode('Codigo de reporte'),'',0,'C');
		
		$pdf->Ln(10);

		$pdf->SetFont('Arial','',15);
		$pdf->SetTextColor(0,107,181);
		$pdf->Cell(0,10,utf8_decode("________________________"),0,0,'C');

		$pdf->SetFont('Arial','B',12);
		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(-35,10,utf8_decode("E_R-00001"),'',0,'C');

		$pdf->Ln(30);

		//Para obtener la fecha loco
		date_default_timezone_set("America/Managua");
		$date=date("d-m-y");

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(40,8,utf8_decode('Fecha de emisión:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(90,8,$date,0,0);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(30,8,utf8_decode('Emitido por:'),"",0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(40,8,utf8_decode($usuario),0,0);

		$pdf->Ln(15);

		$pdf->SetFont('Arial','B',12);
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell($leftTitleMargin,8,utf8_decode('Nombre del empleado:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell($descriptionOfLeftTitle,8,utf8_decode($datos_empleado['NombresEmpleado'].' '.$datos_empleado['ApellidosEmpleado']),0,0);
		
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell($rightTitleMargin,8,utf8_decode('Cedula:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell($descriptionOfRightTitle,8,utf8_decode($datos_empleado['Cedula']),0,0);

		$pdf->Ln(10);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell($leftTitleMargin,8,utf8_decode('INSS:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell($descriptionOfLeftTitle,8,utf8_decode($datos_empleado['INSS']),0,0);
		
		$pdf->SetTextColor(33,33,33);
		$pdf->Cell($rightTitleMargin,8,utf8_decode('Teléfono:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell($descriptionOfRightTitle,8,utf8_decode($datos_empleado['Telefono']),0,0);
		
		$pdf->Ln(10);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell($leftTitleMargin,8,utf8_decode('Cargo actual:'),0,0);

		$pdf->SetTextColor(97,97,97);
		if($cargoActual==null){
			$pdf->Cell($descriptionOfLeftTitle,8,utf8_decode("Actualmente no ejerce algun cargo"),0,0);
		}else{
			$pdf->Cell($descriptionOfLeftTitle,8,utf8_decode($cargoActual['NombreCargo']),0,0);
		}
		

		$pdf->Ln(10);

		$pdf->SetTextColor(33,33,33);
		$pdf->Cell(25,8,utf8_decode('Dirección:'),0,0);

		$pdf->SetTextColor(97,97,97);
		$pdf->Cell(200,8,utf8_decode($datos_empleado['Direccion']),0,0);

		$pdf->Ln(15);

		if(count($datos_familiares_empleado)>=1){
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-15);
			$pdf->Cell(15,8,utf8_decode('Familiares'),0,0);

			$pdf->Ln(10);

			$pdf->SetFillColor(38,198,208);
			$pdf->SetDrawColor(38,198,208);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetFont('Arial','B',12);

			$pdf->Cell(90,7,utf8_decode('Nombre completo'),1,0,'C',true);
			$pdf->Cell(60,7,utf8_decode('Contacto'),1,0,'C',true);
			$pdf->Cell(30,7,utf8_decode('Parentesco'),1,0,'C',true);
			$pdf->Cell(20,7,utf8_decode('Tutor?'),1,0,'C',true);

			$pdf->Ln(7);

			$pdf->SetFont('Arial','',11);
			$pdf->SetTextColor(97,97,97);

			$contador=0;
			foreach($datos_familiares_empleado as $datos_familiar){
				$pdf->Cell(90,10,utf8_decode($datos_familiar['Nombres'].' '.$datos_familiar['Apellidos']),'LR',0,'C');
				if($datos_familiar['Telefono']!=null){
					$pdf->Cell(60,10,utf8_decode($datos_familiar['Telefono']),'LR',0,'C');
				}else{
					$pdf->Cell(60,10,utf8_decode($datos_familiar['Email']),'LR',0,'C');
				}
				$pdf->Cell(30,10,utf8_decode($datos_familiar['Parentesco']),'LR',0,'C');
				
				if($datos_familiar['EsTutor']==1){
					$esTutor='Si';
				}else{
					$esTutor='No';
				}

				$pdf->Cell(20,10,utf8_decode($esTutor),'LR',0,'C');
				$pdf->Ln(5);
				

				if($contador==count($datos_familiares_empleado)-1){
					//La linea de cierre de la tabla mi loco
					$pdf->Ln(5);
					$pdf->Cell(200,null,null,'T',null,null);
				}else{
					//$pdf->Cell(180,null,null,'T',null,null);
					//$pdf->Ln(5);
					if($pdf->GetY()>($pdf->GetPageHeight()-($pdf->GetPageHeight()*($footeMargin+($footeMargin*0.85))))){
						$pdf->Ln(5);
						$pdf->Cell(200,null,null,'T',null,null);

						$alto=$pdf->GetPageHeight();
						$pdf->SetY($alto-($alto*$footeMargin));

						/*----------  INFO. EMPRESA  ----------*/
						$pdf->SetFont('Arial','B',9);
						$pdf->SetTextColor(33,33,33);
						$pdf->Cell(0,6,utf8_decode(COMPANY),0,0,'C');
						$pdf->Ln(6);
						$pdf->SetFont('Arial','',9);
						$pdf->Cell(0,6,utf8_decode(COMPANY_DIRECTION),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Teléfono: ".COMPANY_PHONE),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Correo: ".COMPANY_EMAIL),0,0,'C');

						$pdf->SetY(-15);
						$pdf->SetX(-15);
						$pdf->SetFont('Arial','B',14);
						$pdf->Write(5,$pdf->PageNo());

						$pdf->AddPage();
						//$pdf->Ln(5);
						//$pdf->SetY(0);
						//$pdf->AsignarPagina(1);
						//$pdf->Ln(80);
						$pdf->SetFont('Arial','B',14);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetX(($pdf->GetPageWidth()/2)-15);
						$pdf->Cell(15,8,utf8_decode('Familiares'),0,0);
						$pdf->Ln(10);
						$pdf->SetFillColor(38,198,208);
						$pdf->SetDrawColor(38,198,208);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetFont('Arial','B',12);

						$pdf->Cell(90,7,utf8_decode('Nombre completo'),1,0,'C',true);
						$pdf->Cell(60,7,utf8_decode('Contacto'),1,0,'C',true);
						$pdf->Cell(30,7,utf8_decode('Parentesco'),1,0,'C',true);
						$pdf->Cell(20,7,utf8_decode('Tutor?'),1,0,'C',true);

						$pdf->Ln(7);

						$pdf->SetFont('Arial','',11);
						$pdf->SetTextColor(97,97,97);
					}
				}
			$contador++;
		}
		}else {
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-40);
			$pdf->Cell(60,8,utf8_decode('Este empleado no tiene familiares'),0,0);
			//$pdf->Ln(10);
		}

		///////////////////////////////////////////////////////////////////////////////////
		$pdf->Ln(10);

		if(count($datos_especialidades_empleado)>=1){
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-15);
			$pdf->Cell(15,8,utf8_decode('Especialidades'),0,0);

			$pdf->Ln(10);

			$pdf->SetFillColor(38,198,208);
			$pdf->SetDrawColor(38,198,208);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetFont('Arial','B',12);

			$pdf->Cell(30,7,utf8_decode('Especialidad'),1,0,'C',true);
			$pdf->Cell(170,7,utf8_decode('Descripción especialidad'),1,0,'C',true);

			$pdf->Ln(7);

			$pdf->SetFont('Arial','',11);
			$pdf->SetTextColor(97,97,97);

			$contador=0;
			foreach($datos_especialidades_empleado as $datos_especialidad){
				$pdf->Cell(30,10,utf8_decode($datos_especialidad['NombreEspecialidad']),'LR',0,'C');
				$pdf->Cell(170,10,utf8_decode($datos_especialidad['Descripcion']),'LR',0,'C');
				$pdf->Ln(5);
				

				if($contador==count($datos_especialidades_empleado)-1){
					//La linea de cierre de la tabla mi loco
					$pdf->Ln(5);
					$pdf->Cell(200,null,null,'T',null,null);
				}else{
					//$pdf->Cell(180,null,null,'T',null,null);
					//$pdf->Ln(5);
					if($pdf->GetY()>($pdf->GetPageHeight()-($pdf->GetPageHeight()*($footeMargin+($footeMargin*0.85))))){
						$pdf->Ln(5);
						$pdf->Cell(200,null,null,'T',null,null);

						$alto=$pdf->GetPageHeight();
						$pdf->SetY($alto-($alto*$footeMargin));

						/*----------  INFO. EMPRESA  ----------*/
						$pdf->SetFont('Arial','B',9);
						$pdf->SetTextColor(33,33,33);
						$pdf->Cell(0,6,utf8_decode(COMPANY),0,0,'C');
						$pdf->Ln(6);
						$pdf->SetFont('Arial','',9);
						$pdf->Cell(0,6,utf8_decode(COMPANY_DIRECTION),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Teléfono: ".COMPANY_PHONE),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Correo: ".COMPANY_EMAIL),0,0,'C');

						$pdf->SetY(-15);
						$pdf->SetX(-15);
						$pdf->SetFont('Arial','B',14);
						$pdf->Write(5,$pdf->PageNo());

						$pdf->AddPage();
						//$pdf->Ln(5);
						//$pdf->SetY(0);
						//$pdf->AsignarPagina(1);
						//$pdf->Ln(80);
						$pdf->SetFont('Arial','B',14);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetX(($pdf->GetPageWidth()/2)-15);
						$pdf->Cell(15,8,utf8_decode('Especialidades'),0,0);

						$pdf->Ln(10);

						$pdf->SetFillColor(38,198,208);
						$pdf->SetDrawColor(38,198,208);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetFont('Arial','B',12);

						$pdf->Cell(30,7,utf8_decode('Especialidad'),1,0,'C',true);
						$pdf->Cell(170,7,utf8_decode('Descripción especialidad'),1,0,'C',true);

						$pdf->Ln(7);

						$pdf->SetFont('Arial','',11);
						$pdf->SetTextColor(97,97,97);
					}else{

					}
				}
			$contador++;
		}
		}else {
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-40);
			$pdf->Cell(60,8,utf8_decode('Este empleado no tiene especialidades'),0,0);
			//$pdf->Ln(10);
		}

		$pdf->Ln(10);

		if(count($datos_estudios_academicos_empleado)>=1){
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-15);
			$pdf->Cell(15,8,utf8_decode('Estudios academicos'),0,0);

			$pdf->Ln(10);

			$pdf->SetFillColor(38,198,208);
			$pdf->SetDrawColor(38,198,208);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetFont('Arial','B',12);

			$pdf->Cell(80,7,utf8_decode('Estudio'),1,0,'C',true);
			$pdf->Cell(50,7,utf8_decode('Tipo estudio'),1,0,'C',true);
			$pdf->Cell(70,7,utf8_decode('Institución'),1,0,'C',true);
			/*$pdf->Cell(30,7,utf8_decode('Fecha inicio'),1,0,'C',true);
			$pdf->Cell(30,7,utf8_decode('Fecha finalización'),1,0,'C',true);*/

			$pdf->Ln(7);

			$pdf->SetFont('Arial','',11);
			$pdf->SetTextColor(97,97,97);

			$contador=0;
			foreach($datos_estudios_academicos_empleado as $estudio_academico){
				$pdf->Cell(80,10,utf8_decode($estudio_academico['NombreEstudio']),'LR',0,'C');
				$pdf->Cell(50,10,utf8_decode($estudio_academico['NombreNivelAcademico']),'LR',0,'C');
				$pdf->Cell(70,10,utf8_decode($estudio_academico['Institucion']),'LR',0,'C');
				/*$pdf->Cell(30,10,utf8_decode($estudio_academico['InicioEstudio']),'L',0,'C');
				$pdf->Cell(30,10,utf8_decode($estudio_academico['FinEstudio']),'LR',0,'C');
				*/
				$pdf->Ln(5);
				

				if($contador==count($datos_estudios_academicos_empleado)-1){
					//La linea de cierre de la tabla mi loco
					$pdf->Ln(5);
					$pdf->Cell(200,null,null,'T',null,null);
				}else{
					//$pdf->Cell(180,null,null,'T',null,null);
					//$pdf->Ln(5);
					if($pdf->GetY()>($pdf->GetPageHeight()-($pdf->GetPageHeight()*($footeMargin+($footeMargin*0.85))))){
						$pdf->Ln(5);
						$pdf->Cell(200,null,null,'T',null,null);

						$alto=$pdf->GetPageHeight();
						$pdf->SetY($alto-($alto*$footeMargin));

						/*----------  INFO. EMPRESA  ----------*/
						$pdf->SetFont('Arial','B',9);
						$pdf->SetTextColor(33,33,33);
						$pdf->Cell(0,6,utf8_decode(COMPANY),0,0,'C');
						$pdf->Ln(6);
						$pdf->SetFont('Arial','',9);
						$pdf->Cell(0,6,utf8_decode(COMPANY_DIRECTION),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Teléfono: ".COMPANY_PHONE),0,0,'C');
						$pdf->Ln(6);
						$pdf->Cell(0,6,utf8_decode("Correo: ".COMPANY_EMAIL),0,0,'C');

						$pdf->SetY(-15);
						$pdf->SetX(-15);
						$pdf->SetFont('Arial','B',14);
						$pdf->Write(5,$pdf->PageNo());

						$pdf->AddPage();
						//$pdf->Ln(5);
						//$pdf->SetY(0);
						//$pdf->AsignarPagina(1);
						//$pdf->Ln(80);
						$pdf->SetFont('Arial','B',14);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetX(($pdf->GetPageWidth()/2)-15);
						$pdf->Cell(15,8,utf8_decode('Estudios academicos'),0,0);

						$pdf->Ln(10);

						$pdf->SetFillColor(38,198,208);
						$pdf->SetDrawColor(38,198,208);
						$pdf->SetTextColor(33,33,33);
						$pdf->SetFont('Arial','B',12);

						$pdf->Cell(80,7,utf8_decode('Estudio'),1,0,'C',true);
						$pdf->Cell(50,7,utf8_decode('Tipo estudio'),1,0,'C',true);
						$pdf->Cell(70,7,utf8_decode('Institución'),1,0,'C',true);
						/*$pdf->Cell(30,7,utf8_decode('Fecha inicio'),1,0,'C',true);
						$pdf->Cell(30,7,utf8_decode('Fecha finalización'),1,0,'C',true);
*/
						$pdf->Ln(7);

						$pdf->SetFont('Arial','',11);
						$pdf->SetTextColor(97,97,97);
					}else{

					}
				}
			$contador++;
		}
		}else {
			$pdf->SetFont('Arial','B',14);
			$pdf->SetTextColor(33,33,33);
			$pdf->SetX(($pdf->GetPageWidth()/2)-40);
			$pdf->Cell(60,8,utf8_decode('Este empleado no tiene estudios academicos'),0,0);
			//$pdf->Ln(10);
		}

	$alto=$pdf->GetPageHeight();
	$pdf->SetY($alto-($alto*$footeMargin));

	/*----------  INFO. EMPRESA  ----------*/
	$pdf->SetFont('Arial','B',9);
	$pdf->SetTextColor(33,33,33);
	$pdf->Cell(0,6,utf8_decode(COMPANY),0,0,'C');

	$pdf->Ln(6);

	$pdf->SetFont('Arial','',9);
	$pdf->Cell(0,6,utf8_decode(COMPANY_DIRECTION),0,0,'C');

	$pdf->Ln(6);

	$pdf->Cell(0,6,utf8_decode("Teléfono: ".COMPANY_PHONE),0,0,'C');

	$pdf->Ln(6);

	$pdf->Cell(0,6,utf8_decode("Correo: ".COMPANY_EMAIL),0,0,'C');


	$pdf->SetY(-15);
	$pdf->SetX(-15);
	$pdf->SetFont('Arial','B',14);
	$pdf->Write(5,$pdf->PageNo());

	$pdf->Output("I","Reporte de empleado (".$datos_empleado['CodigoEmpleado'].").pdf",true);

}else{
	?>
	
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo COMPANY;?></title>
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