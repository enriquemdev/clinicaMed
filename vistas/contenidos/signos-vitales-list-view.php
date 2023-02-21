<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==3){
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION SIGNOS VITALES
				</h3>
				<p class="text-justify">
					Lista de signos vitales
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a  href="<?php echo SERVERURL; ?>signos-vitales/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SIGNOS VITALES</a>
					</li>
					<?php } ?>

					<li>
						<a  class="active" href="<?php echo SERVERURL; ?>signos-vitales-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SIGNOS VITALES</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>signos-vitales-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SIGNOS VITALES</a>
					</li>
				</ul>	
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/signosvitalesControlador.php";
					$ins_signos = new signosvitalesControlador();
					echo $ins_signos->paginador_signosvitales_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>