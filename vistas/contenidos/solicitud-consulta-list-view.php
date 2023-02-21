<?php
    
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==3){
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; CONSULTAS ASIGNADAS
				</h3>
        <p class="text-justify">
					Lista de consulta asignadas
				</p>
			</div>
            <div class="container-fluid">
				      <ul class="full-box list-unstyled page-nav-tabs">

                <?php if ($_SESSION['cargo_spm'] == 3){ ?>
                  <li>
                    <a href="<?php echo SERVERURL; ?>solicitudConsulta-new/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; ASIGNAR CONSULTA</a>
                  </li>
                  <li>
                    <a class="active" href="<?php echo SERVERURL; ?>solicitud-consulta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ASIGNACIONES DE CONSULTAS</a>
                  </li>
                  <li>
                    <a href="<?php echo SERVERURL; ?>solicitud-consulta-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTA ASIGNADA</a>
                  </li>
                <?php } else{?>
                  
                  <li>
                    <a class="active" href="<?php echo SERVERURL; ?>solicitud-consulta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ASIGNACIONES DE CONSULTAS</a>
                  </li>
                  <?php  } ?>
				      </ul>	
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/solicitudconsultaControlador.php";
					$ins_consulta = new solicitudConsultaControlador();
					echo $ins_consulta->paginador_consulta_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>