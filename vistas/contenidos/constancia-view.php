<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==17 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==17 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==17 && $key['CodPrivilegio']==3){
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
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION CONSTANCIA
				</h3>
				<p class="text-justify">
					Agregar constancia
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<li>
						<a class="active" href="<?php echo SERVERURL; ?>constancia/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CONSTANCIA</a>
					</li>
                    
					<?php   
          			if($ver==true){ ?>
					<li>
						<a href="<?php echo SERVERURL; ?>constancia-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CONSTANCIA</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>constancia-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSTANCIA</a>
					</li>
					<?php } ?>
				</ul>	
			</div>
		
			<!-- Content -->
			<div class="container-fluid">
			<form class="form-neon	 FormularioAjax" action="<?php echo SERVERURL;?>ajax/constanciaAjax.php" method="POST" data-form= "save" autocomplete="off"> <!-- Esto fue modificado -->
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información De Constancia</legend>
						<div class="container-fluid">
							<div class="row">

							<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="codigo_diagnostico_reg" class="bmd-label-floating">DIAGNÓSTICO (POR PACIENTE)</label>
										<div class="autocompletar">
											<input type="text" class="form-control" name="codigo_diagnostico_reg" id="codigo_diagnostico_reg" maxlength="11" required="">
											<input type="hidden" name="codigo_diagnostico_reg2">
										</div>
										
									</div>
								</div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="razon_constancia" class="bmd-label-floating">RAZON</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚ ]{1,200}" class="form-control" name="razon_constancia_reg" id="razon_constancia" maxlength="200" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="comienzo_cons" class="bmd-label-static" >FECHA DE INICIO </label>
										<input type="datetime-local" class="form-control" name="comienzo_cons_reg" id="comienzo_cons" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="fin_cons" class="bmd-label-static" >FECHA DE FINALIZACIÓN </label>
										<input type="datetime-local" class="form-control" name="fin_cons_reg" id="fin_cons" required="">
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
			<script src="<?php echo SERVERURL; ?>buscadores/Consulta_creaConstancia.js"></script>