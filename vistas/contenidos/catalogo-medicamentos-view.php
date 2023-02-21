<?php
$auxiliar = new loginControlador();
$listaVistas = $auxiliar->navLateral_controlador();

if (!(in_array("Catalogos", $listaVistas))) {
	echo $lc->forzar_cierre_sesion_controlador();
	exit();
}

?>

<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="far fa-calendar-alt fa-fw"></i> &nbsp; CATALOGO MEDICAMENTOS
	</h3>
</div>
<div class="container-fluid">
	<div class="table-responsive">
		<?php
		require_once "./controladores/catalogosControlador.php";
		$ins_cataglogo = new catalogosControlador();
		echo $ins_cataglogo->paginador_medicamento_controlador($pagina[1], 10, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $pagina[0], "");

		?>
		<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/catMedicamentoAjax.php" method="POST" data-form="save" autocomplete="off">
			<fieldset>
				<legend><i class="fas fa-user"></i> &nbsp; AGREGAR MEDICAMENTO</legend>
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="MEDICAMENTO-NOMBRE COMERCIAL_ID" class="bmd-label-floating">NOMBRE COMERCIAL</label>
								<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="MEDICAMENTO-NOMBRE_COMERCIAL_reg" id="MEDICAMENTO-NOMBRE COMERCIAL" maxlength="40">
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="MEDICAMENTO-NOMBRE GENERICO" class="bmd-label-floating">NOMBRE GENERICO</label>
								<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="MEDICAMENTO-NOMBRE_GENERICO_reg" id="MEDICAMENTO-NOMBRE GENERICO" maxlength="40">

							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="MEDICAMENTO-FORMULA" class="bmd-label-floating">FORMULA</label>
								<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="MEDICAMENTO-FORMULA_reg" id="MEDICAMENTO-FORMULA" maxlength="40">

							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="MEDICAMENTO-PRESENTACION" class="bmd-label-floating">PRESENTACION</label>
								<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="MEDICAMENTO-PRESENTACION_reg" id="MEDICAMENTO-PRESENTACION" maxlength="40">

							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								<label for="descripcion" class="bmd-label-floating">Descripción</label>
								<textarea type="text" class="textarea1" requiered="" name="MED_DESC_reg" id="descripcion"></textarea>
							</div>
						</div>
					</div>
				</div>
				<br><br><br>
				<p class="text-center" style="margin-top: 40px;">
					<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
					&nbsp; &nbsp;
					<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
				</p>
			</fieldset>
		</form>
	</div>

</div>