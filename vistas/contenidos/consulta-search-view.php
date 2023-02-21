	<!-- Page header -->
	<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTA
				</h3>
				<p class="text-justify">
					Buscador de consulta
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
				<?php if ($_SESSION['cargo_spm'] == 2){ //Aquí tocó luis, agregó acceso a lista nueva para tomar signos vitales?>
						<li>
							<a  href="<?php echo SERVERURL; ?>solicitud-consulta-asignada-list/"><i class="fa-solid fa-list"></i>&nbsp; CONSULTAS ASIGNADAS</a>
						</li>
            
						
					<?php } ?>
					<?php if ($_SESSION['cargo_spm'] == 2){ ?>
						<li>
							<a href="<?php echo SERVERURL; ?>solicitud-consultadr-list/"><i class="fa-solid fa-list-ol"></i> &nbsp; CONSULTAS EN ESPERA</a>
						</li>
            
						
					<?php } ?>
					
                    <li>
						<a href="<?php echo SERVERURL; ?>consulta-list/"><i class="fa-solid fa-list-check"></i> &nbsp; LISTA DE CONSULTAS TERMINADAS</a>
					</li>

					
                    <li>
						<a class="active" href="<?php echo SERVERURL; ?>consulta-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTAS CONCLUIDAS</a>
					</li>

				</ul>	
			</div>
<!--1 -->	<?php 
				if(!isset($_SESSION['busqueda_consulta']) && empty($_SESSION['busqueda_consulta']) ){//se le pone buqueda_ y luego el nombre del modulo

			 ?>	
			<!-- Content here-->
			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "default" autocomplete="off">
					<input type="hidden" name="modulo" value="consulta"><!-- input hidden para las busquedas -->

					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">
								<div class="form-group">
									<label for="inputSearch" class="bmd-label-floating">¿Qué consulta estas buscando?</label>
									<input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30"><!--//busqueda_inicial -->
								</div>

								<div>
									<label for="fechaConsulta">Fecha Realizada</label>
									<input type="date" class="form-control" name="combobox" id="fechaConsulta" >
								</div>
							</div>
							<div class="col-12">
								<p class="text-center" style="margin-top: 40px;">
									<button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>

<!--2 -->	<?php 
				}else{

			 ?>	
			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "search" autocomplete="off">
					<input type="hidden" name="modulo" value="consulta"><!-- input hidden para las busquedas -->

					<input type="hidden" name="eliminar_busqueda" value="eliminar"><!--name="eliminar_busqueda" -->
					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">

								<p class="text-center" style="font-size: 20px;">
									Resultados de la búsqueda: <strong> <?php echo($_SESSION['busqueda_consulta']); ?> </strong>
								</p><!-- ti -->

							</div>
							<div class="col-12">
								<p class="text-center" style="margin-top: 20px;">
									<button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
								</p>
							</div>
						</div>
					</div>
				</form>
			</div>

			<div class="container-fluid">
				<?php 
					require_once "./controladores/consultaControlador.php";
					$ins_consulta = new consultaControlador();
					echo $ins_consulta->paginador_consulta_realizada_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],$_SESSION['busqueda_consulta'],$_SESSION['combobox']);
				?>
			</div>

		</section>

<!--3 -->	<?php 
				}

			 ?>