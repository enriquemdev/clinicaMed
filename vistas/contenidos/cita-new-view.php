<?php
//require_once "../../config/APP.php" ;
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==1 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>



<!-- Page header -->
        <?php 
            require_once "./controladores/citaControlador.php";
            $ins_item = new citaControlador();
            $datos_item=$ins_item->datos_item1_controlador();
            if($datos_item->rowCount()==1){
                $campos=$datos_item->fetch();
            }
		?>
        
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA CITA
                </h3>
                <p class="text-justify">
                   Registra una nueva cita
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">
                    
                    <li>
                        <a class="active" href="<?php echo SERVERURL; ?>cita-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA CITA</a>
                    </li>
                    

                    <?php   
                    if($ver==true){ ?> 
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-list/"><i class="far fa-calendar-alt"></i> &nbsp; LISTA DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CITA</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>



                <div class="container-fluid form-neon">
                    <div class="container-fluid">
                        
                    <form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/citaAjax.php" method="POST" data-form= "save" autocomplete="off">
                    <fieldset>
                    <legend><i class="fas fa-user-lock"></i> &nbsp; Información de la Cita</legend>
                        <div class="container-fluid">
                            <div class="row">

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="cita_paciente_reg" class="bmd-label-floating">Nombre Paciente <span class="Obligar">*</span></label>
                                        <div class="autocompletar"><!-- pattern="[0-9]{1,35}" -->
                                            <input type="text" class="form-control" name="cita_paciente_reg" id="cita_paciente_reg" maxlength="70"required="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                                
                                        <label for="cita_doctor_reg" class="bmd-label-floating">Nombre Doctor <span class="Obligar">*</span></label>
                                        <div class="autocompletar"><!-- tiii pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,60}"-->
                                            <input type="text" class="form-control" name="cita_doctor_reg" id="cita_doctor_reg" maxlength="60"required="">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
												<div class="form-group">
                                                <label for="cita_consultorio" class="bmd-label-floating">Codigo del Consultorio <span class="Obligar">*</span></label>
													<select class="form-control" name="cita_consultorio_reg" id="cita_consultorio" maxlength="20"required="">
														<option value="" selected="" disabled=""></option>
														<?php foreach($datos_item as $campo){ ?>
														<option value="<?php echo$campo['ID'] ?>"><?php echo$campo['Nombre'] ?></option>
														<?php }?>
													</select>
												</div>
											</div>
                            </div>
                        </div>
                    <fieldset>


                        <fieldset>
                            <legend><i class="far fa-clock"></i> &nbsp; Fecha y Horas Programadas</legend>

                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="cita_fecha">Fecha Programada <span class="Obligar">*</span></label>
                                            <input type="date" class="form-control" name="cita_fecha_reg" id="cita_fecha"required="">
                                        </div>
                                    </div>

                                </div>

                                 <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="cita_hora_inicio">Hora Inicio <span class="Obligar">*</span></label>
                                            <input type="time" class="form-control" name="cita_hora_inicio_reg" id="cita_hora_inicio"required="">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="cita_hora_fin">Hora Fin <span class="Obligar">*</span></label>
                                            <input type="time" class="form-control" name="cita_hora_fin_reg" id="cita_hora_fin"required="">
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
                <div style="display:none;" id="serverTi"><?php echo SERVERURL;?></div> <!--Esta etiqueta tiene en su cocntenido la constante de servidor de config/app-->
                <script src="<?php echo SERVERURL; ?>buscadores/codDoctor_creaCita.js"></script><!--Para  buscador textbox-->
                <script src="<?php echo SERVERURL; ?>buscadores/paciente_creaCita.js"></script><!--Para  buscador textbox-->


