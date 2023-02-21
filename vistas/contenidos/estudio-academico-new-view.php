<?php

$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 5 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 5 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 5 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($agregar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR ESTUDIO ACADÉMICO
	</h3>
	<p class="text-justify">
		Asigne un estudio académico a un funcionario.
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>estudio-academico-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR ESTUDIO ACADÉMICO</a>
		</li>

		<li>
			<a href="<?php echo SERVERURL; ?>estudio-academico-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS CON ESTUDIOS</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>estudio-academico-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIO ACADEMICO</a>
		</li>

	</ul>
</div>

<!-- Content here-->
<?php
require_once "./controladores/estudiosacademicosControlador.php";
$ins_item = new estudiosacademicosControlador();
$datos_item = $ins_item->datos_item1_controlador();

?>
<div class="container-fluid">
	<fieldset>
		<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/estudiosacademicosAjax.php" method="POST" data-form="save" autocomplete="off">
			<!-- Añadir ajax de estudio académico -->
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					

					<div class="col-12 col-md-6">
						<div class="form-group">
							<div class="form-group">
								<label for="cod_empleado">EMPLEADO POR ASIGNAR<span class="Obligar">*</span></label>
								<div class="autocompletar">
									<input type="text" class="form-control" name="cod_empleado_reg" id="cod_empleado" maxlength="40" required="true">
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="nombre_estudio" class="bmd-label-floating">NOMBRE DE ESTUDIO <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚ1-9 ]{3,100}" class="form-control" name="nombre_estudio_reg" id="nombre_estudio" maxlength="40" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="tipo_estudio" class="bmd-label-floating">TIPO DE ESTUDIO <span class="Obligar">*</span></label>
						<select class="form-control" name="tipo_estudio_reg" id="tipo_estudio">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item as $campo) { ?>
								<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['NombreNivelAcademico'] //CAMBIAR POR DATOS DE CATALOGO ACADÉMICO
																			?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="institucion_estudio" class="bmd-label-floating">INSTITUCIÓN DE ESTUDIO <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚ1-9 ]{3,60}" class="form-control" name="institucion_estudio_reg" id="institucion_estudio" maxlength="60" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="inicio_estudios" class="bmd-label-floating">FECHA DE INICIO DE ESTUDIOS <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="inicio_estudios_reg" id="inicio_estudios" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="completo_estudio" class="bmd-label-floating">FECHA DE COMPLETACIÓN</label>
							<input type="date" class="form-control" name="completo_estudio_reg" id="completo_estudio" required="">
						</div>


					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="certificado_estudio" class="bmd-label-floating">CERTIFICADO</label>
							<input type="file" class="form-control" name="certificado_estudio_reg" id="certificado_estudio">
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
<script src="<?php echo SERVERURL; ?>buscadores/nombreEmpleado_estudioAcademico.js"></script>