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

require_once "./controladores/recetaControlador.php";
$ins_item = new recetaControlador();

for ($j = 0; $j < 10; $j++) {
	$datos_item[$j] = $ins_item->datos_medicamentos_controlador();
	if ($datos_item[$j]->rowCount() == 1) {
		$campos = $datos_item[$j]->fetch();
	}
}

require_once("./controladores/comprasFarmaciaControlador.php");
$controladorFarmacia = new comprasFarmaciaControlador();
$laboratorios = $controladorFarmacia->obtener_laboratorios_controlador();
$laboratorios = $laboratorios->fetchAll();

?>
<style>
	/* .eliminar lo tiene el contenedor del boton de eliminar detalles de compra */
	.eliminar {
		display: none;
		justify-content: left;
		align-items: center;
	}

	.quitar {
		cursor: pointer;
	}
</style>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fas fa-plus fa-fw"></i> &nbsp; SOLICITUD DE COMPRA
	</h3>
	<p class="text-justify">
		Realizar una solicitud de compra
	</p>
</div>

<div class="container-fluid">
	<ul class="full-box list-unstyled page-nav-tabs">
		<?php
		if ($agregar == true) { ?>
			<li>
				<a class="active" href="<?php echo SERVERURL; ?>compras-solicitud-farmacia-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SOLICITUD DE COMPRA</a>
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
				<a href="<?php echo SERVERURL; ?>compras-solicitud-farmacia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; SOLICITUDES DE COMPRAS</a>
			</li>
			<li>
				<a href="<?php echo SERVERURL; ?>compras-farmacia-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR COMPRA</a>
			</li>
		<?php } ?>

	</ul>
</div>

<div class="container-fluid">
	<form class="form-neon">
		<fieldset>
			<legend><i class="fa fa-shopping-cart"></i> &nbsp; Información de la solicitud de compra</legend>
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<label for="descripcion" class="bmd-label-floating">Descripción</label>
							<textarea type="text" class="textarea1" requiered="" name="descripcion_reg" id="descripcion"></textarea>
						</div>
					</div>
				</div>
			</div>
		</fieldset>

		<!-- FIELDSET DEL DETALLE AQUI QUEDE-->
		<fieldset>
			<legend><i class="fa fa-shopping-cart"></i> &nbsp; Detalle de la solicitud</legend>
			<table class="container-fluid" id="tabla">
				<tr class="row fila-fija">

					<td class="col-12 col-md-6">
						<div class="form-group">
							<label for="medicamento" class="bmd-label-static">Medicamento<span class="Obligar">*</span></label>
							<div class="autocompletar">
								<input type="text" class="form-control" name="medicamento_name" id="medicamento" maxlength="100">
							</div>
						</div>
					</td>

					<td class="col-12 col-md-6">
						<div class="form-group">
							<label for="item_laboratorio" class="bmd-label-static">Laboratorio<span class="Obligar">*</span></label>
							<div class="autocompletar">
								<input type="text" class="form-control" name="item_laboratorio_reg" id="item_laboratorio" maxlength="100" readonly="true">
							</div>
						</div>
					</td>

					<td class="col-12 col-md-6">
						<div class="form-group">
							<div class="form-group" title="FORMATO: NombreProveedor__LeadTime">
								<label for="proveedor">NOMBRE PROVEEDOR<span class="Obligar">*</span></label>
								<div class="autocompletar">
									<!-- pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ.1-9 ]{3,70}" -->
									<input type="text" class="form-control" name="proveedor_reg" id="proveedor_reg" maxlength="100" readonly="true">
								</div>
							</div>
						</div>
					</td>


					<td class="col-12 col-md-6">
						<div class="form-group">
							<label for="cantidad" class="bmd-label-static">Cantidad<span class="Obligar">*</span></label>
							<input type="text" pattern="[0-9]{1,11}" class="form-control" name="cantidad_reg[]" id="cantidad" maxlength="5">
						</div>
					</td>

					<td class="col-12 col-md-6 eliminar">
						<button type="button" class="btn btn-raised btn-secondary"><i class="fa fa-minus-circle"></i> &nbsp; Eliminar Detalle de Compra</button>
					</td>

				</tr>
			</table>
		</fieldset>
		<div>
			<button type="button" id="agregarDatosTabla" class="btn btn-raised btn-success btn-sm"><i class="fa fa-plus-circle"></i> &nbsp; AÑADIR DETALLE</button>
		</div>
		&nbsp;
		<div class="table-responsive">
			<table class="table table-dark table-sm" id="tablaDetalleSolicitud" hidden>
				<thead>
					<tr class="text-center roboto-medium">
						<th>#</th>
						<th>Medicamento</th>
						<th>Laboratorio</th>
						<th>Proveedor</th>
						<th>Cantidad</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
		</div>
		<p class="text-center" style="margin-top: 40px;">
			<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
			&nbsp; &nbsp;
			<button type="button" id="enviarSolicitudCompra" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
		</p>
	</form>
</div>
<script src="<?php echo SERVERURL; ?>buscadores/nombreProveedor_creaCompra.js"></script>
<script src="<?php echo SERVERURL; ?>buscadores/nombreLaboratorio_creaCompra.js"></script>
<!--Para  buscador textbox-->
<script src="<?php echo SERVERURL; ?>buscadores/Medicamento_creaCompra.js"></script>
<!--Para  buscador textbox-->
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
</script>
<!-- JAAJJAJAJAJAJAJA -->
<script>
	$(document).ready(function() {
		var contador = 0;
		var matrizDetalleSolicitud = [];
		$(document).on('click', '#agregarDatosTabla', function() {
			let medicamento = $('#medicamento').val();
			let laboratorio = $('#item_laboratorio').val();
			let proveedor = $('#proveedor_reg').val()
			let cantidad = $('#cantidad').val();
			if (medicamento != "" && laboratorio != null && proveedor != "" && cantidad != "") {
				/* Separamos los datos */
				medicamento = medicamento.split("__");
				proveedor = proveedor.split("__");
				laboratorio = laboratorio.split("__");
				/* Agregamos a la tabla para visualizacion del usuario */
				contador++;
				$('#tablaDetalleSolicitud').append('<tr class="text-center elementoFilaTabla">' +
					'<td>' + contador + '</td>' +
					'<td>' + medicamento[0] + '</td>' +
					'<td>' + laboratorio[0] + '</td>' +
					'<td>' + proveedor[0] + '</td>' +
					'<td>' + cantidad + '</td>' +
					'<td><a class="quitar" title="Quitar"><i class="fa-solid fa-trash-can"></i></a></td>' +
					'</tr>');
				/* Hacemos visible la tabla */
				$('#tablaDetalleSolicitud').removeAttr('hidden');
				/* Rellenado de la matriz */
				matrizDetalleSolicitud.push({
					"Medicamento": medicamento[1],
					"Laboratorio": laboratorio[1],
					"Proveedor": proveedor[1],
					"Cantidad": cantidad,
					"Indice": contador
				});
				console.log(matrizDetalleSolicitud);
				/* Limpiar campos */
				$('#medicamento').val("");
				$('#item_laboratorio').val("");
				$('#proveedor_reg').val("");
				$('#cantidad').val("");
			} else {
				Swal.fire({
					title: "Ocurrió un error inesperado",
					text: "Campos requeridos no llenados",
					type: "error",
					confirmButtonText: "Aceptar"
				});
			}
		});
		$(document).on('click', '#enviarSolicitudCompra', async function() {
			if (matrizDetalleSolicitud.length > 0) {
				if (await confirmar()) {
					let descripcion = $('#descripcion').val();
					$.post("../ajax/comprasFarmaciaAjax.php", {
						descripcion,
						matrizDetalleSolicitud
					}, function(response) {
						console.log(response);
						let repuesta = JSON.parse(response);
						switch (repuesta.repuesta.estado) {
							case 'exito': {
								matrizDetalleSolicitud = [];
								$('#medicamento').val("");
								$('#item_laboratorio').val("");
								$('#proveedor_reg').val("");
								$('#cantidad').val("");
								$('#descripcion').val("");
								$('#tablaDetalleSolicitud > tbody').empty();
								$('#tablaDetalleSolicitud').attr("hidden", "");
								exito(undefined, "Se ha registrado la solicitud de compra.");
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
			} else {
				let medicamento = $('#medicamento').val();
				let laboratorio = $('#item_laboratorio').val();
				let proveedor = $('#proveedor_reg').val()
				let cantidad = $('#cantidad').val();
				if (medicamento != "" && laboratorio != null && proveedor != "" && cantidad != "") {
					if (await confirmar()) {
						/* Separamos los datos */
						medicamento = medicamento.split("__");
						proveedor = proveedor.split("__");
						laboratorio = laboratorio.split("__");
						/* Rellenado de la matriz */
						matrizDetalleSolicitud.push({
							"Medicamento": medicamento[1],
							"Laboratorio": laboratorio[1],
							"Proveedor": proveedor[1],
							"Cantidad": cantidad
						});
						let descripcion = $('#descripcion').val();
						$.post("../ajax/comprasFarmaciaAjax.php", {
							descripcion,
							matrizDetalleSolicitud
						}, function(response) {
							console.log(response);
							let repuesta = JSON.parse(response);
							switch (repuesta.repuesta.estado) {
								case 'exito': {
									/* Limpiar campos */
									$('#medicamento').val("");
									$('#item_laboratorio').val("");
									$('#proveedor_reg').val("");
									$('#cantidad').val("");
									$('#descripcion').val("");
									matrizDetalleSolicitud = [];
									exito(undefined, "Se ha registrado la solicitud de compra.");
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
				} else {
					Swal.fire({
						title: "Ocurrió un error inesperado",
						text: "Campos requeridos no llenados",
						type: "error",
						confirmButtonText: "Aceptar"
					});
				}
			}
		});
		$(document).on('click', '.quitar', async function() {
			if (await confirmar("Estas seguro?", "Desea remover este registro?")) {
				let pru = $(this)[0].parentElement.parentElement;

				let indice = $(pru).children().eq(0).text()
				console.log(pru);
				console.log(indice);

				let indexForDelete = matrizDetalleSolicitud.findIndex(elemento => elemento.Indice == indice);

				//Eliminando item
				matrizDetalleSolicitud.splice(indexForDelete, 1);
				console.log("Separador");
				console.log(matrizDetalleSolicitud);

				$(pru).remove();

				let elementosFilasTabla = $('.elementoFilaTabla');
				console.log("Separador pequeño");
				console.log(elementosFilasTabla);

				if ($(elementosFilasTabla).length <= 0) {
					$('#tablaDetalleSolicitud').attr("hidden", "true");
					contador = 0;
				}
			}

		});
	});
</script>