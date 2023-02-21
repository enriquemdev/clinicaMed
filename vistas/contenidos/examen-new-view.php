<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 18 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 18 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 18 && $key['CodPrivilegio'] == 3) {
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
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR EXAMEN
	</h3>
	<p class="text-justify">
		Registre nuevo examen
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>examen-new/"><i class="fas fa-plus fa-fw"></i> &nbsp;
				AGREGAR EXAMEN</a>
		</li>

		<?php
		if ($ver == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>examen-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE
					EXÁMENES</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>examen-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR
					EXAMEN</a>
			</li>
		<?php } ?>

	</ul>
</div>

<!-- Content here-->
<?php
require_once "./controladores/examenControlador.php";
$ins_item = new examenControlador();
$datos_item = $ins_item->datos_item1_controlador();

$datos_maquinaria = $ins_item->datos_maquinaria_controlador();

?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ExamenAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="receta_examen">RECETA PREVIA<span class="Obligar">*</span></label>
							<div class="autocompletar">
								<input type="text" class="form-control" name="receta_examen_reg" id="receta_examen" maxlength="50" required="true"
								title="Estructura: NombrePaciente__CodigoReceta__CodigoPaciente">
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="especialista_examen">ESPECIALISTA<span class="Obligar">*</span></label>
							<div class="autocompletar">
								<input type="text" class="form-control" name="especialista_examen_reg" id="especialista_examen" maxlength="50" required="true" 
								title="Estructura: NombreEspecialista__CodigoEspecialista__Especialidad">
							</div>
						</div>
					</div>

					<div class="col-12 col-md-6">
						<label for="item_sala_medica" class="bmd-label-floating">SALA MÉDICA <span class="Obligar">*</span></label>
						<select class="form-control" name="item_sala_medica_reg" id="item_sala_medica">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item as $campo) { ?>
								<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] //CAMBIAR POR DATOS DE SALA MEDICA
																			?></option>
							<?php } ?>
						</select>
					</div>

					<div class="col-12 col-md-6">
						<label for="maquina_examen_reg" class="bmd-label-floating">MAQUINARIA A UTILIZAR <span class="Obligar">*</span></label>
						<select class="form-control" name="maquina_examen_reg" id="maquina_examen_reg">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_maquinaria as $maquina) { ?>
								<option value="<?php echo $maquina['ID'] ?>"><?php echo $maquina['NombreMaquinaria'] ?>
								</option>
							<?php } ?>
						</select>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="fecha_examen" class="bmd-label-floating">FECHA<span class="Obligar">*</span></label>
							<input type="datetime-local" class="form-control" name="fecha_examen_reg" id="fecha_examen" maxlength="11">
						</div>
					</div>

				</div>
			</div>
		</fieldset>
		<br>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp;
				DESHACER</button>
			&nbsp; &nbsp;
			<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
		</p>
	</form>
</div>
<script src="<?php echo SERVERURL; ?>buscadores/RecetaExamen_examen.js"></script>
<script src="<?php echo SERVERURL; ?>buscadores/NombreEspecialista_examen.js"></script>