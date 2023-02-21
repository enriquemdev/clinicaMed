<?php

$auxiliar2 = new loginControlador();
$permisos= $auxiliar2 -> permisos_controlador();

$agregar=false;
$ver=false;
$actualizar=false;


foreach ($permisos as $key) {
  if($key['CodigoSubModulo']==6 && $key['CodPrivilegio']==1){
	$agregar=true;
  }

  if($key['CodigoSubModulo']==6 && $key['CodPrivilegio']==2){
	$ver=true;
  }

  if($key['CodigoSubModulo']==6 && $key['CodPrivilegio']==3){
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
					<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE FAMILIARES
				</h3>
				<p class="text-justify">
					Lista de todos los familiares registrados
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a href="<?php echo SERVERURL; ?>familiares-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR FAMILIAR</a>
					</li>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>familiares-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE FAMILIARES</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>familiares-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR FAMILIAR</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/familiarControlador.php";
					$ins_familiar = new familiarControlador();
					echo $ins_familiar->paginador_familiar_empleado_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"",0);

				?>
			</div>