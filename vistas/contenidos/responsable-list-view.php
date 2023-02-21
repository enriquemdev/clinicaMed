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
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RESPONSABLES
	</h3>
	<p class="text-justify">
		Lista de todos los responsables registrados
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<?php
		if ($agregar == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>responsable-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RESPONSABLE</a>
			</li>
		<?php } ?>
		<li>
			<a class="active" href="<?php echo SERVERURL; ?>responsable-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RESPONSABLES</a>
		</li>
	</ul>
</div>

<!-- Content here-->
<div class="container-fluid">
	<?php
	require_once "./controladores/responsableControlador.php";
	$ins_responsable = new responsableControlador();
	echo $ins_responsable->paginador_responsable_controlador($pagina[1], 15, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $pagina[0], "");

	?>
</div>