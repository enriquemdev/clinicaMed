<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==16 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>
<?php 
				require_once "./controladores/recetaExamenControlador.php";
				$ins_receta = new recetaExamenControlador();
				$datos_receta=$ins_receta->datos_consulta_controlador($pagina[1]);

				if($datos_receta->rowCount()==1){
					$campos=$datos_receta->fetch();
					
					?>
<!-- Page header -->
<?php 
	require_once "./controladores/recetaExamenControlador.php";
	$ins_item = new recetaExamenControlador();
	$datos_item=$ins_item->datos_item1_controlador();
	if($datos_item->rowCount()==1){
		$campos=$datos_item->fetch();
	}
?>
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION RECETA EXAMEN
				</h3>
				<p class="text-justify">
					Agregar de receta examen
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>receta-examen/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RECETA EXAMEN</a>
					</li>
					
					<?php   
          			if($ver==true){ ?>
					<li>
						<a href="<?php echo SERVERURL; ?>receta-examen-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RECETA EXAMEN</a>
					</li>
					<li>	
						<a href="<?php echo SERVERURL; ?>receta-examen-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR RECETA EXAMEN</a>
					</li>
					<?php } ?>
				</ul>	
			</div>
		
		
			<!-- Content -->
			<div class="container-fluid">
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/recetaExamenAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información De Receta Examen</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="codigo_consulta" class="bmd-label-floating">CODIGO CONSULTA</label>
										<input type="text" pattern="[0-9-]{1,27}" class="form-control" name="codigo_consulta_reg2" id="codigo_consulta" required="" value="<?php echo $campos['codconsulta'] ?>" readonly>
										<input type="hidden" name="codigo_consulta_reg"><!-- DECLARACIÓN DE VARIABLE NO USADA DE RECETA NORMAL -->
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="codigo_consulta" class="bmd-label-floating">NOMBRE PACIENTE (SOLO LECTURA)</label>
										<!-- se comentó para autorrelleno <div class="autocompletar"> tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
										<input type="text" class="form-control inputauto" maxlength="50" value="<?php echo $campos['Nombres'] ?> <?php echo $campos['Apellidos'] ?>" readonly></div>
										<!--</div>-->
									</div>
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="tipo_examen" class="bmd-label-floating">TIPO DE EXAMEN</label>
										<select class="form-control" name="tipo_examen_reg" id="tipo_examen">
											<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item as $campo){ ?>
											<option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] ?></option>
											<?php }?>
										</select>
									</div>
								</div>
                                <div class="col-12 col-md-6">
									<div class="form-group">
									<label for="razon" class="bmd-label-floating">MOTIVO DE EXAMEN</label>
										<textarea name="razon" class="textarea1" rows="3" cols="100" ></textarea> <!-- Se utilizará luego -->
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
			<?php 	} else { ?>
				<div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
			</div>
			<?php }		?>