<?php 
		$auxiliar = new loginControlador();
		$listaVistas = $auxiliar -> navLateral_controlador();

	if( !(in_array("Catalogos", $listaVistas)) ){
		echo $lc->forzar_cierre_sesion_controlador();
		exit();
	}

?>
<!-- Page header -->
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; CATALOGO DE ENFERMEDADES
                </h3>
            </div>
			<div class="container-fluid">
            <?php 
					require_once "./controladores/catalogosControlador.php";
					$ins_cataglogo = new catalogosControlador();
					echo $ins_cataglogo->paginador_enfermedad_controlador($pagina[1],5,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
                <form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/catEnfermedadAjax.php" method="POST" data-form= "save" autocomplete="off">
						<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; AGREGAR ENFERMEDAD</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="ENFERMEDAD-NOMBRE" class="bmd-label-floating">NOMBRE DE ENFERMEDAD</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,40}" class="form-control" name="ENFERMEDAD-NOMBRE_reg" id="ENFERMEDAD-NOMBRE" maxlength="40" required="">
							
									</div>
							    </div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="ENFERMEDAD-DESCRIPCION" class="bmd-label-floating">DESCRIPCION</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{10,200}" class="form-control" name="ENFERMEDAD-DESCRIPCION_reg" id="ENFERMEDAD-DESCRIPCION" maxlength="200" required="">
							
									</div>
							    </div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="ENFERMEDAD_TIPO" class="bmd-label-floating">TIPO DE ENFERMEDAD</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,30}" class="form-control" name="ENFERMEDAD_TIPO_reg" id="ENFERMEDAD_TIPO" maxlength="30" required="">
							
									</div>
							    </div>
						   </div>
						</div>
						<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
					</p>
					</fieldset>
					</form>
				</div>
					
            </div>
