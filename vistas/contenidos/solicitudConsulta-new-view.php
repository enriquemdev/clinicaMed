<?php

    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==22 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>
<link rel="stylesheet" href="<?php echo SERVERURL; ?>vistas/css/buscadorForm.css">
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; ASINGACIÓN DE CONSULTA
				</h3>
				<p class="text-justify">
					Asignar consulta a doctor
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php if ($_SESSION['cargo_spm'] == 3){ ?>
						<li>
							<a class="active" href="<?php echo SERVERURL; ?>solicitudConsulta-new/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; ASIGNAR CONSULTA</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>solicitud-consulta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ASIGNACIONES DE CONSULTAS</a>
						</li>
						<li>
							<a href="<?php echo SERVERURL; ?>solicitud-consulta-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTA ASIGNADA</a>
						</li>
					<?php } ?>

				</ul>	
			</div>
		
			<!-- Content -->
			<?php 
				require_once "./controladores/consultaControlador.php";
				$ins_item = new consultaControlador();
				$datos_item=$ins_item->datos_item1_controlador();
				if($datos_item->rowCount()==1){
					$campos=$datos_item->fetch();
				}
					?>

			<div class="container-fluid">
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/solConsultaAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información de Consulta</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										
										<label for="consulta_paciente_reg" class="bmd-label-floating">NOMBRE DEL PACIENTE <span class="Obligar">*</span></label>
										<div class="autocompletar"><!-- tiii -->
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ-0-9- ]{1,60}" class="form-control inputauto" name="consulta_paciente_reg" id="consulta_paciente_reg" maxlength="30" required="">
										<input type="hidden" name="registro" value="1">
										</div>
									</div>
								</div>
								<!-- Content -->
								<div class="col-12 col-md-6">
                                    <div class="form-group">
                                                
                                        <label for="cita_doctor_reg" class="bmd-label-floating">NOMBRE DOCTOR <span class="Obligar">*</span></label>
                                        <div class="autocompletar"><!-- tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
                                        <input type="text" class="form-control" name="cita_doctor_reg" id="cita_doctor_reg" maxlength="60"required="">
                                        </div>

                                    </div>
                                </div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="motivo_consulta_reg" class="bmd-label-floating">MOTIVO ASIGNACIÓN</label>
										<input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ-0-9- ]{1,250}" class="form-control" name="motivo_consulta_reg" id="motivo_consulta_reg" maxlength="250">
									</div>
								</div>
								 
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="codigo_cita" class="bmd-label-floating">CODIGO DE CITA</label>
										<input type="text" pattern="[0-9-]{1,27}" class="form-control" name="consulta_cita_reg" id="codigo_cita" maxlength="9">
									</div>
								</div>
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="codigo_consultorio" class="bmd-label-floating"><span class="lblCombo">SELECCIONE CONSULTORIO <span class="Obligar">*</span></span></label>
										<select class="form-control" name="consulta_consultorio_reg" id="codigo_consultorio">
											<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item as $campo){ ?>
											<option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] ?></option>
											<?php }?>
										</select>
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="estado" class="bmd-label-floating">PASAR DIRECTO</label>
										<input type="checkbox" class="" name="estado_reg" id="estado" value=1></input>
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
			<script src="<?php echo SERVERURL; ?>buscadores/buscarEnConsulta.js"></script>
			<script src="<?php echo SERVERURL; ?>buscadores/codDoctor_creaSolicitudConsulta.js"></script>


