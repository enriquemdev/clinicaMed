<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==13 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION SIGNOS VITALES
				</h3>
				<p class="text-justify">
					Agregar de signos vitales
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
				
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>signos-vitales/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SIGNOS VITALES</a>
					</li>
					

					<?php   
          			if($ver==true){ ?> 
					<li>
						<a href="<?php echo SERVERURL; ?>signos-vitales-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SIGNOS VITALES</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>signos-vitales-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SIGNOS VITALES</a>
					</li>
					<?php } ?>
				</ul>	
			</div>
		
			<!-- Content -->
			<div class="container-fluid">
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/signosvitalesAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información De Signos Vitales</legend>
						<div class="container-fluid">
							<div class="row">
								

								<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="codigo_consulta" class="bmd-label-floating">CONSULTA (POR PACIENTE)</label>
										<div class="autocompletar"><!-- tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
										<input type="text" class="form-control inputauto" name="codigo_consulta_reg" id="codigo_consulta_reg" maxlength="50" required=""></div>
										
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="peso_signos_reg" class="bmd-label-floating">PESO EN LBS</label>
										<input type="text" pattern="[0-9-.]{1,6}" class="form-control" name="peso_signos_reg" id="peso_signos_reg" maxlength="6" required="">
									</div>
								</div>
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="altura_signos" class="bmd-label-floating">ALTURA EN CM</label>
										<input type="text" pattern="[0-9-.]{1,6}" class="form-control" name="altura_signos_reg" id="altura_signos" maxlength="6" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="presion_arterial_signos" class="bmd-label-floating">PRESIÓN ARTERIAL mm Hg</label>
										<input type="text" pattern="[0-9-.]{1,6}" class="form-control" name="presion_arterial_signos_reg" id="presion_arterial_signos" maxlength="6" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="frecuencia_respiratoria_signos" class="bmd-label-floating">FRECUENCIA RESPIRATORIA mm Hg</label>
										<input type="text" pattern="[0-9-.]{1,6}" class="form-control" name="frecuencia_respiratoria_signos_reg" id="frecuencia_respiratoria_signos" maxlength="6" required="">
									</div>
								</div>
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="frecuencia_cardiaca_signos" class="bmd-label-floating">FRECUENCIA CARDÍACA L/M</label>
										<input type="text" pattern="[0-9-.]{1,6}" class="form-control" name="frecuencia_cardiaca_signos_reg" id="frecuencia_cardiaca_signos" maxlength="6" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="temperatura_signos" class="bmd-label-floating">TEMPERATURA °C</label>
										<input type="text" pattern="[0-9-.]{1,4}" class="form-control" name="temperatura_signos_reg" id="temperatura_signos" maxlength="4" required="">
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
			<script src="<?php echo SERVERURL; ?>buscadores/Consulta_creaSignosVitales.js"></script>	