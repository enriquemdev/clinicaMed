<?php
//SEGURIDAD AÑADIDA EL 03/04/2022
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;

    $empleado[0]=false;
    $familiar[0]=false;
    $cargos[0]=false;
    $especialidad[0]=false;
    $academicos[0]=false;

    foreach ($permisos as $key) {
      if(($key['CodigoSubModulo']==3 || $key['CodigoSubModulo']==4 || $key['CodigoSubModulo']==5 || $key['CodigoSubModulo']==6 || $key['CodigoSubModulo']==7) && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if(($key['CodigoSubModulo']==3 || $key['CodigoSubModulo']==4 || $key['CodigoSubModulo']==5 || $key['CodigoSubModulo']==6 || $key['CodigoSubModulo']==7) && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if(($key['CodigoSubModulo']==3 || $key['CodigoSubModulo']==4 || $key['CodigoSubModulo']==5 || $key['CodigoSubModulo']==6 || $key['CodigoSubModulo']==7) && $key['CodPrivilegio']==3){
        $actualizar=true;
      }

	if ($key['CodigoSubModulo'] == 3)//submodulo solicitud de consulta
	{//Si tiene el modulo consulta
		$empleado[0]=true;//Se mirara el boton
	
		if($key['CodPrivilegio'] == 2)
		{//Si es el privilegio ver
			$empleado[1]=2;//Ira a la lista
		}
		else if ($key['CodPrivilegio'] == 1)
		{
			$empleado[1]=1;
		}
	}

if($key['CodigoSubModulo']==6){//Si tiene el modulo consulta
	$familiar[0]=true;//Se mirara el boton

	if($key['CodPrivilegio']==2){//Si es el privilegio ver
		$familiar[1]=2;//Ira a la lista
	}else if($key['CodPrivilegio']==1){
		$familiar[1]=1;
	}
	}

if($key['CodigoSubModulo']==7){
        $cargos[0]=true;

        if($key['CodPrivilegio']==2){
        	$cargos[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$cargos[1]=1;
        }
      }

      if($key['CodigoSubModulo']==4){
        $especialidad[0]=true;

        if($key['CodPrivilegio']==2){
        	$especialidad[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$especialidad[1]=1;
        }
      }

      if($key['CodigoSubModulo']==5){
        $academicos[0]=true;

        if($key['CodPrivilegio']==2){
        	$academicos[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$academicos[1]=1;
        }
      }

    }

    if($ver==false && $agregar==false && $actualizar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION EMPLEADO
				</h3>
			</div>
		
			<!-- Content -->
			<div class="full-box tile-container">

				<?php
				if ($empleado[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{			
				?>
					<a href="<?php echo SERVERURL; if($empleado[1]==1){?>empleado-new/
					<?php }else if($empleado[1]==2){?>empleado-list/<?php }?>" class="tile">
						<div class="tile-tittle">EMPLEADO</div>
						<div class="tile-icon">
						<i class="fas fa-solid fa-user-doctor"></i>
							
						</div>
						
					</a>
				<?php		
				}
				?>

				<?php
				if ($familiar[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{			
				?>
					<a href="<?php echo SERVERURL; if($familiar[1]==1){?>familiares-new/
					<?php }else if($familiar[1]==2){?>familiares-list/<?php }?>" class="tile">
						<div class="tile-tittle">FAMILIARES</div>
						<div class="tile-icon">
						<i class="fa-solid fa-people-roof"></i>
							
						</div>
						
					</a>
				<?php		
				}
				?>

				<?php
				if ($cargos[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{			
				?>
					<a href="<?php echo SERVERURL; if($cargos[1]==1){?>historial-de-cargo-new/
					<?php }else if($cargos[1]==2){?>historial-de-cargo-list/<?php }?>" class="tile">
						<div class="tile-tittle">H. CARGOS</div>
						<div class="tile-icon">
						<i class="fa-solid fa-briefcase"></i>						
						</div>				
					</a>
					<?php if ($_SESSION['cargo_spm'] != 5 ) { //Si es gerente no le muestra el submodulo?> 
					<a href="<?php echo SERVERURL; ?>historial-de-cargo-solicitud/" class="tile">

					<div class="tile-tittle">SOLICITAR CARGO</div>
					<div class="tile-icon">
					<i class="fa-solid fa-hand-holding"></i>
						
					</div>
					</a>
					<?php } ?>
				<?php		
				}
				?>

				<?php
				if ($especialidad[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{			
				?>
					<a href="<?php echo SERVERURL; if($especialidad[1]==1){?>especialidad-new/
					<?php }else if($especialidad[1]==2){?>especialidad-list/<?php }?>" class="tile">
						<div class="tile-tittle">ESPECIALIDADES</div>
						<div class="tile-icon">
						<i class="fa-solid fa-stethoscope"></i>
							
						</div>
						
					</a>
				<?php		
				}
				?>

				<?php
				if ($academicos[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{			
				?>
					<a href="<?php echo SERVERURL; if($academicos[1]==1){?>estudio-academico-new/
					<?php }else if($academicos[1]==2){?>estudio-academico-list/<?php }?>" class="tile">
						<div class="tile-tittle">E. ACADÉMICO</div>
						<div class="tile-icon">
						<i class="fa-solid fa-graduation-cap"></i>
							
						</div>
						
					</a>
				<?php		
				}
				?>
				<!-- 
				<a href="<?php echo SERVERURL; ?>empleado-list/" class="tile">

					<div class="tile-tittle">EMPLEADO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
				<a href="<?php echo SERVERURL; ?>familiares-list/" class="tile">

					<div class="tile-tittle">FAMILIARES</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>

				<a href="<?php echo SERVERURL; ?>historial-de-cargo-list/" class="tile">

					<div class="tile-tittle">H. CARGOS</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>

				<a href="<?php echo SERVERURL; ?>especialidad-list/" class="tile">

					<div class="tile-tittle">ESPECIALIDADES</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
				<a href="<?php echo SERVERURL; ?>estudio-academico-list/" class="tile">

					<div class="tile-tittle">E. ACADÉMICO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
				 -->


			</div>