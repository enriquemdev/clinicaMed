<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;

    $consulta[0]=false;
    $diagnostico[0]=false;
    $constancia[0]=false;
    $signos[0]=false;
    $recMed[0]=false;
    $recEx[0]=false;
	//24/032022
	$solCon[0]=false;//Variable para el nuevo submodulo solicitud de consulta.



    foreach ($permisos as $key) {
      if(($key['CodigoSubModulo']==22 || $key['CodigoSubModulo']==12 || $key['CodigoSubModulo']==13 || $key['CodigoSubModulo']==14 || $key['CodigoSubModulo']==15 || $key['CodigoSubModulo']==16 || $key['CodigoSubModulo']==17) && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if(($key['CodigoSubModulo']==22 || $key['CodigoSubModulo']==12 || $key['CodigoSubModulo']==13 || $key['CodigoSubModulo']==14 || $key['CodigoSubModulo']==15 || $key['CodigoSubModulo']==16 || $key['CodigoSubModulo']==17) && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if(($key['CodigoSubModulo']==22 || $key['CodigoSubModulo']==12 || $key['CodigoSubModulo']==13 || $key['CodigoSubModulo']==14 || $key['CodigoSubModulo']==15 || $key['CodigoSubModulo']==16 || $key['CodigoSubModulo']==17) && $key['CodPrivilegio']==3){
        $actualizar=true;
      }

	if ($key['CodigoSubModulo'] == 22)//submodulo solicitud de consulta
	{//Si tiene el modulo consulta
		$solCon[0]=true;//Se mirara el boton
	
		if($key['CodPrivilegio'] == 2)
		{//Si es el privilegio ver
			$solCon[1]=2;//Ira a la lista
		}
		else if ($key['CodPrivilegio'] == 1)
		{
			$solCon[1]=1;
		}
	}

if($key['CodigoSubModulo']==12){//Si tiene el modulo consulta
	$consulta[0]=true;//Se mirara el boton

	if($key['CodPrivilegio']==2){//Si es el privilegio ver
		$consulta[1]=2;//Ira a la lista
	}else if($key['CodPrivilegio']==1){
		$consulta[1]=1;
	}
	}

if($key['CodigoSubModulo']==13){
        $signos[0]=true;

        if($key['CodPrivilegio']==2){
        	$signos[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$signos[1]=1;
        }
      }

      if($key['CodigoSubModulo']==14){
        $diagnostico[0]=true;

        if($key['CodPrivilegio']==2){
        	$diagnostico[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$diagnostico[1]=1;
        }
      }

      if($key['CodigoSubModulo']==15){
        $recMed[0]=true;

        if($key['CodPrivilegio']==2){
        	$recMed[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$recMed[1]=1;
        }
      }

      if($key['CodigoSubModulo']==16){
        $recEx[0]=true;

        if($key['CodPrivilegio']==2){
        	$recEx[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$recEx[1]=1;
        }
      }

      if($key['CodigoSubModulo']==17){
        $constancia[0]=true;

        if($key['CodPrivilegio']==2){
        	$constancia[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$constancia[1]=1;
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION CONSULTA
				</h3>
			</div>
		
			<!-- Content -->
			<div class="full-box tile-container">

				<!-- 24/03/2022-->
				<?php
				if ($solCon[0]==true)//Si es dr no se muestra solicitud consulta de otro modo si
				{
					
				?>
					<a href="<?php echo SERVERURL; if($solCon[1]==1){?>solicitudConsulta-new/
					<?php }else if($solCon[1]==2){?>solicitud-consulta-list/<?php }?>" class="tile">
						<div class="tile-tittle" style="font-size: 87%;">CONSULTAS ASIGNADAS</div>
						<div class="tile-icon">
						<i class=" fas fa-regular fa-hand-holding-medical"></i>
							
						</div>
					</a>
				<?php		
				}
				?>

				<?php
				if($signos[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($signos[1]==1){?>signos-vitales/
					<?php }else if($signos[1]==2){?>signos-vitales-list/<?php }?>" class="tile">
					<div class="tile-tittle">SIGNOS VITALES</div>
					<div class="tile-icon">
					<i class="fa-solid fa-heart-pulse"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php	
				if($consulta[0]==true && $_SESSION['cargo_spm'] != 1){
				?>

				<a href="<?php echo SERVERURL; 
				if($consulta[1]==1){
					?>consulta/
					<?php }
				else if($consulta[1]==2){ 
						if($_SESSION['cargo_spm'] == 2 ){ 
							?>solicitud-consultadr-list/<?php 
						}
						else{ //en caso de ser recepcionista solo le salen las terminadas
							?>consulta-list/<?php 
						} 
						}?>" class="tile">

					<div class="tile-tittle">CONSULTAS </div>
					<div class="tile-icon">
					<i class="fas fa-solid fa-file-waveform"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php
				if($diagnostico[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($diagnostico[1]==1){?>diagnostico/
					<?php }else if($diagnostico[1]==2){?>diagnostico-list/<?php }?>" class="tile">
					<div class="tile-tittle">DIAGNOSTICO</div>
					<div class="tile-icon">
					<i class="fa-solid fa-comment-medical"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php
				if($recMed[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($recMed[1]==1){?>receta-medica/
					<?php }else if($recMed[1]==2){?>receta-medica-list/<?php }?>" class="tile">
					<div class="tile-tittle">RECETA MEDICA</div>
					<div class="tile-icon">
					<i class="fa-solid fa-notes-medical"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php
				if($recEx[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($recEx[1]==1){?>receta-examen/
					<?php }else if($recEx[1]==2){?>receta-examen-list/<?php }?>" class="tile">
					<div class="tile-tittle">RECETA DE EXAMEN</div>
					<div class="tile-icon">
					<i class="fa-solid fa-file-medical"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php
				if($constancia[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($constancia[1]==1){?>constancia/
					<?php }else if($constancia[1]==2){?>constancia-list/<?php }?>" class="tile">
					<div class="tile-tittle">CONSTANCIA</div>
					<div class="tile-icon">
					<i class="fa-solid fa-bed-pulse"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<!-- 
                <a href="<?php echo SERVERURL; ?>diagnostico/" class="tile">
					<div class="tile-tittle">DIAGNOSTICO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>receta-medica/" class="tile">
					<div class="tile-tittle">RECETA MEDICA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>receta-examen/" class="tile">
					<div class="tile-tittle">RECETA DE EXAMEN</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>constancia/" class="tile">
					<div class="tile-tittle">CONSTANCIA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
						
					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>signos-vitales/" class="tile">
					<div class="tile-tittle">SIGNOS VITALES</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>
					
					</div>
				</a>

				Content -->	
			</div>