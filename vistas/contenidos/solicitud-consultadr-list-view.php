<?php
    /*
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
    */
?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; CONSULTAS EN ESPERA
				</h3>
                <p class="text-justify">
					Lista de todos las Consultas en espera
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

        			<?php if ($_SESSION['cargo_spm'] == 2){ //Aquí tocó luis, agregó acceso a lista nueva para tomar signos vitales?>
						<li>
							<a href="<?php echo SERVERURL; ?>solicitud-consulta-asignada-list/"><i class="fa-solid fa-list"></i>&nbsp; CONSULTAS ASIGNADAS</a>
						</li>
            
						
					<?php } ?>
					<?php if ($_SESSION['cargo_spm'] == 2){ ?>
						<li>
							<a class="active" href="<?php echo SERVERURL; ?>solicitud-consultadr-list/"><i class="fa-solid fa-list-ol"></i> &nbsp; CONSULTAS EN ESPERA</a>
						</li>
            
						
					<?php } ?>
					
                    <li>
						<a href="<?php echo SERVERURL; ?>consulta-list/"><i class="fa-solid fa-list-check"></i> &nbsp; LISTA DE CONSULTAS TERMINADAS</a>
					</li>

					
                    <li>
						<a href="<?php echo SERVERURL; ?>consulta-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTAS CONCLUIDAS</a>
					</li>

				</ul>	
				
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/consultaControlador.php";
					$ins_consulta = new consultaControlador();
					echo $ins_consulta->paginador_consulta_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>