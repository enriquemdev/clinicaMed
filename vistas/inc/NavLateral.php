<?php 
		$auxiliar = new loginControlador();
		$listaVistas = $auxiliar -> navLateral_controlador();/*Aqui se recibe el array de la lista de la Vistas para este usuario*/
		$auxiliar2 = new loginControlador();
		$permisos= $auxiliar2 -> permisos_controlador();

    $agregarCita=false;
    $verCita=false;
    $actualizarCita=false;

    $agregarEmpleado=false;
    $verEmpleado=false;
    $actualizarEmpleado=false;

    $agregarPaciente=false;
    $verPaciente=false;
    $actualizarPaciente=false;
/*
    $agregarExamen=false;
    $verExamen=false;
    $actualizarExamen=false;
*/
    $agregarUsuario=false;
    $verUsuario=false;
    $actualizarUsuario=false;

    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==1){
        $agregarCita=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==2){
        $verCita=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==3){
        $actualizarCita=true;
      }

      //usuario

      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==1){
        $agregarUsuario=true;
      }

      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==2){
        $verUsuario=true;
      }

      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==3){
        $actualizarUsuario=true;
      }

      //Paciente

      if($key['CodigoSubModulo']==8 && $key['CodPrivilegio']==1){
        $agregarPaciente=true;
      }

      if($key['CodigoSubModulo']==8 && $key['CodPrivilegio']==2){
        $verPaciente=true;
      }

      if($key['CodigoSubModulo']==8 && $key['CodPrivilegio']==3){
        $actualizarPaciente=true;
      }

      //Empleado

      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==1){
        $agregarEmpleado=true;
      }

      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==2){
        $verEmpleado=true;
      }

      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==3){
        $actualizarEmpleado=true;
      }
    }

/*
		$auxiliar3 = new consultaControlador();
		$error = $auxiliar3 -> agregar_consulta_controlador();/*Aqui se recibe el array de la lista de la Vistas para este usuario*/
		
/*
		$auxiliarPermisos = new loginControlador();
		$permisos = $auxiliarPermisos -> permisos_controlador();//Aqui se recibe el array de la lista de la Vistas para este usuario
		$conta= count($permisos);


		for ($i=0; $i < $conta; $i++) { 
    
    for ($j=0; $j < 2; $j++) { 



        if($j==0){
        	if($permisos[$i][$j]=2){//2=recepcionista

        	}//cierra if permisos
                    
                }//cierra if j 0

        if($j==1){
                    $matriz[$i][$j]=$matrizEdiciones[$i][0];
                }//cierra if j 1        
    }
}//cierra for grande
*/

   	?>
	<!-- Nav lateral -->
	<section class="full-box nav-lateral">
			<div class="full-box nav-lateral-bg show-nav-lateral"></div>
			<div class="full-box nav-lateral-content">
				<figure class="full-box nav-lateral-avatar">
					<i class="far fa-times-circle show-nav-lateral"></i>
					
					<?php if(isset($_SESSION['imagen-usuario_spm']) && !empty($_SESSION['imagen-usuario_spm'])){?>
						<img src="<?php echo SERVERURL.$_SESSION['imagen-usuario_spm']; ?>" class="img-fluid" alt="Avatar">
					<?php }else{ ?>
						<img src="<?php echo SERVERURL; ?>vistas/assets/avatar/avatarU.jpg" class="img-fluid" alt="Avatar">
					<?php } ?>

					<figcaption class="roboto-medium text-center">
						<?php if($_SESSION['cargo_spm']<=0){?>
							<?php  echo("Bienvenido ".$_SESSION['usuario_spm']);?> <br><small class="roboto-condensed-light">Sin cargo asignado </small>
						<?php }else{ ?>
							<?php  echo("Bienvenido ".$_SESSION['usuario_spm']);?> <br><small class="roboto-condensed-light">Cargo: <?php echo($_SESSION['name-cargo_spm']); ?></small>
						<?php } ?>
						
					</figcaption>
				</figure>
				<div class="full-box nav-lateral-bar"></div>
				<nav class="full-box nav-lateral-menu">
				<ul>
						<li>
							<a href="<?php echo SERVERURL; ?>home/"><i class="fas fa-regular fa-house-user"></i> &nbsp; HOME</a>
						</li>

					<?php  
					//print_r($_SESSION);
					if(in_array("Empleados", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL;?>gestion-empleado/"><i class="fas fa-light fa-user-doctor"></i> &nbsp; Gestión Empleado </i></a>
						</li>
					<?php } ?>
					<?php if(in_array("Consulta", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>gestion-consulta/"><i class="fas fa-regular fa-file-medical"></i> &nbsp; Gestión Consulta </i></a>
						</li>
					<?php } ?>
					<?php if(in_array("Catalogos", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>gestion-catalogos/"><i class=" fas fa-light fa-folder"></i> &nbsp;  Gestión Catalogos</i></a>	
						</li>
					<?php } ?>

					<?php if(in_array("Paciente", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; if($verPaciente==true){?>gestion-paciente<?php }else if($agregarPaciente==true){?>paciente-new/<?php }?>"><i class="fas fa-regular fa-hospital-user"></i> &nbsp; Gestión Paciente</i></a>
						</li>
					<?php } ?>

					<?php if(in_array("Cita", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; if($verCita==true){?>cita-list/
					<?php }else if($agregarCita==true){?>cita-new/<?php }?>"><i class="far fa-calendar-alt fa-fw"></i> &nbsp; Gestión Cita </i></a>
						</li>
					<?php } ?>

					<?php if(in_array("Usuarios", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; if($verUsuario==true){?>user-list/
					<?php }else if($agregarUsuario==true){?>user-new/<?php }?>"><i class="fas  fa-light fa-user"></i> &nbsp; Gestión Usuarios </i></a>
						</li>
					<?php } ?>

					<?php if(in_array("Examen", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>gestion-examen/"><i class="fa-solid fa-x-ray"></i> &nbsp; Gestión Exámenes </i></a>
						</li>
					<?php } ?>
					<?php if(in_array("Farmacia", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>gestion-farmacia/"><i class=" fas fa-light fa-prescription-bottle-medical"></i></i> &nbsp; Gestion Farmacia </i></a>
						</li>
					<?php } ?>
					<?php  
					if(in_array("Empleados", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL;?>gestion-sistema/"><i class="fas fa-solid fa-laptop-code"></i> &nbsp; Gestión Sistema </i></a>
						</li>
					<?php } ?>

					<?php if(in_array("Caja", $listaVistas)){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>gestion-caja/"><i class=" fas fa-light fa-prescription-bottle-medical"></i></i> &nbsp; Gestion Caja </i></a>
						</li>
					<?php } ?>
						
					</ul>
				</nav>
			</div>
		</section>