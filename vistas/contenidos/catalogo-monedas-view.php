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
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; CATALOGO MONEDAS
                </h3>
            </div>
            <div class="container-fluid">
            <?php 
					require_once "./controladores/catalogosControlador.php";
					$ins_cataglogo = new catalogosControlador();
					echo $ins_cataglogo->paginador_monedas_controlador($pagina[1],5,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
                <form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/catMonedasAjax.php" method="POST" data-form= "save" autocomplete="off">
						<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; AGREGAR MONEDA </legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="nombre_moneda_reg" class="bmd-label-floating">NOMBRE DE MONEDA</label>
										<input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,60}" class="form-control" name="nombre_moneda_reg" id="nombre_moneda_reg" maxlength="60" required="">
							
									</div>
							    </div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="simbolo_moneda_reg" class="bmd-label-floating">SIMBOLO DE MONEDA</label>
										<input type="text" pattern="{1,2}" class="form-control" name="simbolo_moneda_reg" id="simbolo_moneda_reg" maxlength="2">
										<!--[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]  -->
									</div>
							    </div>

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="descripcion_moneda_reg" class="bmd-label-floating">DESCRIPCION</label>
										<input type="text" class="form-control" name="descripcion_moneda_reg" id="descripcion_moneda_reg" maxlength="255">
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