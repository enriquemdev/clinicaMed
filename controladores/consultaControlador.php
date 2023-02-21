<?php

if ($peticionAjax) {
    require_once "../modelos/consultaModelo.php";
} else {
    require_once "./modelos/consultaModelo.php";
}
class consultaControlador extends consultaModelo
{
    public function datos_item1_controlador()
    {
        return consultaModelo::datos_item1_modelo();
    }/*Fin de controlador */
    public function datos_item2_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return consultaModelo::datos_consulta_modelo($id);
    }
    /*-----------------Controlador para agregar consulta-----------------*/
    public function agregar_consulta_controlador()
    {
        $NombrePaciente = mainModel::limpiar_cadena($_POST['consulta_paciente_reg']);
        //$CodMedico=mainModel::limpiar_cadena($_POST['consulta_medico_reg']);//AHora lo toma automaticamente
        $CodConsultorio = mainModel::limpiar_cadena($_POST['consulta_consultorio_reg']);
        $CodCita = mainModel::limpiar_cadena($_POST['consulta_cita_reg']);
        $CodSignosVitales = mainModel::limpiar_cadena($_POST['signos_vitales_reg']);

        /* -------------------------------------------Codigo Medico automático-------------------------------------------*/
        /*21/03/2022*/
        session_start(['name' => 'SPM']);
        $CodMedico = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $CodMedico = $CodMedico->fetch();
        $CodMedico = $CodMedico['Codigo'];

        /*----------------Comprobar campos vacíos -----------------*/

        if ($NombrePaciente == "" || $CodMedico == "" || $CodConsultorio == "" || $CodSignosVitales == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $Estado = 1;

        //buscador codigo Paciente textBox

        $parametrosPaciente = explode('-', $NombrePaciente); //Nombre_Apellido_codPaciente


        $datos_Paciente = [
            "NombrePaciente" => $parametrosPaciente[0],
            "ApellidoPaciente" => $parametrosPaciente[1],
            "CodigoPaciente" => $parametrosPaciente[2]
        ];

        $CodigoPaciente = consultaModelo::buscarCodPaciente($datos_Paciente);
        $row2 = $CodigoPaciente->fetch();/*ti*/
        $CodigoPaciente = $row2['CodigoP'];

        $check_medico = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblsignosvitales WHERE Codigo='$CodSignosVitales'");
        if ($check_medico->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de los signos no está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*DATOS POR ENVIAR */
        $datos_consulta_reg = [
            "CodMedico" => $CodMedico,
            "IdCita" => $CodCita,
            "CodPaciente" => $CodigoPaciente,
            "CodSignosVitales" => $CodSignosVitales,
            "CodConsultorio" => $CodConsultorio,
            "Estado" => $Estado
        ];


        $agregar_consulta = consultaModelo::agregar_consulta_modelo($datos_consulta_reg);


        if ($agregar_consulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consulta registrada",
                "Texto" => "Consulta registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el usuario",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }
    public function serviciocerote()
    {
        $datos_servicio_reg = [
            "Tipo" => 2, //Servicio de consulta
            "Estado" => 1 //Estado de servicio activo
        ];
        $agregar_servicio = consultaModelo::agregar_servicio_modelo($datos_servicio_reg);
    } //Terminacontrolador
    public function agregarnotasconsulta()
    {
        $codigo = mainModel::decryption($_POST['consulta_codigo']);
        $codigo = mainModel::limpiar_cadena($codigo);
        $notas = mainModel::limpiar_cadena($_POST['notas_consulta_reg']);
        /*DATOS POR ENVIAR */
        $time = date("Y-m-d h:i:s");
        $datos_consulta = [
            "Codigo" => $codigo,
            "Notas" => $notas

        ];
        /*-----------------Comprobando si no ha cambiado el estado-----------------*/


        /*Posible opción */
        $notas_consulta = consultaModelo::agregarnotas($datos_consulta);

        if ($notas_consulta->rowCount() == 1) {

            $alerta = [
                "Alerta" => "redireccionar",
                "URL" => "../consulta-list/",
                "Tipo" => "consulta"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró actualizar la consulta",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina actualizar estado aceptada
    public function agregaranulacionconsulta()
    {
        $codigo = mainModel::decryption($_POST['consulta_codigo']);
        $codigo = mainModel::limpiar_cadena($codigo);
        $notas = mainModel::limpiar_cadena($_POST['notas_anulada_reg']);
        /*DATOS POR ENVIAR */
        $datos_consulta = [
            "Codigo" => $codigo,
            "Notas" => $notas

        ];
        /*-----------------Comprobando si no ha cambiado el estado-----------------*/


        /*Posible opción */
        $notas_consulta = consultaModelo::agregaranular($datos_consulta);

        if ($notas_consulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consulta registrada",
                "Texto" => "Consulta actualizada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró actualizar la consulta",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina actualizar estado aceptada

    public function cambiarestado()
    {
        $id = mainModel::limpiar_cadena($_POST['id']);
        /*DATOS POR ENVIAR */
        $datos_consulta = [
            "ID" => $id

        ];
        /*-----------------Comprobando si no ha cambiado el estado-----------------*/
        session_start(['name' => 'SPM']);
        $CodigoDoc = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $CodigoDoc = $CodigoDoc->fetch();
        $CodigoDoc = $CodigoDoc['Codigo'];

        $check_estado = mainModel::ejecutar_consulta_simple("SELECT Estado FROM tblconsulta WHERE Codigo='$id' && (Estado=3 or Estado=2 )&& CodMedico= '$CodigoDoc' ");
        if ($check_estado->rowCount() > 0) {

            /*Posible opción */
            $actu_consulta = consultaModelo::actues($datos_consulta);
        } else {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Los datos de la consulta fueron cambiados",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }




        if ($actu_consulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consulta registrada",
                "Texto" => "Consulta actualizada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró actualizar la consulta",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina actualizar estado aceptada

    public function cambiarEstadoRechazado() //anula la consulta
    {
        $id = mainModel::limpiar_cadena($_POST['idRechazar']);
        /*DATOS POR ENVIAR */
        $datos_consulta = [
            "ID" => $id

        ];


        $actu_consulta = consultaModelo::actualizarEstadoRechazada($datos_consulta);


        if ($actu_consulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consulta registrada",
                "Texto" => "Consulta actualizada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró actualizar la consulta",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina actualizar estado Rechazada


    public function datos_solicitud_consulta_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return consultaModelo::datos_solicitud_consulta_modelo($id);
    }/*Fin de controlador */

    /*ESTE ES PARA LAS CONSULTAS PENDIENTES*/
    public function paginador_consulta_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);
        $id = mainModel::limpiar_cadena($id);
        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        //session_start(['name'=>'SPM']);
        if ($_SESSION['cargo_spm'] == 2) //Si es doctor  
        { //inicia condicional 1
            $consulta = " SELECT *, a.Codigo as Codigo_consulta,a.Estado as estadocons, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
            ApellidoPaciente, c.Apellidos as ApellidoDoc,e.Telefono as TelefonoPac, e.Email as EmailPac 
            from tblconsulta as a
            inner join tblempleado as b ON (a.CodMedico=b.Codigo)
            inner join tblpersona as c on (b.CodPersona=c.Codigo)
            inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
            inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                WHERE a.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "') AND ( a.Estado=2 OR a.Estado=3 )
                ORDER BY a.Estado DESC,a.Codigo ASC LIMIT $inicio,$registros";


            /*Se establece la conexión con la bd */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / 15);
            $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>PACIENTE</th>
                        <th>MOTIVO</th>
                        <th>N. CITA</th>
                        <th>CONTACTO</th>
                        <th>HORA</th>
                        <th>S. VITALES</th>
                        <th>COMENZAR C.</th>
                        <th>REVERTIR C.</th>
                        <th>ESTADO</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $horaconsulta = explode(' ', $rows['FechaYHora']);
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['Codigo_consulta'] . '</td>
                                    <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>
                                    <th>' . $rows['MotivoConsulta'] . '</th>
                                    ';
                    if ($rows['IdCita'] == 0) {
                        $tabla .= '
                                        <th>Sin cita</th>
                                        ';
                    } else {
                        $tabla .= '
                                        <th>' . $rows['IdCita'] . '</th>
                                        ';
                    }
                    if ($rows['TelefonoPac'] != null) {
                        $tabla .= '<th>' . $rows['TelefonoPac'] . '</th>';
                    } else {
                        $tabla .= '<th>' . $rows['EmailPac'] . '</th>';
                    }

                    $tabla .= '<th>' . $horaconsulta[1] . '</th>';

                    $check = mainModel::ejecutar_consulta_simple("SELECT * FROM tblconsulta  WHERE CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "') AND Estado =3 ");
                    if ($rows['estadocons'] == '3') { //Cuando es consulta de prioridad
                        $consultaCod = $rows['Codigo_consulta'];
                        $check_signos = mainModel::ejecutar_consulta_simple("SELECT * FROM tblsignosvitales  WHERE CodConsulta = '$consultaCod';"); //Se consulta si hay signos

                        if ($check_signos->rowCount() == 0) { //Se valida que si no hay signos se toman, pero si hay solo se muestran
                            $tabla .= '
                                            <th>
                                            <a href="' . SERVERURL . 'signos-vitales'/*Tomar signos vitales
                                            */ . '"data-toggle="tooltip" title="Tomar signos vitales!"">
                                            <i class="fas fa-file-medical" style="font-size:30px; color: red"></i>
                                            </a>
                                            </th>
                                            ';
                        } else { //Si existen signos se muestran con reporte
                            $IDSignos = $check_signos->fetch();
                            $CodeSignos = $IDSignos['Codigo'];
                            $tabla .= '
                                            <th>
                                        <a href="' . SERVERURL . 'Reportes/reporte-u-signos-vitales.php?id=' . $CodeSignos/*Generar reporte de los signos vitales FALTA!!!!!
                                        */ . '"data-toggle="tooltip" title="Ver signos vitales!"" target="_blank">
                                        <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                        </a>
                                        </th>
                                            ';
                        }
                        $tabla .= '
                                        
                                        <th>
                                        <a href="' . SERVERURL . 'consultas-proceso/' . mainModel::encryption($rows['Codigo_consulta'])/*Generar notas de consulta
                                        */ . '"data-toggle="tooltip" title="Comenzar consulta!"">
                                        <i class=" fas fa-check-circle" style="font-size:30px ; color: green;"></i>
                                        </a></th>';
                    } else if ($rows['estadocons'] != '3') { //Cuando es consulta de no prioridad
                        $consultaCod = $rows['Codigo_consulta'];
                        $check_signos = mainModel::ejecutar_consulta_simple("SELECT * FROM tblsignosvitales  WHERE CodConsulta = '$consultaCod';"); //Se consulta si hay signos
                        $IDSignos = $check_signos->fetch();
                        $CodeSignos = $IDSignos['Codigo'];
                        $tabla .= '
                                        
                                        ';
                        if ($check->rowCount() == 0) {

                            $tabla .= '
                                        
                                        <th>
                                        <a href="' . SERVERURL . 'Reportes/reporte-u-signos-vitales.php?id=' . $CodeSignos /*Generar reporte de los signos vitales FALTA!!!!!
                                        */ . '"data-toggle="tooltip" title="Ver signos vitales!"" target="_blank">
                                        <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                        </a>
                                        </th>
                                        <th>
                                        <a href="' . SERVERURL . 'consultas-proceso/' . mainModel::encryption($rows['Codigo_consulta'])/*Generar notas de consulta
                                        */ . '"data-toggle="tooltip" title="Comenzar consulta!"">
                                        <i class=" fas fa-check-circle" style="font-size:30px; color: green;"></i>
                                        </a></th>';
                        } else {
                            $tabla .= '<th></th>
                                            <th></th>

                                            ';
                        }
                    } //En caso de tener consultas con prioridad no aparecerá el botón
                    $tabla .= '
                                    <td><a href="' . SERVERURL . 'consultas-anulada/' . mainModel::encryption($rows['Codigo_consulta'])/*Anular consulta
                                    */ . '"data-toggle="tooltip" title="Revertir consulta!"">
                                    <i class="fas fa-times-circle" style="font-size:30px; color: red;"></i>
                                    </a></th>
                                    
                        ';

                    if ($rows['estadocons'] == '3') {
                        $tabla .= '
                                <td><span class="badge badge-danger">Prioridad</span></td>
                                ';
                        $contador++;
                    } else {
                        $tabla .= '
                                <td><span class="badge badge-primary">En espera</span></td>
                                ';
                        $contador++;
                    }
                }
            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center"><td colspan="9">
                        <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                        
                        </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
            }

            $tabla .= '</tbody></table></div>';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
        } //Termina condicional 1
        else { //inicia condicional 2 //AQUI ERA PARA CUANDO ERA RECEPCIONISTA (YA NO) AHORA ES PARA CUALQUIERA QUE NO SEA DOCTOR
            $consulta = " SELECT *, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                ApellidoPaciente, c.Apellidos as ApellidoDoC,e.Telefono as TelefonoPac, e.Email as EmailPac 
                from tblconsulta as a
                inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                inner join tblpersona as c on (b.CodPersona=c.Codigo)
                inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                WHERE  (a.Estado=5)
                ORDER BY a.Codigo DESC LIMIT $inicio,$registros";

            /*Se establece la conexión con la bd */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / 15);
            $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>NOMBRE MEDICO</th>
                        <th>CÓDIGO CITA</th>
                        <th>PACIENTE</th>
                        <th>CONTACTO</th>
                        <th>FECHA Y HORA</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    /* Aquí se retiró codigo de signos vitales <th>'.$rows['CodSignosVitales'].'</th> */
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['Codigo_consulta'] . '</td>
                                    <th>' . $rows['NombresDoctor'] . ' ' . $rows['ApellidoDoc'] . '</th>
                                    <th>' . $rows['IdCita'] . '</th>
                                    <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>';
                    if ($rows['TelefonoPac'] != null) {
                        $tabla .= '<th>' . $rows['TelefonoPac'] . '</th>';
                    } else {
                        $tabla .= '<th>' . $rows['EmailPac'] . '</th>';
                    }
                    $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                        ';
                    $contador++;
                }
            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center"><td colspan="9">
                        <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                        
                        </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
            }



            $tabla .= '</tbody></table></div>';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
        } //Termina condicional 2
        return $tabla;
    } //Termina controlador
    /*ESTE ES PARA CONSULTAS TERMINADAS */ //COmbobox xes la de la fecha
    public function paginador_consulta_realizada_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $combobox)
    {
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);
        $id = mainModel::limpiar_cadena($id);
        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        //Identificador: 333
        $EsConsulta = false;
        //Termino identificador: 333

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        //session_start(['name'=>'SPM']);
        if ($_SESSION['cargo_spm'] == 2) //Si es doctor
        { //inicia condicional 1
            if (isset($busqueda) && $busqueda != "" || $combobox != "") {
                $EsConsulta = true;

                $consulta = " SELECT *, a.Estado as estadocons, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc, e.Telefono as TelefonoPac, e.Email as EmailPac 
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    
                    WHERE (a.Estado=5 OR a.Estado=6)
                    AND (a.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'))
                    AND ((CONCAT(e.Nombres,' ',e.Apellidos) LIKE '%$busqueda%')
                    OR (a.Codigo LIKE '$busqueda') 
                    OR (d.INSS LIKE '$busqueda') 
                    OR (e.Cedula LIKE '$busqueda') 
                    OR (e.Telefono LIKE '$busqueda') 
                    OR (e.Email LIKE '$busqueda') 
                    OR (e.Nombres LIKE '%$busqueda%')
                    OR (e.Apellidos LIKE '%$busqueda%')) ";

                if ($_SESSION['combobox'] != "") {
                    //(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1))
                    $consulta = $consulta . "AND ((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1)) = '" . $_SESSION['combobox'] . "') ";
                }

                $consulta = $consulta . "ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            } else {
                $EsConsulta = false;
                $consulta = " SELECT *, a.Codigo as Codigo_consulta,a.Estado as estadocons, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc, e.Telefono as TelefonoPac, e.Email as EmailPac  
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE a.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "') AND (a.Estado=5)
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            }



            /*Se establece la conexión con la bd */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / 15);
            $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>PACIENTE</th>
                        <th>COD. CITA</th>
                        <th>NOTAS</th>
                        <th>DIAGNOSTICO</th>
                        <th>CONTACTO</th>
                        <th>FECHA Y HORA</th>
                        <th>ESTADO</th>
                        <th>REPORTE</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['Codigo_consulta'] . '</td>
                                    <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>
                                    ';
                    if ($rows['IdCita'] == 0) {
                        $tabla .= '
                                        <th>Sin cita</th>
                                        ';
                    } else {
                        $tabla .= '
                                        <th>' . $rows['IdCita'] . '</th>
                                        ';
                    }
                    $tabla .= '
                                    <th>' . $rows['NotasConsulta'] . '</th>
                                    <th>
                                    <a href="' . SERVERURL . 'diagnostico-auto/' . mainModel::encryption($rows['Codigo_consulta']) //Generar diagnostico de consulta
                        . '"data-toggle="tooltip" title="Crear Diagnostico!"">
                                    <i class="fas fa-book-medical" style="font-size:30px"></i>
                                    </a></th>';
                    if ($rows['TelefonoPac'] != null) {
                        $tabla .= '<th>' . $rows['TelefonoPac'] . '</th>';
                    } else {
                        $tabla .= '<th>' . $rows['EmailPac'] . '</th>';
                    }
                    $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                        ';
                    if ($rows['estadocons'] == 5) {/*AQUI ME DIO UN ERROR (ENRIQUE)*/
                        $tabla .= '
                            <td><span class="badge badge-secondary">Terminada</span></td>
                            ';
                        $contador++;
                    } else {
                        $tabla .= '
                            <td><span class="badge badge-primary">En proceso</span></td>
                            ';
                        $contador++;
                    }
                    $tabla .= '<td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-consulta.php?idConsulta='
                        . $rows['Codigo_consulta'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>';
                }
            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center"><td colspan="9">
                        <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                        
                        </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
            }

            $tabla .= '</tbody></table></div>';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
        } //Termina condicional 1
        else if ($_SESSION['cargo_spm'] == 3 || $_SESSION['cargo_spm'] == 4) { //En caso sea recepcionista
            if (isset($busqueda) && $busqueda != "" || $combobox != "") {
                $EsConsulta = true;
                //inicia condicional 2
                $consulta = " SELECT *, a.Estado as estadocons, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc, e.Telefono as TelefonoPac, e.Email as EmailPac  
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo)
                    WHERE (a.Estado=5 /* OR a.Estado=2 */) 
                    AND ((CONCAT(e.Nombres,' ',e.Apellidos) LIKE '%$busqueda%')
                    OR (a.Codigo LIKE '$busqueda') 
                    OR (d.INSS LIKE '$busqueda') 
                    OR (e.Cedula LIKE '$busqueda') 
                    OR (e.Telefono LIKE '$busqueda') 
                    OR (e.Email LIKE '$busqueda') 
                    OR (e.Nombres LIKE '%$busqueda%') 
                    OR (e.Apellidos LIKE '%$busqueda%')) ";

                if ($_SESSION['combobox'] != "") {
                    //(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1))
                    $consulta = $consulta . "AND ((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1)) = '" . $_SESSION['combobox'] . "') ";
                }

                $consulta = $consulta . "ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            } else {
                $EsConsulta = false;
                //inicia condicional 2
                $consulta = " SELECT *, a.Codigo as Codigo_consulta, a.Estado as estadocons, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc, e.Telefono as TelefonoPac, e.Email as EmailPac  
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo)
                    WHERE (a.Estado=5 /* OR a.Estado=2 */)
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            }

            /*Se establece la conexión con la bd */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / 15);
            $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>NOMBRE MEDICO</th>
                        <th>CÓDIGO CITA</th>
                        <th>PACIENTE</th>
                        <th>CONTACTO</th>
                        <th>FECHA Y HORA</th>
                        <th>ESTADO</th>
                        <th>REPORTE</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['Codigo_consulta'] . '</td>
                                    <th>' . $rows['NombresDoctor'] . ' ' . $rows['ApellidoDoc'] . '</th>
                                    ';
                    if ($rows['IdCita'] == 0) {
                        $tabla .= '
                                        <th>Sin cita</th>
                                        ';
                    } else {
                        $tabla .= '
                                        <th>' . $rows['IdCita'] . '</th>
                                        ';
                    }
                    $tabla .= '
                                    <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>';
                    if ($rows['TelefonoPac'] != null) {
                        $tabla .= '<th>' . $rows['TelefonoPac'] . '</th>';
                    } else {
                        $tabla .= '<th>' . $rows['EmailPac'] . '</th>';
                    }
                    $tabla .= '<th>' . $rows['FechaYHora'] . '</th>';
                    if ($rows['estadocons'] == 5) {
                        $tabla .= '
                            <td><span class="badge badge-secondary">Terminada</span></td>
                            ';
                        $contador++;
                    } else {
                        $tabla .= '
                            <td><span class="badge badge-primary">En proceso</span></td>
                            ';
                        $contador++;
                    }
                    $tabla .= '<td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-consulta.php?idConsulta='
                        . $rows['Codigo_consulta'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>';
                } //FALTA AGREGAR EL DE RECHAZO Y HAY QUE VER COMO HACER QUE HAGAN LA ACCION DE EJECUTAR SQL



            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center"><td colspan="9">
                        <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                        
                        </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
            }

            $tabla .= '</tbody></table></div>';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
        } //Termina condicional 2
        else if ($_SESSION['cargo_spm'] == 1) { //En caso sea enfermera
            if (isset($busqueda) && $busqueda != "" || $combobox != "") {
                $EsConsulta = true;
                //inicia condicional 3
                $consulta = " SELECT *, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc 
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE  (a.Estado=2)
                    AND ((CONCAT(e.Nombres,' ',e.Apellidos) LIKE '%$busqueda%')
                    OR (a.Codigo LIKE '$busqueda') 
                    OR (d.INSS LIKE '$busqueda') 
                    OR (e.Cedula LIKE '$busqueda') 
                    OR (e.Telefono LIKE '$busqueda') 
                    OR (e.Email LIKE '$busqueda') 
                    OR (e.Nombres LIKE '%$busqueda%') 
                    OR (e.Apellidos LIKE '%$busqueda%')) ";

                if ($_SESSION['combobox'] != "") {
                    //(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1))
                    $consulta = $consulta . "AND ((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(a.FhInicio, ' ', 1), ' ', -1)) = '" . $_SESSION['combobox'] . "') ";
                }

                $consulta = $consulta . "ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            } else {
                $EsConsulta = false;
                //inicia condicional 3
                $consulta = " SELECT *, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                    ApellidoPaciente, c.Apellidos as ApellidoDoc 
                    from tblconsulta as a
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE  (a.Estado=2)
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            }

            /*Se establece la conexión con la bd */
            $conexion = mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            $Npaginas = ceil($total / 15);
            $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>NOMBRE MEDICO</th>
                        <th>CÓDIGO CITA</th>
                        <th>PACIENTE</th>
                        <th>TELEFONO</th>
                        <th>FECHA Y HORA</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['Codigo_consulta'] . '</td>
                                    <th>' . $rows['NombresDoctor'] . ' ' . $rows['ApellidoDoc'] . '</th>
                                    ';
                    if ($rows['IdCita'] == 0) {
                        $tabla .= '
                                        <th>Sin cita</th>
                                        ';
                    } else {
                        $tabla .= '
                                        <th>' . $rows['IdCita'] . '</th>
                                        ';
                    }
                    $tabla .= '
                                    <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>
                                    <th>' . $rows['Telefono'] . '</th>
                                    <th>' . $rows['FechaYHora'] . '</th>
                                    ' //Aquí se cambia a las opciones de consulta
                        . '
                        ';
                    $contador++;
                } //FALTA AGREGAR  BOTÓN DE SIGNOS VITALES


            } else {
                if ($total >= 1) {
                    $tabla .= '<tr class="text-center"><td colspan="9">
                        <a href="' . $url . '" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                        
                        </td></tr>';
                } else {
                    $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
            }

            $tabla .= '</tbody></table></div>';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
            }
        } //Termina condicional 3
        return $tabla;
    } //Termina controlador

    public function reporte_consulta_controlador($id)
    {
        $id = mainModel::limpiar_cadena($id);
        return consultaModelo::reporte_consulta_modelo($id);
    }

    public function paginador_consulta_signos_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
    }
}
