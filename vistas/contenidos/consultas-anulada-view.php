

<?php
   /* $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==12 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==12 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==12 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }*/

?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; DATOS CONSULTA
				</h3>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					
					<li>
						<a href="<?php echo SERVERURL; ?>consulta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; TERMINAR</a>
					</li>
				</ul>	
			</div>
		
			<!-- Content -->
			<?php 
				require_once "./controladores/consultaControlador.php";
				$ins_item = new consultaControlador();
				$datos_item=$ins_item->datos_item2_controlador($pagina[1]);
				if($datos_item->rowCount()==1){
					$datos=$datos_item->fetch();
				
					?>
			<div class="container-fluid">
			<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/ConsultaAjax.php" method="POST" data-form= "update" autocomplete="off">
				<input type="hidden" name="consulta_codigo_up" value="<?php echo $pagina[1]; ?>">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información de Consulta</legend>
						<div class="container-fluid">
							<div class="row">
								
								<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="consulta_paciente_reg" class="bmd-label-floating">NOMBRE DEL PACIENTE (SOLO LECTURA)</label>
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ-0-9- ]{1,60}" class="form-control inputauto" name="nombre" id="nombre_reg" maxlength="30" required="" value="<?php echo $datos['Nombres'] ?> <?php echo $datos['Apellidos'] ?>" readonly>
										<input type="hidden" name="consulta_codigo" value="<?php echo $pagina[1] ?>">
									</div>
								</div>
								
								<!-- Content -->
								<div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="notas_anulada" class="bmd-label-floating">NOTAS DE CONSULTA <span class="Obligar">*</span></label>
										<textarea type="text" class="textarea1" name="notas_anulada_reg" id="notas_anulada" maxlength="250"required="" ></textarea>
                                    </div>
                                </div>
							</div>
						</div>
					</fieldset>
					<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
					</p>
				</form>
			</div>	
			<?php } else { ?>
				<div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
			</div>
			<?php }		?>

