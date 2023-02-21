<?php

$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 4 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 4 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 4 && $key['CodPrivilegio'] == 3) {
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
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ESPECIALIDAD
	</h3>
	<p class="text-justify">
		Asigne especialidad a un colaborador
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>especialidad-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR ESPECIALIDAD</a>
		</li>


		<li>
			<a href="<?php echo SERVERURL; ?>especialidad-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ESPECIALISTAS</a>
		</li>
		<li>
			<a href="<?php echo SERVERURL; ?>especialidad-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESPECIALIDAD</a>
		</li>

	</ul>
</div>

<!-- Content here-->
<?php
require_once "./controladores/especialidadControlador.php";
$ins_item = new especialidadControlador();
$datos_item = $ins_item->datos_item1_controlador();

?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/especialidadAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<!-- <div class="col-12 col-md-6">
						<div class="form-group">
							<label for="codigo_dog_especialidad" class="bmd-label-floating">DOCTOR POR ASIGNAR(Codigo) <span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9]{1,11}" class="form-control" name="codigo_doc_especialidad_reg" id="codigo_dog_especialidad" maxlength="30">
						</div>
					</div> -->

					<div class="col-12 col-md-6">
						<div class="form-group">
							<div class="form-group">
								<label for="codigo_dog_especialidad">DOCTOR POR ASIGNAR<span class="Obligar">*</span></label>
								<div class="autocompletar">
									<input type="text" class="form-control" name="codigo_doc_especialidad_reg" id="codigo_dog_especialidad" maxlength="30" required="true">
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<label for="especialidad" class="bmd-label-floating">ESPECIALIDAD <span class="Obligar">*</span></label>
						<select class="form-control" name="especialidad_reg" id="especialidad">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item as $campo) { ?>
								<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] //CAMBIAR POR DATOS DE especialidad
																			?></option>
							<?php } ?>
						</select>
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
<script src="<?php echo SERVERURL; ?>buscadores/nombreDoctor_especialidad.js"></script>