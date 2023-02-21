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


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>
<link rel="stylesheet" href="<?php echo SERVERURL; ?>vistas/css/buscadorForm.css">
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO
				</h3>
				<p class="text-justify">
					Rellene los campos para completar el nuevo registro.
				</p>
			</div>
			
			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
					</li>

          <?php   
          if($ver==true){ ?> 
					<li>
						<a href="<?php echo SERVERURL; ?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
					</li>

					<li>
						<a href="<?php echo SERVERURL; ?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
					</li>
          <?php } ?>
				</ul>	
			</div>
			
			<!-- Content -->
			<div class="container-fluid">
		
				<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/usuarioAjax.php" method="POST" data-form= "save" autocomplete="off" id="formElement"> <!-- Esto fue modificado -->
					<fieldset>
					<legend><i class="fas fa-user-lock"></i> &nbsp; Información de la cuenta</legend>
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_usuario" class="bmd-label-floating">Nombre de usuario</label>
										<input type="text" pattern="[a-zA-Z0-9]{1,35}" class="form-control" name="usuario_usuario_reg" id="usuario_usuario" maxlength="14" required="">
									</div>
								</div>

								

								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_empleado" class="bmd-label-floating">Nombre de colaborador</label>
										<div class="autocompletar"><!-- tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}" Modificar a que solo sean empleados-->
										<input type="text" class="form-control inputauto" name="usuario_empleado_reg" id="usuario_empleado_reg" maxlength="30"required="">
										</div>
									</div>
								</div>
		 			
						<legend style="margin-top: 40px;"><i class="fas fa-lock"></i> &nbsp;</legend>
					
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_clave_1" class="bmd-label-floating">Contraseña</label>
										<input type="password" class="form-control" name="usuario_clave_1_reg" id="usuario_clave_1" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="" >
									</div>
								</div>
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_clave_2" class="bmd-label-floating">Repetir contraseña</label>
										<input type="password" class="form-control" name="usuario_clave_2_reg" id="usuario_clave_2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="" >
									</div>
								</div>
								<legend style="margin-top: 40px;"> &nbsp;</legend>

								
								<div class="col-12 col-md-6">
									<div class="form-group">
										<label for="usuario_imagen" class="bmd-label-floating">Foto de referencia</label>
										<input type="file" class="form-control" name="usuario_imagen_reg" id="usuario_imagen" accept="image/*">
									</div>
								</div>

								<legend style="margin-top: 40px;"> </legend>
							<!--checkboxes-->

							<div class="col-12 col-md-6">
									<div class="form-group">
								<input type="checkbox" id="cbESTADO" name="cbESTADO_reg" value=1>
  								<label for="cbESTADO"> ESTADO ACTIVO</label><br>
								</div>
								</div>
								<!--checkboxes-->

							</div>
						</div>
					</fieldset>
					<fieldset>
						
					</fieldset>
					<br><br><br>
					<fieldset id="checkboxesmark">

						
						<legend class="row justify-content-center"><i class="fas fa-medal"></i> &nbsp; Privilegios de Usuario</legend>
						<br>
						
						<div class="container-fluid">

						<!--checkboxes-->
						<div class="row justify-content-center" id="fijos">

						<div class="col-4 col-sm-2 justify-content-left" >
  						<label class="titulo-tb fijo" >Submodulo</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-left">
  						<label class="titulo-tb fijo" >Ver</label><br>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-left">
  						<label class="titulo-tb fijo" >Agregar</label><br>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-left">
  						<label class="titulo-tb fijo" >Actualizar</label><br>
  						</div>

  						</div>

  						<br><div class="row justify-content-center"><label>Módulo Cita</label><br></div>

              <div class="row justify-content-center">

						<div class="col-4 col-sm-2 justify-content-center recep">
  						<label>Cita</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center doc">
						<input type="checkbox" id="cbCita1" name="cita1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" id="cbCita2" name="cita2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" id="cbCita3" name="cita3" value=1>
  						</div>

  						</div>

  <br><div class="row justify-content-center"><label>Módulo Usuarios</label><br></div>

     					<div class="row justify-content-center admin">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Usuarios</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" id="cbUsuario1" name="usuario1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" id="cbUsuario2" name="usuario2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" id="cbUsuario3" name="usuario3" value=1>
  						</div>

  						</div>

              <br><div class="row justify-content-center"><label>Módulo Empleados</label><br></div>

  						<div class="row justify-content-center admin">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Empleados</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="empleado1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="empleado2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="empleado3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center admin">

  						<div class="col-2 justify-content-center">
  						<label>Especialidades</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="especialidades1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="especialidades2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="especialidades3" value=1>
  						</div>

  						</div>

  						 <div class="row justify-content-center admin">

  						<div class="col-2 justify-content-center">
  						<label>Estudios Académicos Empleados</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="estudios1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="estudios2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="estudios3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center admin">

  						<div class="col-2 justify-content-center">
  						<label>Familiares Empleado</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="familiares1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="famililares2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="familiares3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center admin">

  						<div class="col-2 justify-content-center">
  						<label>Historial Cargos</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="historial1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="historial2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="historial3" value=1>
  						</div>

  						</div>


<br><div class="row justify-content-center"><label>Módulo Paciente</label><br></div>

  						<div class="row justify-content-center recep">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Paciente</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center doc">
						<input type="checkbox" name="paciente1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="paciente2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="paciente3" value=1>
  						</div>

  						</div>
<!--
						<div class="row justify-content-center">

  						<div class="col-2 justify-content-center">
  						<label>Antecedentes</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="antecedentes1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="anatecedentes2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="antecedentes3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center">

  						<div class="col-2 justify-content-center">
  						<label>Familiares Paciente</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="fampaciente1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="fampaciente2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="fampaciente3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center">

  						<div class="col-2 justify-content-center">
  						<label>Ocupacion Paciente</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="ocupacion1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="ocupacion2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="ocupacion3" value=1>
  						</div>

  						</div>
-->

<br><div class="row justify-content-center"><label>Módulo Consulta</label><br></div>

						<div class="row justify-content-center recep">

						<div class="col-4 col-sm-2 justify-content-center">
						<label>Solicitud Consulta</label><br>
						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="soli1" value=1>
						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox"  name="soli2" value=1>
						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="soli3" value=1>
						</div>

						</div>

  						<div class="row justify-content-center">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Consulta</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center recep doc">
						<input type="checkbox" name="consulta1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="consulta2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center recep doc">
  						<input type="checkbox" name="consulta3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center doc">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Signos Vitales</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="signos1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="signos2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="signos3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center doc">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Diagnóstico</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="diagnostico1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="diagnostico2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="diagnostico3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center doc">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Receta Médica</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="recmed1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="recmed2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="recmed3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center doc">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Receta Examen</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="recex1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="recex2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="recex3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center doc">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Constancia</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="constancia1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="constancia2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="constancia3" value=1>
  						</div>

  						</div>

              <br><div class="row justify-content-center"><label>Módulo Examen</label><br></div>

  						<div class="row justify-content-center">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Examen</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center doc">
						<input type="checkbox" name="exam1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="exam2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="exam3" value=1>
  						</div>

  						</div>

  						<div class="row justify-content-center">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Resultados</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center doc">
						<input type="checkbox" name="result1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="result2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="result3" value=1>
  						</div>

  						</div>
<!--
  						<div class="row justify-content-center">

  						<div class="col-2 justify-content-center">
  						<label>Maquinaria</label><br>
  						</div>

						<div class="col-1 justify-content-center">
						<input type="checkbox" name="maq1" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox"  name="maq2" value=1>
  						</div>

  						<div class="col-1 justify-content-center">
  						<input type="checkbox" name="maq3" value=1>
  						</div>

  						</div>
-->

<br><div class="row justify-content-center"><label>Módulo Catálogos</label><br></div>

  						<div class="row justify-content-center admin">

  						<div class="col-4 col-sm-2 justify-content-center">
  						<label>Catalogos</label><br>
  						</div>

						<div class="col-2 col-sm-1 justify-content-center">
						<input type="checkbox" name="cat1" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox"  name="cat2" value=1>
  						</div>

  						<div class="col-2 col-sm-1 justify-content-center">
  						<input type="checkbox" name="cat3" value=1>
  						</div>

  						</div>

						</div>
						</fieldset>

					

						<!--checkboxes-->
						<p class="text-center" style="margin-top: 40px; ">
						<p>
							<button type="button" class="btn btn-primary" id="marcarTodo">Marcar todo</button>	

							<button type="button" class="btn btn-primary" id="desmarcarTodo">Desmarcar todo</button>	
							<button type="button" class="btn btn-primary" id="recepcionista">Recepcionista</button>	
							<button type="button" class="btn btn-primary" id="doctor">Doctor</button>	
							<button type="button" class="btn btn-primary" id="enfermera">Enfermera</button>	
						</p>
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
					</p>
				</form>


				</div>

				<script src="<?php echo SERVERURL; ?>buscadores/nombreEmpleado_creaUsuario.js"></script><!--Para  buscador textbox-->

												




