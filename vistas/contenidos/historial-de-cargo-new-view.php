<?php

    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==7 && $key['CodPrivilegio']==3){
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
					<i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR CARGO
				</h3>
				<p class="text-justify">
					Asigne nuevo cargo
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>historial-de-cargo-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR CARGO</a>
					</li>

					<li>
						<a href="<?php echo SERVERURL; ?>historial-de-cargo-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CARGOS</a>
					</li>
					<?php if ($_SESSION['cargo_spm'] == 5)//Si es gerente mostrar la lista de solicitudes
					{ ?>
					<li>
						<a href="<?php echo SERVERURL; ?>historial-de-solicitud-cargo-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SOLICITUDES</a>
					</li>
					<?php  } ?>
					<li>
						<a href="<?php echo SERVERURL; ?>historial-de-cargo-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR HISTORIAL DE CARGO</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<?php 
				require_once "./controladores/historialdecargoControlador.php";
				$ins_item = new historialdecargoControlador();
				$datos_item=$ins_item->datos_item1_controlador();
                $datos_item2=$ins_item->datos_item2_controlador();

					?>
			<div class="container-fluid">
			<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/historialdecargoAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="cargo_empleado_reg" class="bmd-label-floating">NOMBRE EMPLEADO <span class="Obligar">*</span></label>
										<div class="autocompletar">
											<input type="text" class="form-control" name="cargo_empleado_reg" id="cargo_empleado_reg" maxlength="70" required="">
										</div>
									</div>
								</div>
                                <div class="col-12 col-md-6">
                                        <label for="cargo" class="bmd-label-floating">CARGO <span class="Obligar">*</span></label>
                                        <select class="form-control" name="cargo_reg" id="cargo">
                                                <option value="" selected="" disabled=""></option>
                                                <?php foreach($datos_item as $campo){ ?>
                                                <option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] //CAMBIAR POR DATOS DE CARGOS?></option>
                                                <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="asig_cargo" class="bmd-label-static" >FECHA DE ASIGNACIÓN <span class="Obligar">*</span></label>
										<input type="date" class="form-control" name="asig_cargo_reg" id="asig_cargo" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="salario_cargo" class="bmd-label-floating">SALARIO (C$) <span class="Obligar">*</span></label>
										<input type="number" required name="salario_cargo_reg" id="salario_cargo" min="0" value="0" step="0.1">
									</div>
								</div>
								<div class="col-12 col-md-6">
												<label for="estado_cargos" class="bmd-label-floating">ESTADO <span class="Obligar">*</span></label>
												<select class="form-control" name="estado_cargos_reg" id="estado_cargos">
														<option value="" selected="" disabled=""></option>
														<?php foreach($datos_item2 as $campo2){ ?>
														<option value="<?php echo$campo2['ID'] ?>"><?php echo$campo2['NombreEstado'] //CAMBIAR POR DATOS DE SALA MEDICA?></option>
														<?php }?>
												</select>
											</div>
								<!-- 
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="regitrador_cargo" class="bmd-label-floating">REGISTRADO POR (EN UN FUTURO IMPLICITO)</label>
										<input type="text" pattern="[1-9]{1,30}" class="form-control" name="regitrador_cargo_reg" id="regitrador_cargo" maxlength="30">
									</div>
								</div>
								
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="aprob_cargo" class="bmd-label-floating">APROBADO POR (NOMBRE) <span class="Obligar">*</span></label>
										<input type="text" pattern="[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,30}" class="form-control" name="aprob_cargo_reg" id="aprob_cargo" maxlength="30" required="">
									</div>
								</div>
								-->

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
			<script src="<?php echo SERVERURL; ?>buscadores/codEmpleado_asignaCargo.js"></script><!--Para  buscador textbox-->
