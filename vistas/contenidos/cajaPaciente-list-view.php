<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==28 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

    //   if($key['CodigoSubModulo']==28 && $key['CodPrivilegio']==2){
    //     $ver=true;
    //   }

    //   if($key['CodigoSubModulo']==8 && $key['CodPrivilegio']==3){
    //     $actualizar=true;
    //   }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

    

?>

<!-- Page header -->
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE PACIENTES  
                </h3>
                <p class="text-justify">
                    Se muestra la lista de pacientes con servicios médicos pendientes por pagar
                </p>
            </div>

            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
                        <a href="<?php echo SERVERURL; ?>cajaPaciente-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PACIENTE</a>
					</li>

                    <li>
                        <a class="active" href="<?php echo SERVERURL; ?>cajaPaciente-list/"><i class="fas fa-search fa-fw"></i> &nbsp; VER PACIENTES CON SERVICIOS PENDIENTES</a>
					</li>
					<?php } ?>

				</ul>	
			</div>


             <div class="container-fluid">
			<?php 
					require_once "./controladores/cajaControlador.php";
					$ins_caja = new cajaControlador();
					echo $ins_caja->paginador_paciente_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"","","");

				?>
			</div>