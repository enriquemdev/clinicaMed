<?php 

//PARTE VER
        $auxiliar = new loginControlador();
        $listaVistas = $auxiliar -> navLateral_controlador();

    if( !(in_array("Cita", $listaVistas)) ){
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
                    <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CITA
                </h3>
                <p class="text-justify">
                    Actualiza los datos de la cita 
                </p>
            </div>

            <div class="container-fluid">
                <ul class="full-box list-unstyled page-nav-tabs">
                    <?php   
                    if($contadorFases==2){ ?>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA CITA</a>
                    </li>
                    <?php } ?>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-list/"><i class="far fa-calendar-alt"></i> &nbsp; LISTA DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-historial/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; HISTORIAL DE CITAS</a>
                    </li>
                    <li>
                        <a href="<?php echo SERVERURL; ?>cita-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR CITA</a>
                    </li>
                </ul>
            </div>

            <div class="container-fluid">
                <div class="container-fluid form-neon">
                    <div class="container-fluid">
                        <p class="text-center roboto-medium">AGREGAR DOCTOR Y PACIENTE</p>
                        <p class="text-center">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalCliente"><i class="fas fa-user-plus"></i> &nbsp; Agregar DOCTOR</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalItem"><i class="fas fa-box-open"></i> &nbsp; Agregar PACIENTE</button>
                        </p>
                        <div>
                            <span class="roboto-medium">paciente:</span> 
                            <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un paciente</span>
                            <form action="" style="display: inline-block !important;">
                                Codenaut
                                <button type="button" class="btn btn-danger"><i class="fas fa-user-times"></i></button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-dark table-sm">
                                <thead>
                                    <tr class="text-center roboto-medium">
                                        <th>PACIENTE</th>
                                        <th>CITA</th>
                                        <th>TIEMPO</th>
                                        <th>COSTO</th>
                                        <th>SUBTOTAL</th>
                                        <th>DETALLE</th>
                                        <th>ELIMINAR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-center" >
                                        <td>Marcos duartes</td>
                                        <td>1</td>
                                        <td>Hora</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>
                                            <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Nombre del paciente" data-content="Detalle completo del paciente">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <form action="">
                                                <button type="button" class="btn btn-warning">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="text-center" >
                                        <td>Luis Matus</td>
                                        <td>1</td>
                                        <td>Hora</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>
                                            <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Nombre del paciente" data-content="Detalle completo del paciente">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <form action="">
                                                <button type="button" class="btn btn-warning">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="text-center" >
                                        <td>Enrique jose</td>
                                        <td>1</td>
                                        <td>Hora</td>
                                        <td>NULL</td>
                                        <td>NULL</td>
                                        <td>
                                            <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Nombre del paciente" data-content="Detalle completo del paciente">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <form action="">
                                                <button type="button" class="btn btn-warning">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr class="text-center bg-light">
                                        <td><strong>TOTAL</strong></td>
                                        <td><strong>3 pacienntes</strong></td>
                                        <td colspan="2"></td>
                                        <td><strong>NULL</strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <form action="" autocomplete="off">
                        <fieldset>
                            <legend><i class="far fa-clock"></i> &nbsp; Fecha y hora de registro</legend>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="registro_fecha_inicio">Fecha de registro</label>
                                            <input type="date" class="form-control" name="registro_fecha_inicio_reg" id="registro_fecha_inicio">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="registro_hora_inicio">Hora de registro</label>
                                            <input type="time" class="form-control" name="registro_hora_inicio_reg" id="registro_hora_inicio">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><i class="fas fa-history"></i> &nbsp; Fecha y hora de cita</legend>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="p=cita_fecha_final">Fecha de cita</label>
                                            <input type="date" class="form-control" name="cita_fecha_final_reg" id="cita_fecha_final">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="cita_hora_final">Hora de cita</label>
                                            <input type="time" class="form-control" name="cita_hora_final_reg" id="cita_hora_final">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend><i class="fas fa-cubes"></i> &nbsp; Otros datos</legend>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="prestamo_estado" class="bmd-label-floating">Estado</label>
                                            <select class="form-control" name="prestamo_estado_reg" id="prestamo_estado">
                                                <option value="" selected="" disabled="">Seleccione una opción</option>
                                                <option value="Reservacion">activa</option>
                                                <option value="Prestamo">pendiente</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="cita_total" class="bmd-label-floating">Total a pagar en $</label>
                                            <input type="text" pattern="[0-9.]{1,10}" class="form-control" readonly="" value="45.00" id="cita_total" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="cita_pagado" class="bmd-label-floating">Total depositado en $</label>
                                            <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="cita_pagado_reg" id="cita_pagado" maxlength="10">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="cita_observacion" class="bmd-label-floating">Observación</label>
                                            <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ#() ]{1,400}" class="form-control" name="cita_observacion_reg" id="cita_observacion" maxlength="400">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <br><br><br>
                        <p class="text-center" style="margin-top: 40px;">
                            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
                            &nbsp; &nbsp;
                            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
                        </p>
                    </form>
                </div>
            </div>


            <!-- MODAL doctor-->
            <div class="modal fade" id="Modaldoctor" tabindex="-1" role="dialog" aria-labelledby="Modaldoctor" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="Modaldoctor">Agregar doctor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <label for="input_doctor" class="bmd-label-floating">inss, Nombre, Apellido, Telefono</label>
                                    <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_doctor" id="input_doctor" maxlength="30">
                                </div>
                            </div>
                            <br>
                            <div class="container-fluid" id="tabla_doctor">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-sm">
                                        <tbody>
                                            <tr class="text-center">
                                                <td>0000000000 - Nombre del doctor</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-user-plus"></i></button>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>0000000000 - Nombre del doctor</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-user-plus"></i></button>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>0000000000 - Nombre del doctor</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-user-plus"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    No hemos encontrado ningún doctor en el sistema que coincida con <strong>“Busqueda”</strong>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                            &nbsp; &nbsp;
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL PACIENTE -->
            <div class="modal fade" id="Modalpaciente" tabindex="-1" role="dialog" aria-labelledby="Modalpaciente" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="Modalpaciente">Agregar paciente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group">
                                    <label for="input_item" class="bmd-label-floating">Código, Nombre</label>
                                    <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_item" id="input_item" maxlength="30">
                                </div>
                            </div>
                            <br>
                            <div class="container-fluid" id="tabla_paciente">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered table-sm">
                                        <tbody>
                                            <tr class="text-center">
                                                <td>000000000000 - Nombre del paciente</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-box-open"></i></button>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>000000000000 - Nombre del paciente</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-box-open"></i></button>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>000000000000 - Nombre del paciente</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-box-open"></i></button>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <td>000000000000 - Nombre del paciente</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary"><i class="fas fa-box-open"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <p class="text-center mb-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                                    No hemos encontrado ningún paciente en el sistema que coincida con <strong>“Busqueda”</strong>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                            &nbsp; &nbsp;
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- MODAL AGREGAR paciente -->
            <div class="modal fade" id="ModalAgregarpaciente" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarpaciente" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form class="modal-content FormularioAjax">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalAgregarpaciente">Selecciona el formato, cantidad de pacientes</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_agregar_paciente" id="id_agregar_paciente">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="detalle_formato" class="bmd-label-floating">Formato de cita</label>
                                            <select class="form-control" name="detalle_formato" id="detalle_formato">
                                                <option value="Horas" selected="" >Horas</option>
                                                <option value="Dias">Días</option>
                                                <option value="Evento">Evento</option>
                                                <option value="Mes">Mes</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de pacientes</label>
                                            <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" >
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="detalle_tiempo" class="bmd-label-floating">Tiempo (según formato)</label>
                                            <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_tiempo" id="detalle_tiempo" maxlength="7" required="" >
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="detalle_costo_tiempo" class="bmd-label-floating">Costo por unidad de tiempo</label>
                                            <input type="text" pattern="[0-9.]{1,15}" class="form-control" name="detalle_costo_tiempo" id="detalle_costo_tiempo" maxlength="15" required="" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" >Agregar</button>
                            &nbsp; &nbsp;
                            <button type="button" class="btn btn-secondary" >Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>