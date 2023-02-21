<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;


foreach ($permisos as $key) {
	if ($key['CodigoSubModulo'] == 24 && $key['CodPrivilegio'] == 1) {
		$agregar = true;
	}

	if ($key['CodigoSubModulo'] == 24 && $key['CodPrivilegio'] == 2) {
		$ver = true;
	}

	if ($key['CodigoSubModulo'] == 24 && $key['CodPrivilegio'] == 3) {
		$actualizar = true;
	}
}


if ($agregar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

//Recibida de datos
$idSolicitud = (isset($_GET['idSolicitud'])) ? $_GET['idSolicitud'] : 0;

require_once("./controladores/comprasFarmaciaControlador.php");
$controladorFarmacia = new comprasFarmaciaControlador();
$laboratorios = $controladorFarmacia->obtener_laboratorios_controlador();
$laboratorios = $laboratorios->fetchAll();

?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; MERCANCIA RECIBIDA
	</h3>
	<p class="text-justify">
		Recibir mercancia
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<?php
		if ($agregar == true) { ?>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>compras-recibir-mercancia-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; RECIBIR MERCANCIA</a>
			</li>
		<?php } ?>

	</ul>
</div>

<div class="container-fluid">
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/ComprasFarmaciaAjax.php" method="POST" data-form="new" autocomplete="off">
		<fieldset>
			<legend><i class="fa fa-shopping-cart"></i> &nbsp; Información</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" value="<?php echo $pagina[1]; ?>" name="idSolicitud_reg" id="idSolicitud" hidden>
							<label class="bmd-label-floating">Recibido <span class="Obligar">*</span></label>
							<select class="form-control" name="tipoRecibido_reg" id="tipoRecibido">
								<option value="" selected="" disabled=""></option>
								<option value="1">Completo</option>
								<option value="2">Parcial</option>
								<option value="3">Incorrecto</option>
							</select>
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label>Fecha recibido <span class="Obligar">*</span></label>
							<input type="date" class="form-control" name="fechaRecibido_reg" id="fechaRecibid" required="">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Nota</label>
							<textarea type="text" class="textarea1" requiered="" name="nota_reg" id="nota"></textarea>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
			&nbsp; &nbsp;
			<button type="submit" id="enviarSolicitudCompra" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
		</p>
	</form>
</div>