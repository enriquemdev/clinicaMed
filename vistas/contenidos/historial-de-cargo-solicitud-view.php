<?php


?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-plus fa-fw"></i> &nbsp; SOLICITAR CAMBIO DE CARGO
				</h3>
				<p class="text-justify">
					Proponga nuevo cargo
				</p>
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
                                        <label for="cargo" class="bmd-label-floating">CARGO POR SOLICITAR<span class="Obligar">*</span></label>
                                        <select class="form-control" name="cargo_reg" id="cargo">
                                                <option value="" selected="" disabled=""></option>
                                                <?php foreach($datos_item as $campo){ ?>
                                                <option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] //CAMBIAR POR DATOS DE CARGOS?></option>
                                                <?php }?>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="asig_cargo" class="bmd-label-static" >FECHA DE ASIGNACIÓN SOLICITADA<span class="Obligar">*</span></label>
										<input type="date" class="form-control" name="asig_cargo_reg" id="asig_cargo" required="">
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="salario_cargo" class="bmd-label-floating">SALARIO PROPUESTO (C$) <span class="Obligar">*</span></label>
										<input type="number" required name="salario_cargo_reg" id="salario_cargo" min="0" value="0" step="0.1">
									</div>
								</div>
								<!-- Solo el gerente tiene acceso a esta parte 
								<div class="col-12 col-md-6">
												<label for="estado_cargos" class="bmd-label-floating">ESTADO <span class="Obligar">*</span></label>
												<select class="form-control" name="estado_cargos_reg" id="estado_cargos">
														<option value="" selected="" disabled=""></option>
														<?php // foreach($datos_item2 as $campo2){ ?>
														<option value="<?php // echo$campo2['ID'] ?>"><?php // echo$campo2['NombreEstado'] //CAMBIAR POR DATOS DE SALA MEDICA?></option>
														<?php // }?>
												</select>
											</div>
								-->
								<input type="hidden" name="solicitud" value="1" > <!-- Se utiliza input oculto para activar ajax de solicitud-->
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="nota_cambio" class="bmd-label-floating">¿POR QUÉ SOLICITA EL CAMBIO? <span class="Obligar">*</span></label>
										<textarea type="text" class="textarea1" requiered="" name="nota_cambio" ></textarea>
									</div>
								</div>
								<!-- Se eliminó aprobado por-->
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
			<script src="<?php echo SERVERURL; ?>buscadores/codEmpleado_solicitaCargo.js"></script><!--Para  buscador textbox-->
