<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 25 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 25 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 25 && $key['CodPrivilegio'] == 3) {
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
		<i class="fas fa-plus fa-fw"></i> &nbsp; VENTA
	</h3>
	<p class="text-justify">
		Registrar una venta
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<?php
		if ($ver == true) { ?>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>ventas-farmacia-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR VENTA</a>
			</li>
		<?php } ?>

		<?php
		if ($ver == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>ventas-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE VENTAS</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>ventas-farmacia-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR VENTA</a>
			</li>
		<?php } ?>

	</ul>
</div>

<style>
	.letraRoja {
		color: red;
	}

	.letraverde {
		color: green;
	}
</style>

<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ventasFarmaciaAjax.php" method="POST" data-form="save" autocomplete="off">
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; Información basica de la venta</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Receta medica</label>
							<div class="autocompletar">
								<input type="text" class="form-control inputauto" name="receta_medica_reg" id="receta_medica_reg" maxlength="30" required="">
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="fechaReceta" class="bmd-label-static">FECHA EMISION RECETA <span class="Obligar">*</span></label>
							<input type="text" class="form-control" id="fechaReceta" required="" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="medicamento" class="bmd-label-static">Medicamento<span class="Obligar">*</span></label>
							<input type="text" class="form-control" id="medicamento" required="" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="cantidadReceta" class="bmd-label-static">Cantidad receta<span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="cantidadReceta_reg" id="cantidadReceta" required="" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="disponibilidad" class="bmd-label-static">Disponibilidad<span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="disponibilidad_reg" id="disponibilidad" required="" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="precio" class="bmd-label-static">Precio/unidad<span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="precio_reg" id="precio" required="" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="costo" class="bmd-label-static">Costo total<span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="costo_reg" id="costo" required="" readonly>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="selectxD" class="bmd-label-floating">Vender mercancia disponible?<span class="Obligar">*</span></label>
							<select class="form-control" name="select_reg" id="select" required="">
								<option value="" selected="" disabled=""></option>
								<option value="No">No</option>
								<option value="Si">Si</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="fechaVenta" class="bmd-label-static">FECHA VENTA <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="fechaVenta_reg" id="fechaVenta" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="descripcion" class="bmd-label-floating">Descripción</label>
							<textarea type="text" class="textarea1" requiered="" name="descripcion_reg" id="descripcion"></textarea>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
			&nbsp; &nbsp;
			<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
		</p>
	</form>
</div>
<script src="<?php echo SERVERURL; ?>buscadores/RecetaMedica_creaVenta.js"></script>