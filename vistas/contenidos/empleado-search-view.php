<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==3 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($ver==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

	
	require_once "./controladores/historialdecargoControlador.php";
	$ins_item = new historialdecargoControlador();
	$datos_item=$ins_item->datos_item1_controlador();					

?>

			<!-- Page header -->
			<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR EMPLEADO
				</h3>
				<p class="text-justify">
					Buscador de empleados
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a href="<?php echo SERVERURL; ?>empleado-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR EMPLEADO</a>
					</li>
					<?php } ?>

					<li>
						<a href="<?php echo SERVERURL; ?>empleado-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS</a>
					</li>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>empleado-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR EMPLEADO</a>
					</li>
				</ul>	
			</div>
<!--1 -->	<?php 
				if(!isset($_SESSION['busqueda_empleado']) && empty($_SESSION['busqueda_empleado']) ){//se le pone buqueda_ y luego el nombre del modulo

			 ?>	
			<!-- Content here-->
			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "default" autocomplete="off">
					<input type="hidden" name="modulo" value="empleado"><!-- input hidden para las busquedas -->

					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">
								<div class="form-group">
									<label for="inputSearch" class="bmd-label-floating">¿Qué empleado estas buscando?</label>
									<input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30"><!--//busqueda_inicial -->
								</div>
								<!-- <div>
										<br>
										<label for="inputCheck" class="">Que salga el paciente 1 &nbsp;</label>
										<input type="checkbox" class="" name="condicion" id="inputCheck">
									</div> -->

								<div class="row">
									<div class="col-6">
										<br>
										<h5>Genero: </h5>
										<input type="radio" name="condRadio" id="Hombre" value="1">
										<label for="Hombre">Hombre</label>

										<input type="radio" name="condRadio" id="Mujer" value="2">
										<label for="Mujer">Mujer</label>
									</div>

									<div class="col-6">
										<br>
										<h5>Estado: </h5>
										
										<input type="radio" name="condRadio2" id="eAct" value="1">
										<label for="eAct">Activo</label>

										<input type="radio" name="condRadio2" id="eInact" value="2">
										<label for="eInact">Inactivo</label>
									</div>
								</div>

								<div class="col-12 col-md-6" style="padding-left: 0;">
									<br>
                                        <label for="cargo" class="">BUSCAR POR CARGO</label>
                                        <select class="form-control" name="combobox" id="cargo">
                                                <option value="" selected="" disabled=""></option>
                                                <?php foreach($datos_item as $campo){ ?>
                                                <option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] //CAMBIAR POR DATOS DE CARGOS?></option>
                                                <?php }?>
                                        </select>
                                    </div>

							</div>
							<div class="col-12">
								<p class="text-center" style="margin-top: 40px;">
									<button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>

<!--2 -->	<?php 
				}else{

			 ?>	
			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "search" autocomplete="off">
					<input type="hidden" name="modulo" value="empleado"><!-- input hidden para las busquedas -->

					<input type="hidden" name="eliminar_busqueda" value="eliminar"><!--name="eliminar_busqueda" -->
					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">

								<p class="text-center" style="font-size: 20px;">
									Resultados de la búsqueda: <strong> <?php echo($_SESSION['busqueda_empleado']); ?> </strong>
								</p><!-- ti -->

							</div>
							<div class="col-12">
								<p class="text-center" style="margin-top: 20px;">
									<button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="container-fluid">
				<?php 
					require_once "./controladores/empleadosControlador.php";
					$ins_empleado = new empleadosControlador();
					echo $ins_empleado->paginador_persona_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],$_SESSION['busqueda_empleado'],
					$_SESSION['condicion'], $_SESSION['condRadio'], $_SESSION['condRadio2'], $_SESSION['combobox']);

				?>
			</div>

		</section>

<!--3 -->	<?php 
				}

			 ?>