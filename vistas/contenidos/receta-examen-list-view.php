<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==3){
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION RECETA EXAMEN
				</h3>
				<p class="text-justify">
					Lista de receta examen
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a  href="<?php echo SERVERURL; ?>receta-examen/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RECETA EXAMEN</a>
					</li>
					<?php } ?>
					
					<li>
						<a  class="active" href="<?php echo SERVERURL; ?>receta-examen-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RECETA EXAMEN</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>receta-examen-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR RECETA EXAMEN</a>
					</li>
				</ul>	
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<div class="full-box page-header">
	<?php 
			require_once "./controladores/recetaExamenControlador.php";
			$ins_receta = new recetaExamenControlador();
			echo $ins_receta->paginador_receta_examen_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

		?>
	</div>