<?php

$auxiliar2 = new loginControlador();
$permisos= $auxiliar2 -> permisos_controlador();

$agregar=false;
$ver=false;
$actualizar=false;


foreach ($permisos as $key) {
  if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==1){
	$agregar=true;
  }

  if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==2){
	$ver=true;
  }

  if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==3){
	$actualizar=true;
  }
}


if($ver==false){
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE HISTORIAL DE CARGOS
				</h3>
				<p class="text-justify">
					Lista de todo el historial de cargos registrados
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<?php if ($_SESSION['cargo_spm'] == 5) {?>
					<li>
						<a href="<?php echo SERVERURL; ?>historial-de-cargo-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR CARGO</a>
					</li>
					<?php } ?>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>historial-de-cargo-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CARGOS</a>
					</li>

					<?php if ($_SESSION['cargo_spm'] == 5) {?>
						<li>
							<a href="<?php echo SERVERURL; ?>historial-de-solicitud-cargo-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SOLICITUDES</a>
						</li>
					<?php } ?>

					<li>
						<a href="<?php echo SERVERURL; ?>historial-de-cargo-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR HISTORIAL DE CARGO</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/historialdecargoControlador.php";
					$ins_cargo = new historialdecargoControlador ();
					echo $ins_cargo->paginador_cargos_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>