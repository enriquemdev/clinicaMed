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
		<i class="fas fa-clipboard-list fa-fw"></i> &nbsp;SOLICITUDES DE COMPRA
	</h3>
	<p class="text-justify">
		Lista de solicitudes de compras
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
				<a href="<?php echo SERVERURL; ?>compras-recibir-mercancia-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; RECIBIR MERCANCIA</a>
			</li>
		<?php } ?>
		<?php
		if ($ver == true) { ?>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE COMPRAS</a>
			</li>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>compras-solicitud-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; SOLICITUDES DE COMPRAS</a>
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
	echo $ins_comprasFarmaciaControlador->paginador_compras_solicitud_farmacia_controlador($pagina[1], 15, $_SESSION['privilegio_spm'], $_SESSION['id_spm'], $pagina[0], "");

	?>
</div>
<!-- Alertas -->
<script>
	function confirmar(title = "Estas segur@?", texto = "Presiona *Confirmar* para continuar", confirmText = "Confirmar!") {
		return new Promise(resolve => {
			Swal.fire({
				title: title,
				text: texto,
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: 'Cancelar',
				confirmButtonText: confirmText
			}).then((result) => {
				resolve(result.value);
			});
		})
	}

	function exito(title = "Realizado!", mensaje = "Todo ha ido bien") {
		Swal.fire({
			title: title,
			text: mensaje,
			type: "success",
			confirmButtonText: 'Aceptar'
		});
	}

	function error(title = "Vaya!", mensaje = "Ha ocurrido un error") {
		Swal.fire({
			title: title,
			text: mensaje,
			type: "error",
			confirmButtonText: "Aceptar"
		});
	}

	function confirmar2(title = "Realizado", texto = "Todo a ido bien", confirmText = "Aceptar") {
		return new Promise(resolve => {
			Swal.fire({
				title: title,
				text: texto,
				type: 'success',
				confirmButtonColor: '#3085d6',
				confirmButtonText: confirmText
			}).then((result) => {
				resolve(result.value);
			});
		})
	}
</script>
<!-- JS -->
<script>
	$(document).ready(function() {
		$(document).on('click', '.autorizarSolicitud', async function() {
			if (await confirmar(undefined,"Deseas autorizar esta solicitud de compra?","Autorizar!")) {
				let elemento = $(this)[0].parentElement.parentElement;
				let idSolicitudCompra = $(elemento).children().eq(0).text();
				$.post("../ajax/comprasFarmaciaAjax.php", {
					idSolicitudCompra
				}, async function(response) {
					console.log(response);
					let repuesta = JSON.parse(response);
					console.log("Ajax responde");
					console.log(repuesta);
					switch (repuesta.repuesta.estado) {
						case 'exito': {
							await confirmar2(undefined,"Se ha autorizado la solicitud de compra.",undefined);
							location.reload();
							break;
						}
						case 'error': {
							error();
							break;
						}
						default: {
							error();
							break;
						}
					}
				});
			}
		});
		$(document).on('click', '.denegarSolicitud', async function() {
			if (await confirmar(undefined,"Deseas denegar esta solicitud de compra?","Denegar!")) {
				let elemento = $(this)[0].parentElement.parentElement;
				let idSolicitudCompra2 = $(elemento).children().eq(0).text();
				$.post("../ajax/comprasFarmaciaAjax.php", {
					idSolicitudCompra2
				}, async function(response) {
					console.log(response);
					let repuesta = JSON.parse(response);
					console.log("Ajax responde");
					console.log(repuesta);
					switch (repuesta.repuesta.estado) {
						case 'exito': {
							await confirmar2(undefined,"Se ha denegado la solicitud de compra.",undefined);
							location.reload();
							break;
						}
						case 'error': {
							error();
							break;
						}
						default: {
							error();
							break;
						}
					}
				});
			}
		});
	});
</script>