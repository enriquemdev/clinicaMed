<?php 
		$auxiliar = new loginControlador();
		$listaVistas = $auxiliar -> navLateral_controlador();/*Aqui se recibe el array de la lista de la Vistas para este usuario*/
   	?>

<!-- Page header -->
<div class="full-box page-header">
        <h3 class="text-left">
            <i class="fab fa-dashcube fa-fw"></i> &nbsp; RESPALDO BASE DE DATOS
        </h3>
        <p class="text-justify">
            En este apartado se proporciona la opción de realizar un backup de la base de datos.
        </p>

    </div>
   <!-- Content -->
   <div class="container-fluid">
			
					<fieldset>
						<legend><i class="fas fa-database"></i>&nbsp; Aceptar responsabilidad</legend>
						<div class="container-fluid">
							<div class="row">
                                <!-- CHECKBOX -->
								<div class="col-12 col-md-12">
									<div class="form-group">
										<label for="empleado_nombre" class="bmd-label-floating">Al presionar marcar el checkbox está deacuerdo con la creación de un respaldo que será descargado y registrado
                                        por el usuario actual y el servidor de la aplicación <span class="Obligar">*</span></label>
                                        <div style="margin-left: 45%;">
										<input  class="form-check-input" type="checkbox" value="1" id="backup" name="backup" checked>
                                        <label class="form-check-label" for="flexCheckDefault"> Aceptar términos </label>
                                        </div>
									</div>
								</div>
                                
							</div>
						</div>
					</fieldset>
					<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						&nbsp; &nbsp;
                        <a href="<?php echo SERVERURL;?>Respaldos/RESPALDO.php" target="_blank"><button type="" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; CREAR Y DESCARGAR</button></a>
						
					</p>
			</div>	

   
</div>