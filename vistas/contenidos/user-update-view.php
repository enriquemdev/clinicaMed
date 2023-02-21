<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==2 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR USUARIO
				</h3>
				<p class="text-justify">
					Actualización de usuario.
				</p>
			</div>
			
			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
					</li>

					<!-- <li>
						<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
					</li> -->
          <?php  ?>
				</ul>	
			</div>
			
			<!-- Content -->
			<div class="container-fluid">

			<?php 
				require_once "./controladores/usuarioControlador.php";
				$ins_usuario = new usuarioControlador();
				$datos_usuario=$ins_usuario->datos_usuario_controlador("Unico",$pagina[1]);

				if($datos_usuario->rowCount()==1){
					$campos=$datos_usuario->fetch();
					
					?>
					
					
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/usuarioAjax.php" method="POST" data-form= "update" autocomplete="off"> <!-- Esto fue modificado -->
				<input type="hidden" name="usuario_codigo_up" value="<?php echo $pagina[1]; ?>">
					<fieldset>
					<legend><i class="fas fa-user-lock"></i> &nbsp; Información de la cuenta</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_usuario" class="bmd-label-floating">Nombre de usuario</label>
										<input type="text" maxlength="14" pattern="[a-zA-Z0-9]{1,35}" class="form-control" name="usuario_usuario_up" id="usuario_usuario" maxlength="35" required="" value="<?php echo $campos['NombreUsuario'] ?>">
									</div>
								</div>
								&nbsp;
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_imagen_editar" class="bmd-label-floating">Actualizar foto de referencia</label>
										<input type="file" class="form-control" name="usuario_imagen_editar_reg" id="usuario_imagen_editar" accept="image/*">
									</div>
								</div>

								</fieldset>
								<fieldset>
						<legend style="margin-top: 40px;"><i class="fas fa-lock"></i> &nbsp; Nueva contraseña</legend>
						<p>Para actualizar la contraseña de esta cuenta ingrese una nueva y vuelva a escribirla. En caso que no desee actualizarla debe dejar vacíos los dos campos de las contraseñas.</p>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_clave_1" class="bmd-label-floating">Nueva Contraseña</label>
										<input type="password" class="form-control" name="usuario_clave_1_reg" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100"  >
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_clave_2" class="bmd-label-floating">Repetir Nueva contraseña</label>
										<input type="password" class="form-control" name="usuario_clave_2_reg" id="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100"  >
									</div>
								</div>
							<!--checkboxes-->
						A partir de aqui abajo se tiene que verificar si el usuario tiene privilegios para editar esto, sino, no se muestra
							<div class="col-12 col-md-6">
									<div class="form-group">
								<input type="checkbox" id="cbESTADO" name="cbESTADO_reg" value=1>
  								<label for="cbESTADO"> ESTADO ACTIVO</label><br>
								</div>
								</div>
						</fieldset>

					

		
						<p class="text-center" style="margin-top: 40px;">

						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
					</p>
				</form>


			</div>

					</fieldset>
					<?php if($lc->encryption($_SESSION['id_spm'])!=$pagina[1]){ ?>
						<input type="hidden" name="tipo_cuenta" value="Impropia">
					<?php }else{?>
						<input type="hidden" name="tipo_cuenta" value="Propia">
					<?php } ?>

			<?php 	} else { ?>
				<div class="alert alert-danger text-center" role="alert">
				<p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
				<h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
				<p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
			</div>
			<?php }		?>

												



