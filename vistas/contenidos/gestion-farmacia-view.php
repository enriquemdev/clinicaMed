<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;

$inventario[0] = false;
$compras[0] = false;
$ventas[0] = false;
$detalleCompra[0] = false;
$detalleVenta[0] = false;


foreach ($permisos as $key) {
	if (($key['CodigoSubModulo'] == 23 || $key['CodigoSubModulo'] == 24
			|| $key['CodigoSubModulo'] == 25 || $key['CodigoSubModulo'] == 26
			|| $key['CodigoSubModulo'] == 27)
		&& $key['CodPrivilegio'] == 1
	) {
		$agregar = true;
	}

	if (($key['CodigoSubModulo'] == 23 || $key['CodigoSubModulo'] == 24
			|| $key['CodigoSubModulo'] == 25 || $key['CodigoSubModulo'] == 26
			|| $key['CodigoSubModulo'] == 27)
		&& $key['CodPrivilegio'] == 2
	) {
		$ver = true;
	}

	if (($key['CodigoSubModulo'] == 23 || $key['CodigoSubModulo'] == 24
			|| $key['CodigoSubModulo'] == 25 || $key['CodigoSubModulo'] == 26
			|| $key['CodigoSubModulo'] == 27)
		&& $key['CodPrivilegio'] == 3
	) {
		$actualizar = true;
	}

	if ($key['CodigoSubModulo'] == 23) { //Si tiene el modulo inventario
		$inventario[0] = true; //Se mirara el boton

		if ($key['CodPrivilegio'] == 2) { //Si es el privilegio ver
			$inventario[1] = 2; //Ira a la lista
		} else if ($key['CodPrivilegio'] == 1) {
			$inventario[1] = 1;
		}
	}

	if ($key['CodigoSubModulo'] == 24) { //Si tiene el modulo compras
		$compras[0] = true; //Se mirara el boton

		if ($key['CodPrivilegio'] == 2) { //Si es el privilegio ver
			$compras[1] = 2; //Ira a la lista
		} else if ($key['CodPrivilegio'] == 1) {
			$compras[1] = 1;
		}
	}

	if ($key['CodigoSubModulo'] == 25) { //Si tiene el modulo ventas
		$ventas[0] = true; //Se mirara el boton

		if ($key['CodPrivilegio'] == 2) { //Si es el privilegio ver
			$ventas[1] = 2; //Ira a la lista
		} else if ($key['CodPrivilegio'] == 1) {
			$ventas[1] = 1;
		}
	}

	if ($key['CodigoSubModulo'] == 26) { //Si tiene el modulo ventas
		$detalleCompra[0] = true; //Se mirara el boton

		if ($key['CodPrivilegio'] == 2) { //Si es el privilegio ver
			$detalleCompra[1] = 2; //Ira a la lista
		} else if ($key['CodPrivilegio'] == 1) {
			$detalleCompra[1] = 1;
		}
	}
	if ($key['CodigoSubModulo'] == 27) { //Si tiene el modulo ventas
		$detalleVenta[0] = true; //Se mirara el boton

		if ($key['CodPrivilegio'] == 2) { //Si es el privilegio ver
			$detalleVenta[1] = 2; //Ira a la lista
		} else if ($key['CodPrivilegio'] == 1) {
			$detalleVenta[1] = 1;
		}
	}
}


if ($ver == false && $agregar == false && $actualizar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}
?>


<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION FARMACIA
	</h3>
</div>

<!-- Content -->
<div class="full-box tile-container">
	<?php
	if ($inventario[0] == true) {
	?>
		<a href="<?php echo SERVERURL;
					if ($inventario[1] == 1) { ?>inventario-farmacia-new/
					<?php } else if ($inventario[1] == 2) { ?>inventario-farmacia-list/<?php } ?>" class="tile">
			<div class="tile-tittle">Inventario</div>
			<div class="tile-icon">
				<i class="fa-solid fa-truck-ramp-box"></i>

			</div>
		</a>
	<?php
	}
	?>

	<?php
	if ($compras[0] == true) {
	?>
		<a href="<?php echo SERVERURL;
					if ($compras[1] == 1) { ?>compras-farmacia-new/
					<?php } else if ($compras[1] == 2) { ?>compras-farmacia-list/<?php } ?>" class="tile">
			<div class="tile-tittle">Compras</div>
			<div class="tile-icon">
				<i class="fa-solid fa-money-check-dollar"></i>

			</div>
		</a>
	<?php
	}
	?>

	<?php
	if ($ventas[0] == true) {
	?>
		<a href="<?php echo SERVERURL;
					if ($ventas[1] == 1) { ?>ventas-farmacia-new/
					<?php } else if ($ventas[1] == 2) { ?>ventas-farmacia-list/<?php } ?>" class="tile">
			<div class="tile-tittle">ventas</div>
			<div class="tile-icon">
				<i class="fa-solid fa-capsules"></i>

			</div>
		</a>
	<?php
	}
	?>

</div>
</section>
</main>