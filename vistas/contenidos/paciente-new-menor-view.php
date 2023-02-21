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
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PACIENTE MENOR DE EDAD
	</h3>
	<p class="text-justify">
		Rellena todos los datos para poder agregar al paciente
	</p>
</div>

<div class="container-fluid">

	<ul class="full-box list-unstyled page-nav-tabs">
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>paciente-new-menor/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PACIENTE MENOR</a>
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
require_once "./controladores/familiarControlador.php";
$parentesco = new familiarControlador();
$datos_item3 = $parentesco->datos_item3_controlador();
?>
<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/pacienteAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información responsable
				<!--Ya registrado?-->
			</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="tutor" class="bmd-label-floating">TUTOR/RESPONSABLE <span class="Obligar">*</span></label>
							<!--Será un buscador luego-->
							<div class="text-center mt-5 mb-4">
								<!-- Button trigger modal -->
								<button type="button" class="btn btn-raised btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="liveSearchResponsable()">
									Seleccione un tutor
								</button>
								<!-- Modal -->
								<div class="modal fade " id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="staticBackdropLabel">Seleccione un tutor</h5>
												<button type="button" class="btn btn-raised btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
											</div>
											<div class="modal-body">
												Busca el tutor registrado
												<input type="text" class="form-control" name="registrado" id="live_search_Responsable" autocomplete="off" placeholder="Search..">
												<div id="responsablesResult"></div>

											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-raised btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
											</div>
										</div>
									</div>
								</div>
								<div id="ResponsableInfo">

								</div>
							</div>
						</div>
					</div>
					<legend><i class="fas fa-user"></i> &nbsp; Información paciente
					</legend>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="paciente_nombre" class="bmd-label-floating">NOMBRES <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,60}" class="form-control" name="paciente_nombre_reg" id="paciente_nombre" maxlength="60" required="">
						</div>
					</div>

					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="paciente_apellido" class="bmd-label-floating">APELLIDOS <span class="Obligar">*</span></label>
							<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,60}" class="form-control" name="paciente_apellido_reg" id="paciente_apellido" maxlength="60" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="item_parentesco" class="bmd-label-floating">SELECCIONE PARENTESCO DEL RESPONSABLE<span class="Obligar">*</span></label>
						<select class="form-control" name="parentesco_reg" id="parentesco">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item3 as $campo3) { ?>
								<option value="<?php echo $campo3['ID'] ?>"><?php echo $campo3['Nombre'] ?></option>
							<?php } ?>
						</select>
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
							<label for="paciente_nacio" class="bmd-label-static">FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="paciente_nacio_reg" id="paciente_nacio" required="">
						</div>
					</div>
					<!-- <div class="col-12 col-md-6">
						<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
						<select class="form-control" name="item_civil_reg" id="item_genero">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item1 as $campo1) { ?>
								<option value="<?php echo $campo1['ID'] ?>"><?php echo $campo1['Nombre'] ?></option>
							<?php } ?>
						</select>
					</div> -->
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="paciente_telefono" class="bmd-label-floating">DIRECCIÓN</label>
							<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,100}" class="form-control" name="paciente_direccion_reg" id="paciente_direccion" maxlength="100" required="">
						</div>
					</div>
					<!-- <div class="col-12 col-md-6">
						<div class="form-group">
							<label for="paciente_telefono" class="bmd-label-floating">TELÉFONO</label>
							<input type="text" pattern="[0-9#+-]{8,15}" class="form-control" name="paciente_telefono_reg" id="paciente_telefono" maxlength="13">
						</div>
					</div> -->
					<div class="col-12 col-md-6">
						<label for="item_grupo sanguineo" class="bmd-label-floating">GRUPO SANGUINEO <span class="Obligar">*</span></label>
						<select class="form-control" name="item_grupo_sanguineo_reg" id="item_sanguineo">
							<option value="" selected="" disabled=""></option>
							<?php foreach ($datos_item2 as $campo2) { ?>
								<option value="<?php echo $campo2['ID'] ?>"><?php echo $campo2['Nombre'] ?></option>
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