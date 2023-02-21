<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 8 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 8 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 8 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($agregar == false && $ver == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}
?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; AÑADIR RESPONSABLE DE MENOR
	</h3>
	<p class="text-justify">
		Registre nuevo responsable
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">

		<li>
			<a class="active" href="<?php echo SERVERURL; ?>responsable-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RESPONSABLE</a>
		</li>

		<?php
		if ($agregar == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>responsable-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RESPONSABLES</a>
			</li>
		<?php } ?>
	</ul>
</div>

<!-- Content here-->
<!-- OBTENIENDO INFO DE LOS ITEMS-->
<?php
require_once "./controladores/empleadosControlador.php";
$ins_item = new empleadosControlador();
require_once "./controladores/familiarControlador.php";
$ins_item2 = new familiarControlador();

$datos_item = $ins_item->datos_item1_controlador();
$datos_item1 = $ins_item->datos_item2_controlador();
$datos_item3 = $ins_item2->datos_item3_controlador();
if ($datos_item->rowCount() == 1) {
	$campos = $datos_item->fetch();
}
if ($datos_item1->rowCount() == 1) {
	$campos1 = $datos_item1->fetch();
}
if ($datos_item3->rowCount() == 1) {
	$campos3 = $datos_item3->fetch();
}
?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ResponsableAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="nombre_familiar" class="bmd-label-floating">NOMBRE DE RESPONSABLE <span class="Obligar">*</span></label>
							<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}" class="form-control" name="nombre_familiar_reg" id="nombre_familiar" maxlength="50" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="apellido_familiar" class="bmd-label-floating">APELLIDO DE RESPONSABLE <span class="Obligar">*</span></label>
							<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}" class="form-control" name="apellido_familiar_reg" id="apellido_familiar" maxlength="50" required="">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_cedula" class="bmd-label-floating">CÉDULA <span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9-(A-Za-z)]{16,16}" class="form-control" placeholder="xxx-xxxxxx-xxxx" name="cedula_reg" id="cedula_reg" maxlength="16" required="" title="Inserte cédula sin espacios ni guiones">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="item_genero" class="bmd-label-floating">GENERO <span class="Obligar">*</span></label>
						<select class="form-control" name="item_genero_reg" id="item_genero">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item as $campo) { ?>
								<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="familiar_fecha" class="bmd-label-static">FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="familiar_fecha_reg" id="familiar_fecha">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
						<select class="form-control" name="item_civil_reg" id="item_civil">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item1 as $campo1) { ?>
								<option value="<?php echo $campo1['ID'] ?>"><?php echo $campo1['Nombre'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="direccion_fam" class="bmd-label-floating">DIRECCIÓN DE RESPONSABLE</label>
							<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,70}" class="form-control" name="direccion_fam_reg" id="direccion_fam" maxlength="70" required="">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="familiar-telefono" class="bmd-label-floating">TELEFONO/CORREO CONTACTO EMERGENCIA <span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9a-zA-ZáéíóúÁÉÍÓÚ@#+. ]{8,50}" class="form-control" name="familiar_telefono_reg" id="familiar_telefono" maxlength="50">
						</div>
					</div>
					<input type="hidden" value="0" name="menor">
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