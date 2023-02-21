<?php
$auxiliar2 = new loginControlador();
$permisos = $auxiliar2->permisos_controlador();

$agregar = false;
$ver = false;
$actualizar = false;

$paciente[0] = false;
$familiaresPaciente[0] = false;


foreach ($permisos as $key) {
	if (($key['CodigoSubModulo'] == 8 || $key['CodigoSubModulo'] == 10)
		&& $key['CodPrivilegio'] == 1
	) {
		$agregar = true;
	}

	if (($key['CodigoSubModulo'] == 8 || $key['CodigoSubModulo'] == 10)
		&& $key['CodPrivilegio'] == 2
	) {
		$ver = true;
	}

	if (($key['CodigoSubModulo'] == 8 || $key['CodigoSubModulo'] == 10)
		&& $key['CodPrivilegio'] == 3
	) {
		$actualizar = true;
	}
	/* XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX */
	if ($key['CodigoSubModulo'] == 8) { //Si tiene el modulo paciente
		$paciente[0] = true; //Se mirara el boton
		if ($key['CodPrivilegio'] == 1) { //Si es el privilegio ver
			$paciente[1] = 1; //Ira a agregar
		} else if ($key['CodPrivilegio'] == 2) {
			$paciente[1] = 2; //Ira a la lista
		}
	}

	if ($key['CodigoSubModulo'] == 10) { //Si tiene el modulo compras
		$familiaresPaciente[0] = true; //Se mirara el boton
		if ($key['CodPrivilegio'] == 1) { //Si es el privilegio ver
			$familiaresPaciente[1] = 1; //Ira a agregar
		} else if ($key['CodPrivilegio'] == 2) {
			$familiaresPaciente[1] = 2; //Ira a la lista
		}
	}
}


if ($ver == false && $agregar == false && $actualizar == false) {
	echo $lc->redireccionar_home_controlador();
	exit();
}
?>
<!-- Page header -->
<div class="full-box page-header">
	<h3 class="text-left">
		<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION PACIENTE
	</h3>
</div>

<!-- Content -->
<div class="full-box tile-container">

	<?php
	if ($paciente[0] == true) {
	?>
		<a href="<?php echo SERVERURL;
					if ($paciente[1] == 1) { ?>paciente-new/
					<?php } else if ($paciente[1] == 2) { ?>paciente-list/<?php } ?>" class="tile">
			<div class="tile-tittle">PACIENTE</div>
			<div class="tile-icon">
				<i class="fa-solid fa-hospital-user"></i>

			</div>
		</a>
		<?php
		if (($_SESSION['name-cargo_spm']) == "Recepcionista") {
		?>
			<a href="<?php echo SERVERURL;
						if ($paciente[1] == 1) { ?>responsable-new/
					<?php } else if ($paciente[1] == 2) { ?>responsable-list/<?php } ?>" class="tile">
				<div class="tile-tittle">RESPONSABLE</div>
				<div class="tile-icon">
					<i class="fa-solid fa-user-shield"></i>

				</div>
			</a>
			<a href="<?php echo SERVERURL; ?>paciente-new-menor/" class="tile">
				<div class="tile-tittle">PACIENTE MENOR</div>
				<div class="tile-icon">
					<i class="fa-solid fa-child-reaching"></i>
				</div>
			</a>
		<?php } ?>

	<?php
	}
	?>

	<?php
	if ($familiaresPaciente[0] == true) {
	?>
		<a href="<?php echo SERVERURL;
					if ($familiaresPaciente[1] == 1) { ?>familiar-paciente-new/
					<?php } else if ($familiaresPaciente[1] == 2) { ?>familiar-paciente-list/<?php } ?>" class="tile">
			<div class="tile-tittle">FAMILIAR</div>
			<div class="tile-icon">
				<i class="fa-solid fa-people-roof"></i>

			</div>
		</a>
	<?php
	}
	?>
</div>