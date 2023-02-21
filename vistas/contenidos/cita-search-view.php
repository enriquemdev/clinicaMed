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
			        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CITA POR FECHA
			    </h3>
			    <p class="text-justify">
			        Digita la fecha, para encontrar la cita deseada
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
                        <a href="<?php echo SERVERURL; ?>cita-list/"><i class="far fa-calendar-alt"></i> &nbsp; LISTA DE CITAS</a>
                    </li>
                    <li>
                        <a class="active" href="<?php echo SERVERURL; ?>cita-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CITA</a>
                    </li>
                </ul>
            </div>
<!--1 -->   <?php 

                if(!isset($_SESSION['fecha_inicio_cita']) && empty($_SESSION['fecha_inicio_cita']) 
                && !isset($_SESSION['fecha_final_cita']) && empty($_SESSION['fecha_final_cita'])){//se le pone buqueda_ y luego el nombre del modulo

             ?>

			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "default" autocomplete="off">
                    <input type="hidden" name="modulo" value="cita"><!--input hidden para las busquedas --> 

					<div class="container-fluid">   
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label for="busqueda_inicio_cita">Fecha inicial (día/mes/año)</label>
									<input type="date" class="form-control" name="fecha_inicio" 
                                    id="busqueda_inicio_cita"  maxlength="30">
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="form-group">
									<label for="busqueda_final_cita">Fecha final (día/mes/año)</label>
									<input type="date" class="form-control" name="fecha_final" 
                                    id="busqueda_final_cita" maxlength="30">
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

<!--2 -->   <?php 
                }else{

             ?>
			<div class="container-fluid">
				
                    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "search" autocomplete="off">
                    <input type="hidden" name="modulo" value="cita"><!--input hidden para las busquedas --> 
					<input type="hidden" name="eliminar_busqueda" value="eliminar">
					<div class="container-fluid">
						<div class="row justify-content-md-center">
                            <div class="col-12 col-md-6">
                                <p class="text-center" style="font-size: 20px;"> 
                                    Fecha de busqueda : 
                                    <strong>
                                    <?php echo date("y-m-d",strtotime($_SESSION['fecha_inicio_cita'])); ?> 
                                    &nbsp; a &nbsp;
                                    <?php echo date("y-m-d",strtotime($_SESSION['fecha_final_cita'])); ?> 
                                    </strong>
                                </p>    
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
                    require_once "./controladores/citaControlador.php";
                    $ins_cita = new citaControlador();
                    echo $ins_cita->paginador_cita_controlador(
                        $pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],
                        $_SESSION['fecha_inicio_cita'],$_SESSION['fecha_final_cita']);

                ?>
            </div>
<!--3 -->   <?php 
                }/*ESTE BUSCADOR AUN NO FUNCIONA, TRABAJA DE MANERA DISTINTA*/

             ?>