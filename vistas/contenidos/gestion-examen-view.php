<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;

    $exam[0]=false;
    $result[0]=false;



    foreach ($permisos as $key) {
      if(($key['CodigoSubModulo']==18 || $key['CodigoSubModulo']==19) && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if(($key['CodigoSubModulo']==18 || $key['CodigoSubModulo']==19) && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if(($key['CodigoSubModulo']==18 || $key['CodigoSubModulo']==19) && $key['CodPrivilegio']==3){
        $actualizar=true;
      }

      if($key['CodigoSubModulo']==18){//Si tiene el modulo examen
        $exam[0]=true;//Se mirara el boton

        if($key['CodPrivilegio']==2){//Si es el privilegio ver
        	$exam[1]=2;//Ira a la lista
        }else if($key['CodPrivilegio']==1){
        	$exam[1]=1;
        }
      }

if($key['CodigoSubModulo']==19){
        $result[0]=true;

        if($key['CodPrivilegio']==2){
        	$result[1]=2;
        }else if($key['CodPrivilegio']==1){
        	$result[1]=1;
        }
      }

    }


    if($ver==false && $agregar==false && $actualizar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>


<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION EXAMEN
				</h3>
			</div>
		
			<!-- Content -->
			<div class="full-box tile-container">
				<?php
				if($exam[0]==true){
				?>
				<a href="<?php echo SERVERURL; if($exam[1]==1){?>examen-new/
					<?php }else if($exam[1]==2){?>examen-list/<?php }?>" class="tile">
					<div class="tile-tittle">EXAMEN</div>
					<div class="tile-icon">
					<i class="fa-solid fa-flask-vial"></i>
						
					</div>
				</a>
				<?php
				}
				?>

				<?php
				if($result[0]==true){
				?>
                <a href="<?php echo SERVERURL; if($result[1]==1){?>resultado-examen-new/
					<?php }else if($result[1]==2){?>resultado-examen-lista/<?php }?>" class="tile">
					<div class="tile-tittle">RESULTADO EXAMEN</div>
					<div class="tile-icon">
					<i class="fa-solid fa-vial-circle-check"></i>
						
					</div>
				</a>
				<?php
				}
				?>
                
			</div>

		</section>
    </main>