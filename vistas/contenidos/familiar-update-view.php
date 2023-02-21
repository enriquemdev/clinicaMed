<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 6 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 6 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 6 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($actualizar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; ACTUALIZAR FAMILIAR EMPLEADO/PERSONA
	</h3>
	<p class="text-justify">
		Actualice el familiar
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>familiares-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ACTUALIZAR FAMILIAR</a>
		</li>

	</ul>
</div>

<!-- Content here-->
<?php
//Datos de empleado
require_once "./controladores/familiarControlador.php";
$ins_fam = new familiarControlador();
$datos_fam = $ins_fam->datos_familiar_controladorUPDATE($pagina[1]);

if ($datos_fam->rowCount() == 1) {
	$camposFam = $datos_fam->fetch();

?>
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
		<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/FamiliarAjax.php" method="POST" data-form="update" autocomplete="off">
			<input type="hidden" name="fam_id" value="<?php echo $pagina[1]; ?>">

			<fieldset>
				<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="nombre_familiar" class="bmd-label-floating">NOMBRE DE FAMILIAR <span class="Obligar">*</span></label>
								<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}" value="<?php echo $camposFam['namefamiliar'] ?>" class="form-control" name="nombre_familiar_up" id="nombre_familiar" maxlength="50" required="">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="receta_examen" class="bmd-label-floating">APELLIDO DE FAMILIAR <span class="Obligar">*</span></label>
								<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}" value="<?php echo $camposFam['apellidofam'] ?>" class="form-control" name="apellido_familiar_up" id="apellido_familiar" maxlength="50" required="">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="empleado_cedula" class="bmd-label-floating">CEDULA <span class="Obligar">*</span></label>
								<input type="text" pattern="[a-zA-Z0-9- ]{16,16}" value="<?php echo $camposFam['CedulaFam'] ?>" class="form-control" name="cedula_familiar_up" id="cedula_familiar" maxlength="16" required="">
								<input type="hidden" name="cedoriginal_up" value="<?php echo $camposFam['CedulaFam'] ?>">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label for="item_genero" class="bmd-label-floating">GENERO <span class="Obligar">*</span></label>
							<select class="form-control" name="item_genero_up" id="item_genero">
								<option value="" selected="" disabled=""></option>
								<?php foreach ($datos_item as $campo) { ?>
									<option value="<?php echo $campo['ID'] ?>"><?php echo $campo['Nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="familiar_fecha" class="bmd-label-static">FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
								<input type="date" value="<?php echo $camposFam['dateoffam'] ?>" class="form-control" name="familiar_fecha_up" id="familiar_fecha">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
							<select class="form-control" name="item_civil_up" id="item_civil">
								<option value="" selected="" disabled=""></option>
								<?php foreach ($datos_item1 as $campo1) { ?>
									<option value="<?php echo $campo1['ID'] ?>"><?php echo $campo1['Nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="direccion_fam" class="bmd-label-floating">DIRECCIÓN DE FAMILIAR</label>
								<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,70}" value="<?php echo $camposFam['direccionfam'] ?>" class="form-control" name="direccion_fam_up" id="direccion_fam" maxlength="70" required="">
							</div>
						</div>
						<!-- <div class="col-12 col-md-6">
							<div class="form-group">
								<label for="familiar_de" class="bmd-label-floating">FAMILIAR DE (NOMBRE) <span class="Obligar">*</span></label>
								<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}" value="<?php echo $camposFam['NombreEmpleado'] ?>" class="form-control" name="familiar_de_up" id="familiar_de" maxlength="50" required="">
							</div>
						</div> -->

						<div class="col-12 col-md-6">
							<div class="form-group">
								<div class="form-group">
									<label for="familiar_de">FAMILIAR DE<span class="Obligar">*</span></label>
									<div class="autocompletar">
										<input type="text" class="form-control" value="<?php echo $camposFam['NombreEmpleado'] . ' ' . $camposFam['ApellidoEmpleado'] . '__' . $camposFam['CodigoEmpleado'] ?>" name="familiar_de_up" id="familiar_de" maxlength="50" required="true">
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-md-6">
							<label for="item_parentesco" class="bmd-label-floating">PARENTESCO <span class="Obligar">*</span></label>
							<select class="form-control" name="parentesco_up" id="parentesco">
								<option value="" selected="" disabled=""></option>
								<?php foreach ($datos_item3 as $campo3) { ?>
									<option value="<?php echo $campo3['ID'] ?>"><?php echo $campo3['Nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
						<?php
						/* Aquí se hizo un condicional para mostrar info según existencia */
						$motrar = "";
						if ($camposFam['telefonofam'] == "" && $camposFam['correofam'] == "") {
							$motrar = "";
						} else if ($camposFam['telefonofam'] == "") {
							$mostrar = $camposFam['correofam'];
						} else if ($camposFam['correofam'] == "") {
							$mostrar = $camposFam['telefonofam'];
						}
						?>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="familiar-telefono" class="bmd-label-floating">TELEFONO/CORREO CONTACTO EMERGENCIA <span class="Obligar">*</span></label>
								<input type="text" pattern="[0-9a-zA-ZáéíóúÁÉÍÓÚ@#.+ ]{8,50}" value="<?php echo $mostrar ?>" class="form-control" name="familiar_telefono_up" id="familiar_telefono" maxlength="50">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<label for="item_parentesco" class="bmd-label-floating">ESTADO <span class="Obligar">*</span></label>
							<select class="form-control" name="estado_up" id="estado">
								<option value="" selected="" disabled=""></option>
								<option value="1">Activo</option>
								<option value="2">Inactivo</option>
							</select>
						</div>
					<?php } ?>
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
	<script src="<?php echo SERVERURL; ?>buscadores/familiarDe_empleado.js"></script>