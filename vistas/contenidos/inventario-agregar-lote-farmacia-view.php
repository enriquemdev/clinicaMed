<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 23 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 23 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 23 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($ver == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR LOTE
	</h3>
	<p class="text-justify">
		Agregar mercancia a lote
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<?php
		if ($agregar == true) { ?>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>inventario-agregar-lote-farmacia/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; AÑADIR LOTES</a>
			</li>
		<?php } ?>

	</ul>
</div>

<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/inventarioFarmaciaAjax.php" method="POST" data-form="new" autocomplete="off">
		<fieldset>
			<legend><i class="fa fa-shopping-cart"></i> &nbsp; Información</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label>Detalle compra <span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="idDetSolicitud_reg" id="idDetSolicitud" value="<?php echo $pagina[1]; ?>" required readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label>Cantidad en espera de inventariar<span class="Obligar">*</span></label>
							<input type="number" class="form-control" name="cantidad_reg" id="cantidad" required value="<?php echo $pagina[2]; ?>" readonly>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label>Fecha vence <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="fechaVence_reg" id="fechaVence" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Cantidad lote</label>
							<input type="number" class="form-control" name="cantidadLote_reg" id="cantidadLote" required>
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