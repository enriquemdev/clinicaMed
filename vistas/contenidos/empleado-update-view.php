<?php 
        $auxiliar = new loginControlador();
        $listaVistas = $auxiliar -> navLateral_controlador();

    

?>

<!-- Page content -->
<?php 
	//Datos de empleado
	require_once "./controladores/empleadosControlador.php";
	$ins_emple = new empleadosControlador();
	$datos_emple=$ins_emple->datos_empleado_controladorUPDATE($pagina[1]);

	if($datos_emple->rowCount()==1){
		$campos=$datos_emple->fetch();

		//Datos de selects
		require_once "./controladores/empleadosControlador.php";
				$ins_item = new empleadosControlador();
				$datos_item=$ins_item->datos_item1_controlador();
				$datos_item1=$ins_item->datos_item2_controlador();
				$datos_item2=$ins_item->datos_item3_controlador();
				if($datos_item->rowCount()==1){
					$campos=$datos_item->fetch();
				}
				if($datos_item1->rowCount()==1){
					$campos1=$datos_item1->fetch();
				}
				if($datos_item2->rowCount()==1){
					$campos2=$datos_item2->fetch();
				}
					
	?>

			<!-- Page header -->
			<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-plus fa-fw"></i> &nbsp; ACTUALIZAR EMPLEADO
				</h3>
				<p class="text-justify">
					Actualizar informacion del empleado
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>empleado-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ACTUALIZAR EMPLEADO</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<div class="container-fluid">
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/EmpleadoAjax.php" method="POST" data-form= "update" autocomplete="off"> <!-- Esto fue modificado -->
				<input type="hidden" name="empleado_codigo_up" value="<?php echo $pagina[1]; ?>">
				<input type="hidden" name="cedoriginal_up" value="<?php echo $campos['Cedula'] ?>">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
									<label for="empleado_nombre" class="bmd-label-floating">NOMBRE <span class="Obligar">*</span></label>
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,70}" class="form-control" name="empleado_nombre_up" id="empleado_nombre" maxlength="40" required="" value="<?php echo $campos['Nombres'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_apellidos" class="bmd-label-floating">APELLIDOS <span class="Obligar">*</span></label>
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,70}" class="form-control" name="empleado_apellido_up" id="empleado_apellido" maxlength="40" required="" value="<?php echo $campos['Apellidos'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_inss" class="bmd-label-floating">INSS</label>
										<input type="text" pattern="[0-9-]{9,9}" class="form-control" name="empleado_inss_up" id="empleado_inss" maxlength="9" required="" value="<?php echo $campos['INSS'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_cedula" class="bmd-label-floating">CEDULA <span class="Obligar">*</span></label>
										<input type="text" pattern="[a-zA-Z0-9- ]{16,16}" class="form-control" name="empleado_cedula_up" id="empleado_cedula" maxlength="16" required="" value="<?php echo $campos['Cedula'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									
										<label for="item_genero" class="bmd-label-floating">GENERO <span class="Obligar">*</span></label>
										<select class="form-control" name="item_genero_up" id="item_genero" required="">
										<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item as $campo){ ?>
											<option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] ?></option>
											<?php }?>
										</select>
		
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_nacio" class="bmd-label-static" >FECHA DE NACIMIENTO <span class="Obligar">*</span></label>
										<input type="date" class="form-control" name="empleado_nacio_up" id="empleado_nacio" required="" value="<?php echo $campos['Fecha_de_nacimiento'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<label for="item_estado_civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
										<select class="form-control" name="item_civil_up" id="item_genero" required="">
											<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item1 as $campo1){ ?>
											<option value="<?php echo$campo1['ID'] ?>"><?php echo$campo1['Nombre'] ?></option>
											<?php }?>
										</select>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_direccion" class="bmd-label-floating">DIRECCIÓN</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="empleado_direccion_up" id="empleado_direccion" maxlength="150" required="" value="<?php echo $campos['Direccion'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_telefono" class="bmd-label-floating">TELEFONO</label>
										<input type="text" pattern="[0-9#-+ ]{8,15}" class="form-control" name="empleado_telefono_up" id="" maxlength="14" required="" value="<?php echo $campos['Telefono'] ?>">
									</div>
								</div>
							
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="empleado_correo" class="bmd-label-floating">CORREO ELECTRÓNICO</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#@\- ]{1,150}" class="form-control" name="empleado_correo_up" id="empleado_correo" maxlength="70" required="" value="<?php echo $campos['Email'] ?>">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<label for="item_estado" class="bmd-label-floating">ESTADO <span class="Obligar">*</span></label>
										<select class="form-control" name="item_estado_up" id="item_estado" required="">
											<option value="" selected="" disabled=""></option>
											<option value="1">Activo</option>
											<option value="2">Inactivo</option>
										</select>
								</div>			
								
								
							
							</div>
						</div>
					</fieldset>
					<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
					</p>
				</form>
			</div>
			<?php 
			
			/*if($lc->encryption($_SESSION['id_spm'])!=$pagina[1]){ ?>
						<input type="hidden" name="tipo_cuenta" value="Impropia">
					<?php }else{?>
						<input type="hidden" name="tipo_cuenta" value="Propia">
					<?php } 
			*/?>
			<?php 	} else { ?>
				<div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
			</div>
			<?php }		?>