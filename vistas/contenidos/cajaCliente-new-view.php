<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 28 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 28 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 28 && $key['CodPrivilegio'] == 3) {
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
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE
	</h3>
	<p class="text-justify">
		Rellena todos los datos para poder agregar al cliente
	</p>
</div>

<div class="container-fluid">

	<ul class="full-box list-unstyled page-nav-tabs">

		<li>
			<a class="active" href="<?php echo SERVERURL; ?>paciente-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CLIENTE</a>
		</li>

	</ul>
</div>

<!-- Content here-->
<?php
require_once "./controladores/pacienteControlador.php";
$ins_item = new pacienteControlador();
$datos_item = $ins_item->datos_item1_controlador();
$datos_item1 = $ins_item->datos_item2_controlador();
$datos_item2 = $ins_item->datos_item3_controlador();
if ($datos_item->rowCount() == 1) {
	$campos = $datos_item->fetch();
}
if ($datos_item1->rowCount() == 1) {
	$campos1 = $datos_item1->fetch();
}
if ($datos_item2->rowCount() == 1) {
	$campos2 = $datos_item1->fetch();
}
?>
<div class="container-fluid">
	<!--Collapse-->

	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/clienteAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información básica
				<!--Ya registrado?-->
			</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_nombre" class="bmd-label-floating">NOMBRES <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,60}" class="form-control" name="cliente_nombre_reg" id="cliente_nombre" maxlength="60" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_apellido" class="bmd-label-floating">APELLIDOS <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,60}" class="form-control" name="cliente_apellido_reg" id="cliente_apellido" maxlength="60" required="">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_cedula" class="bmd-label-floating">CÉDULA <span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9-(A-Za-z)]{16,16}" class="form-control" placeholder="xxx-xxxxxx-xxxx" name="cliente_cedula_reg" id="cliente_cedula_reg" maxlength="16" required="" title="Inserte cédula sin espacios ni guiones">
						</div>
						<!-- <input type="text" pattern="[a-zA-Z0-9- ]{16,16}" class="form-control" name="cliente_cedula_reg" id="cliente_cedula" maxlength="16" required=""> -->
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_genero" class="bmd-label-floating">GENERO <span class="Obligar">*</span></label>
							<select class="form-control" name="item_genero_reg" id="item_genero">
								<option value="" selected="" disabled=""></option>
								<?php foreach ($datos_item as $campo) { ?>
									<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_nacio" class="bmd-label-static">FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="cliente_nacio_reg" id="cliente_nacio" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
						<select class="form-control" name="item_civil_reg" id="item_genero">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item1 as $campo1) { ?>
								<option value="<?php echo $campo1['ID'] ?>"><?php echo $campo1['Nombre'] ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_telefono" class="bmd-label-floating">DIRECCIÓN</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}" class="form-control" name="cliente_direccion_reg" id="cliente_direccion" maxlength="100" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_telefono" class="bmd-label-floating">TELÉFONO</label>
							<input type="text" pattern="[0-9#+-]{8,15}" class="form-control" name="cliente_telefono_reg" id="cliente_telefono" maxlength="13">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cliente_correo" class="bmd-label-floating">CORREO ELECTRÓNICO</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ()@.,#\- ]{1,40}" class="form-control" name="cliente_correo_reg" id="cliente_correo" maxlength="40">
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