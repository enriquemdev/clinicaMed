<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 3 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 3 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 3 && $key['CodPrivilegio'] == 3) {
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
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR EMPLEADO
	</h3>
	<p class="text-justify">
		Registre nuevo empleado
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>empleado-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR EMPLEADO</a>
		</li>

		<?php
		if ($ver == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>empleado-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>empleado-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR EMPLEADO</a>
			</li>
		<?php } ?>

	</ul>
</div>

<!-- Content here-->
<?php
require_once "./controladores/empleadosControlador.php";
$ins_item = new empleadosControlador();
$datos_item = $ins_item->datos_item1_controlador();
$datos_item1 = $ins_item->datos_item2_controlador();
if ($datos_item->rowCount() == 1) {
	$campos = $datos_item->fetch();
}
if ($datos_item1->rowCount() == 1) {
	$campos1 = $datos_item1->fetch();
}
?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/EmpleadoAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_nombre" class="bmd-label-floating">NOMBRE <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,70}" class="form-control" name="empleado_nombre_reg" id="empleado_nombre" maxlength="40" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_apellidos" class="bmd-label-floating">APELLIDOS <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,70}" class="form-control" name="empleado_apellido_reg" id="empleado_apellido" maxlength="40" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_inss" class="bmd-label-floating">INSS</label>
							<input type="text" pattern="[0-9-]{9,9}" class="form-control" name="empleado_inss_reg" id="empleado_inss" maxlength="9">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_cedula" class="bmd-label-floating">CEDULA <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-Z0-9- ]{16,16}" class="form-control cedula_formato" name="empleado_cedula_reg" id="empleado_cedula" maxlength="16">
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
							<label for="empleado_nacio" class="bmd-label-static">FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="empleado_nacio_reg" id="empleado_nacio">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
						<select class="form-control" name="item_civil_reg" id="item_genero" required>
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item1 as $campo1) { ?>
								<option value="<?php echo $campo1['ID'] ?>"><?php echo $campo1['Nombre'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_direccion" class="bmd-label-floating">DIRECCIÓN <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="empleado_direccion_reg" id="empleado_direccion" maxlength="150" required>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_telefono" class="bmd-label-floating">TELEFONO <span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9#-+ ]{8,15}" class="form-control" name="empleado_telefono_reg" id="" maxlength="14" required="">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="empleado_correo" class="bmd-label-floating">CORREO ELECTRÓNICO <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#@\- ]{1,150}" class="form-control" name="empleado_correo_reg" id="empleado_correo" maxlength="70" required="">
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