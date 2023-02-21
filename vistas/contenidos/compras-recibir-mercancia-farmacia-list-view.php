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


if ($ver == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>

<style>
	.cursorPointer {
		cursor: pointer;
	}
</style>

<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp;MERCANCIAS EN ESPERA
	</h3>
	<p class="text-justify">
		Lista de mercancia en espera
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">

		<?php
		if ($agregar == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-solicitud-farmacia-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SOLICITUD DE COMPRA</a>
			</li>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>compras-recibir-mercancia-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; RECIBIR MERCANCIA</a>
			</li>
		<?php } ?>
		<?php
		if ($ver == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE COMPRAS</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-solicitud-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; SOLICITUDES DE COMPRAS</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-farmacia-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR COMPRA</a>
			</li>
		<?php } ?>
	</ul>
</div>
<!-- Content here-->
<div class="container-fluid">
	<?php
	require_once "./controladores/comprasFarmaciaControlador.php";
	$ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();
	echo $ins_comprasFarmaciaControlador->paginador_compras_recibir_mercancia_farmacia_controlador($pagina[1], 15, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $pagina[0], "");

	?>
</div>