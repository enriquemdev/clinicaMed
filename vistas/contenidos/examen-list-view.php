<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==18 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==18 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==18 && $key['CodPrivilegio']==3){
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
					<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EXÁMENES
				</h3>
				<p class="text-justify">
					Lista de todos los exámenes
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a  href="<?php echo SERVERURL; ?>examen-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR EXAMEN</a>
					</li>
					<?php } ?>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>examen-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EXÁMENES</a>
					</li>
					<li>
						<a href="<?php echo SERVERURL; ?>examen-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR EXAMEN</a>
					</li>
				</ul>	
			</div>
			<!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/examenControlador.php";
					$ins_examen = new examenControlador();
					echo $ins_examen->paginador_examen_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>