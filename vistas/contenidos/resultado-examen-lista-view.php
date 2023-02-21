<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==19 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==19 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==19 && $key['CodPrivilegio']==3){
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; RESULTADO DE EXAMEN
				</h3>
				<p class="text-justify">
					Lista de resultado examenes
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a  href="<?php echo SERVERURL; ?>resultado-examen-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RESULTADO DE EXAMEN</a>
					</li>
					<?php } ?>
					<li>
						<a  class="active" href="<?php echo SERVERURL; ?>resultado-examen-lista/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RESULTADO DE EXAMENES</a>
					</li>
					<!-- Identificador: 000 -->
					<li>
						<a href="<?php echo SERVERURL; ?>resultado-examen-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR RESULTADO EXAMEN</a>
					</li>
					<!-- Termino Identificador: 000 -->
				</ul>	
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/resultadoExamenControlador.php";
					$ins_examen = new resultadoExamenControlador();
					echo $ins_examen->paginador__resultado_examen_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>