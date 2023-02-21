<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==15 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==15 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==15 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

	//Para el combobox (26/03/2022)
	require_once "./controladores/recetaControlador.php";
	$ins_item = new recetaControlador();
	$datos_item=$ins_item->datos_medicamentos_controlador();
	if ($datos_item->rowCount()==1)
	{
		$campos=$datos_item->fetch();
	}
	
?>
<?php 
				require_once "./controladores/recetaControlador.php";
				$ins_receta = new recetaControlador();
				$datos_receta=$ins_receta->datos_consulta_controlador($pagina[1]);

				if($datos_receta->rowCount()==1){
					$campos=$datos_receta->fetch();
					
					?>
<link rel="stylesheet" href="<?php echo SERVERURL; ?>vistas/css/buscadorForm.css">

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION RECETA MEDICA
				</h3>
				<p class="text-justify">
					Agregar receta medica
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<li>
						<a class="active" href="<?php echo SERVERURL; ?>receta-medica/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR RECETA MEDICA</a>
					</li>
					
					<?php   
          			if($ver==true){ ?> 
					<li>
						<a href="<?php echo SERVERURL; ?>receta-medica-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE RECETA MEDICA</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>receta-medica-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR RECETA MEDICA</a>
					</li>
					<?php } ?>
				</ul>	
			</div>
		
			<!-- Content -->
			<div class="container-fluid">
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/recetaAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información De Receta Medica</legend>
						<div class="container-fluid">
							<div class="row">

								<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="codigo_consulta" class="bmd-label-floating">CODIGO CONSULTA (SOLO LECTURA)</label>
										<!-- se comentó para autorrelleno <div class="autocompletar"> tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
										<input type="text" class="form-control inputauto" name="codigo_consulta_previa_reg" id="codigo_consulta_previa_reg" maxlength="50" required="" value="<?php echo $campos['codconsulta'] ?>" readonly></div>
										<!--</div>-->
										<input type="hidden" name="codigo_consulta_reg"><!-- DECLARACIÓN DE VARIABLE NO USADA DE RECETA AUTO -->
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
										<label for="codigo_medicamento" class="bmd-label-floating"><span class="lblCombo">CODIGO MEDICAMENTO <span class="Obligar">*</span></span></label>
										<select class="form-control" name="codigo_medicamento_reg" id="codigo_medicamento">
											<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item as $campo){ ?>
											<option value="<?php echo$campo['Codigo'] ?>"><?php echo$campo['nombreComercial']." - ".$campo['presentacion'] ?></option>
											<?php }?>
										</select>
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="dosis" class="bmd-label-floating">DOSIS (UNIDAD/CUCHARADAS)</label>
										<input type="text" pattern="[0-9-]{1,27}" class="form-control" name="dosis_medicamento_reg" id="dosis_medicamento" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="frecuencia" class="bmd-label-floating">FRECUENCIA (HORAS)</label>
										<input type="text" pattern="[0-9-]{1,27}" class="form-control" name="frecuencia_medicamento_reg" id="frecuencia_medicamento" required="">
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
			<script src="<?php echo SERVERURL; ?>buscadores/Consulta_creaRecetaMedica.js"></script>
			<?php 	} else { ?>
				<div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
			</div>
			<?php }		?>