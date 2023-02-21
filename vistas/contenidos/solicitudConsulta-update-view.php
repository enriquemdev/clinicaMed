<?php

$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 22 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 22 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 22 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($agregar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<link rel="stylesheet" href="<?php echo SERVERURL; ?>vistas/css/buscadorForm.css">
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fab fa-dashcube fa-fw"></i> &nbsp; SOLICITUD DE CONSULTA
	</h3>
	<p class="text-justify">
		Actualizar solicitud de consulta
	</p>
</div>
<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">

		<?php if ($_SESSION['cargo_spm'] == 3) { ?>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>solicitudConsulta-update/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; ACTUALIZAR ASIGNACIÓN DE CONSULTA</a>
			</li>
		<?php } ?>

	</ul>
</div>

<!-- Content -->
<?php  //DATOS DE ITEMS
require_once "./controladores/consultaControlador.php";
$ins_item = new consultaControlador();
$datos_item = $ins_item->datos_item1_controlador();
$datos2 = $ins_item->datos_solicitud_consulta_controlador($pagina[1]);
if ($datos_item->rowCount() == 1) {
	$campos = $datos_item->fetch();
}
//DATOS DE AUTORRELLENO

if ($datos2->rowCount() == 1) {
	$datos = $datos2->fetch();

?>


	<div class="container-fluid">
		<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/solConsultaAjax.php" method="POST" data-form="update" autocomplete="off">
			<input type="hidden" name="consulta_codigo_up" value="<?php echo $pagina[1]; ?>">
			<fieldset>
				<legend><i class="fas fa-user"></i> &nbsp; Información de Consulta</legend>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">

								<label for="consulta_paciente_reg" class="bmd-label-floating">NOMBRE DEL PACIENTE (SOLO LECTURA)</label>
								<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ-0-9- ]{1,60}" class="form-control inputauto" name="" id="" maxlength="30" required="" value="<?php echo $datos['NombrePaciente'] ?> <?php echo $datos['ApellidoPaciente'] ?> - <?php echo $datos['CodPaciente'] ?>" readonly>
							</div>
						</div>
						<!-- Content -->
						<div class="col-12 col-md-6">
							<div class="form-group">

								<label for="cita_doctor_reg" class="bmd-label-floating">NOMBRE DOCTOR (SOLO LECTURA)</label>
								<div class="autocompletar">
									<!-- tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
									<input type="text" class="form-control" name="cita_doctor_reg" id="cita_doctor_reg" maxlength="60" required="" value="<?php echo $datos['CodMedico'] ?>">
								</div>

							</div>
						</div>

						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="codigo_cita" class="bmd-label-floating">CODIGO DE CITA (SOLO LECTURA)</label>
								<input type="text" pattern="[0-9-]{1,27}" class="form-control" name="" id="" maxlength="9" value="<?php
																																	if ($datos['IdCita'] == 0) {
																																	?> Sin cita <?php
																																	} else {
																																		echo $datos['IdCita'];
																																	}
														?>" readonly>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="codigo_consultorio" class="bmd-label-floating"><span class="lblCombo">CODIGO DE CONSULTORIO <span class="Obligar">*</span></span></label>
								<select class="form-control" name="consulta_consultorio_reg" id="codigo_consultorio">
									<option value="" selected="" disabled=""></option>
									<?php foreach ($datos_item as $campo) { ?>
										<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="motivo_consulta_reg" class="bmd-label-floating">MOTIVO CONSULTA</label>
								<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ-0-9- ]{1,250}" class="form-control" name="motivo_consulta_reg" id="motivo_consulta_reg" maxlength="250" value="<?php echo $datos['MotivoConsulta'] ?>">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="estado" class="bmd-label-floating">PASAR DIRECTO</label>
								<input type="checkbox" class="" name="estado_reg" id="estado" value=1></input>
							</div>
						</div>
					</div>
				</div>
			</fieldset>
			<br><br><br>
			<p class="text-center" style="margin-top: 40px;">
				<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
				&nbsp; &nbsp;
				<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
			</p>
		</form>
	</div>
	<script src="<?php echo SERVERURL; ?>buscadores/buscarEnConsulta.js"></script>
	<script src="<?php echo SERVERURL; ?>buscadores/codDoctor_creaSolicitudConsulta.js"></script>

<?php 	} else { ?>
	<div class="alert alert-danger text-center" role="alert">
		<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
		<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
		<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
	</div>
<?php }		?>