<?php 

//PARTE VER
        $auxiliar = new loginControlador();
        $listaVistas = $auxiliar -> navLateral_controlador();

    if( !(in_array("Paciente", $listaVistas)) ){
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }

 //PARTE ESCRIBIR
        $auxiliarPermisos = new loginControlador();
        $permisos = $auxiliarPermisos -> permisos_controlador();/*Aqui se recibe el array de permisos*/
        $conta= count($permisos);
        $contadorFases=0;

        for ($i=0; $i < $conta; $i++) { 
            if ($contadorFases==2) {
                break;
            }

    $contadorFases=0;
    for ($j=0; $j < 2; $j++) { 
        

        if($j==0){
            if($permisos[$i][$j]==2){//2 recepcion
                $contadorFases++;
                    }//cierra if permisos         
                }//cierra if j 0

        if($j==1){
                if($permisos[$i][$j]==1 && $contadorFases==1){
                $contadorFases++;
                    }//cierra if permisos
                }//cierra if j 1 

                            }//termina for pequeño
}//cierra for grande

?>

<!-- Page header -->
<div class="full-box page-header">
                <h3 class="text-left">
                    <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PACIENTE
                </h3>
                <p class="text-justify">
                   Se actualizara la informacion del paciente seleccionado
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">
                    <?php   //si tiene las dos fases puede pasar
                    if($contadorFases==2){ ?> 
                    <li>
                        <a href="<?php echo SERVERURL; ?>paciente-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PACIENTE</a>
                    </li>
                    <?php } ?>
                    
                    <li>
                        <a href="<?php echo SERVERURL; ?>paciente-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PACIENTE</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>paciente-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PACIENTE</a>
                    </li>
                </ul>
            </div>
            
            <!--CONTENT-->
            <div class="container-fluid">
                <form action="" class="form-neon" autocomplete="off">
                    <fieldset>
                        <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_nombre" class="bmd-label-floating">NOMBRES <span class="Obligar">*</span></label>
                                        <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="paciente_nombre_reg" id="paciente_nombre" maxlength="40">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_apellido" class="bmd-label-floating">APELLIDOS <span class="Obligar">*</span></label>
                                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="paciente_apellido_reg" id="paciente_apellido" maxlength="14">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_inss" class="bmd-label-floating">INSS <span class="Obligar">*</span></label>
                                        <input type="text" pattern="[0-9-]{1,27}" class="form-control" name="paciente_inss_reg" id="paciente_inss" maxlength="9">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_cedula" class="bmd-label-floating">CEDULA <span class="Obligar">*</span></label>
                                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="paciente_cedula_reg" id="paciente_cedula" maxlength="14">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="item_genero" class="bmd-label-floating">GENERO <span class="Obligar">*</span></label>
                                        <select class="form-control" name="item_genero_reg" id="item_genero">
                                            <option value="" selected="" disabled=""></option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_nacio" class="bmd-label-static" >FECHA DE NACIMIENTO <span class="Obligar">*</span> </label>
                                        <input type="date" class="form-control" name="paciente_nacio_reg" id="paciente_nacio">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_estado civil" class="bmd-label-floating">ESTADO CIVIL <span class="Obligar">*</span></label>
                                        <select class="form-control" name="item_genero_reg" id="item_genero">
                                            <option value="" selected="" disabled=""></option>
                                            <option value="casado">Casad@</option>
                                            <option value="soltero">Solter@</option>
                                            <option value="otro">Otro</option>
                                        </select>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_telefono" class="bmd-label-floating">DIRECCION</label>
                                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="paciente_direccion_reg" id="paciente_direccion" maxlength="150">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_telefono" class="bmd-label-floating">TELEFONO</label>
                                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="paciente_telefono_reg" id="paciente_telefono" maxlength="15">
                                    </div>
                                </div>
                            
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="paciente_correo" class="bmd-label-floating">CORREO ELECTRÓNICO</label>
                                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="paciente_correo_reg" id="paciente_correo" maxlength="70">
                                    </div>
                                </div>
                            
                                
                                
                            
                            </div>
                        </div>
                    </fieldset>
                    <br><br><br>
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
                        &nbsp; &nbsp;
                        <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; ACTUALIZAR</button>
                    </p>
                </form>
            </div>
