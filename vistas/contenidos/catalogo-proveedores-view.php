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
		<i class="far fa-calendar-alt fa-fw"></i> &nbsp; CATALOGO PROVEEDORES
	</h3>
</div>
<div class="container-fluid">
	<?php
	require_once "./controladores/catalogosControlador.php";
	$ins_cataglogo = new catalogosControlador();
	echo $ins_cataglogo->paginador_proveedor_controlador($pagina[1], 5, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $pagina[0], "");

	?>
	<form class="form-neon FormularioAjax" action="<?php echo SERVERURL; ?>ajax/catProveedorAjax.php" method="POST" data-form="save" autocomplete="off">
		<!-- Esto fue modificado -->
		<fieldset>
			<legend><i class="fas fa-user"></i> &nbsp; AGREGAR PROVEEDOR</legend>
			<div class="container-fluid">

				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROEEDOR_NOMBRE" class="bmd-label-floating">NOMBRE DEL PROVEEDOR<span class="Obligar">*</span></label>
							<input type="text" pattern="[a-záéíóúÁÉÍÓÚA-Z0-9., ]{4,40}" class="form-control" name="PROEEDOR_NOMBRE_reg" id="PROEEDOR_NOMBRE" maxlength="40" required="">

						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROVEEDOR_TELEFONO" class="bmd-label-floating">TELEFONO DEL PROVEEDOR<span class="Obligar">*</span></label>
							<input type="tel" class="form-control" name="PROVEEDOR_TELEFONO_reg" id="PROVEEDOR_TELEFONO" maxlength="100" required="">

						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROVEEDOR_DIRECCION" class="bmd-label-floating">DIRECCIÓN DEL PROVEEDOR</label>
							<input type="text" class="form-control" name="PROVEEDOR_DIRECCION_reg" id="PROVEEDOR_DIRECCION" maxlength="100" required="">

						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROVEEDOR_EMAIL" class="bmd-label-floating">EMAIL DEL PROVEEDOR</label>
							<input type="email" class="form-control" name="PROVEEDOR_EMAIL_reg" id="PROVEEDOR_EMAIL" maxlength="100" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<label for="PROVEEDOR_ESTADO" class="bmd-label-floating">ESTADO<span class="Obligar">*</span></label>
						<select class="form-control" name="PROVEEDOR_ESTADO_reg" id="PROVEEDOR_ESTADO">
							<option value="1">Activo</option>
							<option value="1">Inactivo</option>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROVEEDOR_RANKING" class="bmd-label-floating">RANKING</label>
							<input type="number" class="form-control" name="PROVEEDOR_RANKING_reg" id="PROVEEDOR_RANKING" maxlength="100" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="PROVEEDOR_TIEMPO" class="bmd-label-floating">LEAD TIME/TIEMPO ENTREGA</label>
							<input type="number" class="form-control" name="PROVEEDOR_TIEMPO_reg" id="PROVEEDOR_TIEMPO" maxlength="100" required="">
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="descripcion" class="bmd-label-floating">Descripción</label>
							<textarea type="text" class="textarea1" requiered="" name="PROVEEDOR_DESC_reg" id="descripcion"></textarea>
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