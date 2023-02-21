<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==3){
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
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE CITAS 
                </h3>
                <p class="text-justify">
                    se mostrara todos las citas registradas 
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">

                   
                    <?php   
                    if($agregar==true){ ?> 
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA CITA</a>
                    </li>
                    <?php } ?>
                    
                    <li>
                        <a class="active" href="<?php echo SERVERURL; ?>cita-list/"><i class="far fa-calendar-alt"></i> &nbsp; LISTA DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CITA</a>
                    </li>
                </ul>
            </div>


             <div class="container-fluid">
			<?php 
					require_once "./controladores/citaControlador.php";
					$ins_cita = new citaControlador();
					echo $ins_cita->paginador_cita_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"","");

				?>
			</div>