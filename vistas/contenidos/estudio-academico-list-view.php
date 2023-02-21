<?php

$auxiliar2 = new loginControlador();
$permisos= $auxiliar2 -> permisos_controlador();

$agregar=false;
$ver=false;
$actualizar=false;


foreach ($permisos as $key) {
  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==1){
	$agregar=true;
  }

  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==2){
	$ver=true;
  }

  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==3){
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
					<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ESTUDIOS DE FUNCIONARIOS
				</h3>
				<p class="text-justify">
					Lista de todos los estudios de funcionarios registrados
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a href="<?php echo SERVERURL; ?>estudio-academico-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR ESTUDIO ACADÉMICO</a>
					</li>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>estudio-academico-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS CON ESTUDIOS</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>estudio-academico-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIO ACADEMICO</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/estudiosacademicosControlador.php";
					$ins_estudio = new estudiosacademicosControlador();
					echo $ins_estudio->paginador_estudio_academico_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>