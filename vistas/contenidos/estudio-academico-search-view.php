<?php

$auxiliar2 = new loginControlador();
$permisos= $auxiliar2 -> permisos_controlador();

$agregar=false;
$ver=false;
$actualizar=false;


foreach ($permisos as $key) {
  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==1){
	$agregar=true;
  }

  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==2){
	$ver=true;
  }

  if($key['CodigoSubModulo']==5 && $key['CodPrivilegio']==3){
	$actualizar=true;
  }
}


if($ver==false){
	echo $lc->redireccionar_home_controlador();
	exit();
}

?>
<!-- Page header -->
	<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIO ACADEMICO
				</h3>
				<p class="text-justify">
					Buscador de estudios academicos
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<li>
						<a href="<?php echo SERVERURL; ?>estudio-academico-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; ASIGNAR ESTUDIO ACADÉMICO</a>
					</li>
					

					<li>
						<a href="<?php echo SERVERURL; ?>estudio-academico-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE EMPLEADOS CON ESTUDIOS</a>
					</li>
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>estudio-academico-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIO ACADEMICO</a>
					</li>
				</ul>	
			</div>
<!--1 -->	<?php 
				if(!isset($_SESSION['busqueda_estudio-academico']) && empty($_SESSION['busqueda_estudio-academico']) ){//se le pone buqueda_ y luego el nombre del modulo

			 ?>	
			<!-- Content here-->
			<div class="container-fluid">

				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/buscadorAjax.php" method="POST" data-form= "default" autocomplete="off">
					<input type="hidden" name="modulo" value="estudio-academico"><!-- input hidden para las busquedas -->

					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">
								<div class="form-group">
									<label for="inputSearch" class="bmd-label-floating">¿Qué estudioacademico estas buscando?</label>
									<input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30"><!--//busqueda_inicial -->
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
					<input type="hidden" name="modulo" value="estudio-academico"><!-- input hidden para las busquedas -->

					<input type="hidden" name="eliminar_busqueda" value="eliminar"><!--name="eliminar_busqueda" -->
					<div class="container-fluid">
						<div class="row justify-content-md-center">
							<div class="col-12 col-md-6">

								<p class="text-center" style="font-size: 20px;">
									Resultados de la búsqueda: <strong> <?php echo($_SESSION['busqueda_estudio-academico']); ?> </strong>
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
					require_once "./controladores/estudiosacademicosControlador.php";
					$ins_estudiosAcademicos = new estudiosacademicosControlador();
					echo $ins_estudiosAcademicos->paginador_estudio_academico_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],$_SESSION['busqueda_estudio-academico']);

				?>
			</div>

		</section>

<!--3 -->	<?php 
				}

			 ?>