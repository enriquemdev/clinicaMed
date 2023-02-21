<?php

if ($peticionAjax) {
    require_once "../modelos/solicitudConsultaModelo.php";
} else {
    require_once "./modelos/solicitudConsultaModelo.php";
}
class solicitudConsultaControlador extends solicitudConsultaModelo
{
    public function datos_item1_controlador()
    {
        return solicitudConsultaModelo::datos_item1_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar consulta-----------------*/
    public function agregar_solconsulta_controlador()
    { //Agregar asignación de consulta
        $NombrePaciente = mainModel::limpiar_cadena($_POST['consulta_paciente_reg']);
        $CodMedico = mainModel::limpiar_cadena($_POST['cita_doctor_reg']);
        $CodConsultorio = mainModel::limpiar_cadena($_POST['consulta_consultorio_reg']);

        $CodCita = mainModel::limpiar_cadena($_POST['consulta_cita_reg']);
        $MotivoConsulta = mainModel::limpiar_cadena($_POST['motivo_consulta_reg']);
        if (isset($_POST['estado_reg'])) {
            $Estado = 3; //se asigna el estado de estado de prioridad para pasar de un solo al doctor
        } else {
            $Estado = 1; //Sino se le asigna el estado de asignada para que se le muestre a la enfermera
        }
        $cita = false;
        if ($CodCita != "") {
            $cita = true; //se viene cita definida

        }

        /* -------------------------------------------Codigo Medico automático-------------------------------------------*/
        /*21/03/2022 Ahora 24/03/2022 trabajando de balde*/
        session_start(['name' => 'SPM']);
        $registradoPor = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $registradoPor = $registradoPor->fetch();
        $registradoPor = $registradoPor['Codigo']; //Registra el empleado (recepcionista) que hizo el registro

        /*----------------Comprobar campos vacíos -----------------*/
        if ($NombrePaciente == "" || $CodMedico == "" || $CodConsultorio == "" || $Estado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /* VALIDACIONES  04/05/2022*/
        if ($cita == true) {
            $check_cita = mainModel::ejecutar_consulta_simple("SELECT IDCita FROM tblcita WHERE IDCita='$CodCita'");
            if ($check_cita->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cita no se encuentra registrada",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /* El violento */
            $checkCita = mainModel::ejecutar_consulta_simple("SELECT a.IDCita,b.CodigoP FROM tblcita as a 
            INNER JOIN tblpaciente as b on (a.CodPaciente=b.CodigoP)
            where a.IDCita='$CodCita'");
            if ($checkCita->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Esta cita no le pertenece a este paciente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        $check_consult = mainModel::ejecutar_consulta_simple("SELECT ID FROM catconsultorio WHERE ID='$CodConsultorio'");
        if ($check_consult->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El consultorio no se encuentra registrado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //buscador codigo Paciente textBox
        $parametrosPaciente = explode('-', $NombrePaciente); //Nombre_Apellido_codPaciente


        $datos_Paciente = [
            "NombrePaciente" => $parametrosPaciente[0],
            "ApellidoPaciente" => $parametrosPaciente[1],
            "CodigoPaciente" => $parametrosPaciente[2]
        ];


        $CodigoPaciente = solicitudConsultaModelo::buscarCodPaciente($datos_Paciente);
        $row2 = $CodigoPaciente->fetch();/*ti*/
        //AQUÍ TOCÓ LUIS DE NUEVO


        $CodigoPaciente = $row2['CodigoP'];
        //////////////////////////////////////////////////////////////////////////////////////
        //buscador de empleado(doctor) para textbox

        $parametrosEmpleado = explode('-', $CodMedico); //Nombre_Apellido_NombreCargo_UltimoCargo_codEmpleado

        $datos_empleado = [
            "NombreEmpleado" => $parametrosEmpleado[0],
            "ApellidoEmpleado" => $parametrosEmpleado[1],
            "UltimoCargo" => $parametrosEmpleado[2],
            "CodigoEmpleado" => $parametrosEmpleado[3]
        ];


        $CodMedico = solicitudConsultaModelo::buscarCodEmpleado($datos_empleado);
        $CodMedico = $CodMedico->fetch(); //ti
        $CodMedico = $CodMedico['cod_empleado']; //primary key tabla empleado
        ///////////////////////////////////////////////////////////////////////////////////////

        /* ENRIQUE 25 SEPT 2022*/
        $MontoServicio = mainModel::ejecutar_consulta_simple("SELECT PrecioGeneral FROM catservicios WHERE idServicio = '2'");
        $MontoServicio = $MontoServicio->fetch();
        $MontoServicio = $MontoServicio['PrecioGeneral'];

        $datos_servicio = [
            "MontoServicio" => $MontoServicio
        ];

        $agregar_servicio = solicitudConsultaModelo::agregar_servicio_modelo($datos_servicio); //Mandando a crear servicio
        /////////////////////////////////////////////////////////////////////////////////

        $servicio = solicitudConsultaModelo::lastservice();
        $servicio = $servicio->fetch();
        $servicio = $servicio['id'];
        /*DATOS POR ENVIAR */
        $datos_consulta_reg = [
            "CodMedico" => $CodMedico, //De buscador en tiempo real
            "IdCita" => $CodCita,
            "CodPaciente" => $CodigoPaciente, //De buscador en tiempo real
            "CodConsultorio" => $CodConsultorio,
            "Estado" => $Estado,
            "idServicio" => $servicio,
            "MotivoConsulta" => $MotivoConsulta, //Falta por validar que no haga inserción maliciosa de db
            "RegistradoPor" => $registradoPor
        ];


        if ($cita == true) { //Cambiando estado de cita una vez realizada la solicitud
            $datos_cita = [
                "ID" => $CodCita
            ];


            $citaactu = solicitudConsultaModelo::actualizar_cita_modelo($datos_cita);
            if ($citaactu->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cita no se actualizó correctamente",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        $agregar_consulta = solicitudConsultaModelo::agregar_solConsulta_modelo($datos_consulta_reg);


        if ($agregar_servicio->rowCount() == 1 && $agregar_consulta->rowCount() == 1) {

            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consulta registrada",
                "Texto" => "Solicitud de Consulta registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la Solicitud de Consulta.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador
    /*-----------------Controlador para agregar consulta-----------------*/
    public function actualizar_consulta_controlador()
    {
        $codigo = mainModel::decryption($_POST['consulta_codigo_up']);
        $codigo = mainModel::limpiar_cadena($codigo);

        $CodMedico = mainModel::limpiar_cadena($_POST['cita_doctor_reg']);
        $CodConsultorio = mainModel::limpiar_cadena($_POST['consulta_consultorio_reg']);

        $MotivoConsulta = mainModel::limpiar_cadena($_POST['motivo_consulta_reg']);
        if (isset($_POST['estado_reg'])) {
            $Estado = 3; //se asigna el estado de estado de prioridad para pasar de un solo al doctor
        } else {
            $Estado = 1; //Sino se le asigna el estado de asignada para que se le muestre a la enfermera
        }

        /* -------------------------------------------Codigo registrador por automático-------------------------------------------*/
        /*21/03/2022 Ahora 24/03/2022 trabajando de balde*/
        session_start(['name' => 'SPM']);
        $registradoPor = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $registradoPor = $registradoPor->fetch();
        $registradoPor = $registradoPor['Codigo']; //Registra el empleado (recepcionista) que hizo el registro

        /*----------------Comprobar campos vacíos -----------------*/

        if ($CodMedico == "" || $CodConsultorio == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if ($codigo == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se encuentra la consulta que intenta editar",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /* VALIDACIONES */
        /*if($CodCita){
                if($CodCita!="0"){
                    $check_cita=mainModel::ejecutar_consulta_simple("SELECT IDCita FROM tblcita WHERE IDCita='$CodCita'");
                    if($check_cita->rowCount()==0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La cita no se encuentra registrada",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                    }
                }
                
            }*/
        $check_doctor = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$CodMedico'");
        if ($check_doctor->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El doctor no se encuentra registrado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $check_consult = mainModel::ejecutar_consulta_simple("SELECT ID FROM catconsultorio WHERE ID='$CodConsultorio'");
        if ($check_consult->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El consultorio no se encuentra registrado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //buscador codigo Paciente textBox
        //$parametrosPaciente=explode('-', $NombrePaciente);//Nombre_Apellido_codPaciente

        /*
            $datos_Paciente = [
                "NombrePaciente"=>$parametrosPaciente[0],
                "ApellidoPaciente"=>$parametrosPaciente[1],
                "CodigoPaciente"=>$parametrosPaciente[2]
            ];
            
                
            $CodigoPaciente=consultaModelo::buscarCodPaciente($datos_Paciente);
            $row2=$CodigoPaciente->fetch();/*ti*//*
            $CodigoPaciente=$row2['CodigoP'];
            */
        //////////////////////////////////////////////////////////////////////////////////////
        //buscador de empleado(doctor) para textbox
        /*
            $parametrosEmpleado=explode('-', $CodMedico);//Nombre_Apellido_NombreCargo_UltimoCargo_codEmpleado
            */
        /*
            $datos_empleado = [
                "NombreEmpleado"=>$parametrosEmpleado[0],
                "ApellidoEmpleado"=>$parametrosEmpleado[1],
                "UltimoCargo"=>$parametrosEmpleado[2],
                "CodigoEmpleado"=>$parametrosEmpleado[3]
            ];

            */ /*
            $CodMedico = consultaModelo::buscarCodEmpleado($datos_empleado);
            $CodMedico = $CodMedico->fetch();//ti
            $CodMedico = $CodMedico['cod_empleado'];//primary key tabla empleado 
            */
        ///////////////////////////////////////////////////////////////////////////////////////



        /*DATOS POR ENVIAR */
        $datos_consulta_reg = [
            "CodMedico" => $CodMedico, //De buscador en tiempo real
            /*"IdCita"=>$CodCita,*/
            "CodConsultorio" => $CodConsultorio,
            "Estado" => $Estado,
            "MotivoConsulta" => $MotivoConsulta,
            "Codigo" => $codigo,
            "RegistradoPor" => $registradoPor
        ];
        /*-----------------Comprobando si no ha cambiado el estado-----------------*/

        $check_estado = mainModel::ejecutar_consulta_simple("SELECT Estado FROM tblconsulta WHERE Codigo='$codigo' && Estado=6");
        if ($check_estado->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La consulta ya fue aceptada",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $actualizar_consulta = solicitudConsultaModelo::actualizar_solicitud_consulta_modelo($datos_consulta_reg);


        if ($actualizar_consulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "redireccion_violenta",
                "Titulo" => "Consulta registrada",
                "Texto" => "Solicitud de Consulta actualizada correctamente",
                "Tipo" => "success",
                "URL" => SERVERURL . "solicitud-consulta-list/"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró actualizar la Solicitud de Consulta.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador
    /*-----------------Controlador para agregar servicio-----------------*/
    public function serviciocerote()
    {
        $datos_servicio_reg = [
            "Tipo" => 2, //Servicio de consulta
            "Estado" => 1 //Estado de servicio activo
        ];
        $agregar_servicio = solicitudConsultaModelo::agregar_servicio_modelo($datos_servicio_reg);
    } //Terminacontrolador
    public function paginador_consulta_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
        //Listar asignaciones de consulta
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
        if ($_SESSION['cargo_spm'] == 3) //Si es recepcionista
        {
            if (isset($busqueda) && $busqueda != "") {
                $EsConsulta = true;
                $consulta = " SELECT *, f.Nombre as Estado_Consulta, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, 
                e.Nombres as NombresPaciente,e.Apellidos as ApellidoPaciente, c.Apellidos as ApellidoDoc, 
                e.Telefono as TelefonoPac, e.Email as EmailPac
                    from catestadoconsulta as f 
                    inner join tblconsulta as a ON (f.ID = a.Estado)
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE a.Estado = 1 OR a.Estado = 4 OR a.Estado = 3
                    AND (CONCAT(e.Nombres,' ',e.Apellidos) LIKE '%$busqueda%')
                    OR (a.Codigo LIKE '$busqueda') 
                    OR (d.INSS LIKE '$busqueda') 
                    OR (e.Cedula LIKE '$busqueda') 
                    OR (e.Telefono LIKE '$busqueda') 
                    OR (e.Email LIKE '$busqueda') 
                    OR (e.Nombres LIKE '%$busqueda%') 
                    OR (e.Apellidos LIKE '%$busqueda%')
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            } else {
                $EsConsulta = false;
                $consulta = " SELECT *, f.Nombre as Estado_Consulta, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, 
                e.Nombres as NombresPaciente,e.Apellidos as ApellidoPaciente, c.Apellidos as ApellidoDoc,
                e.Telefono as TelefonoPac, e.Email as EmailPac 
                from catestadoconsulta as f 
                inner join tblconsulta as a ON (f.ID = a.Estado)
                inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                inner join tblpersona as c on (b.CodPersona=c.Codigo)
                inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                WHERE a.Estado = 1 OR a.Estado = 4 OR a.Estado = 3
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
            }
        } else if ($_SESSION['cargo_spm'] == 2) //Si es doctor
        {

            $EsConsulta = false;
            $consulta = " SELECT *, f.Nombre as Estado_Consulta, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, 
            e.Nombres as NombresPaciente,e.Apellidos as ApellidoPaciente, c.Apellidos as ApellidoDoc, 
            e.Telefono as TelefonoPac, e.Email as EmailPac
                    from catestadoconsulta as f 
                    inner join tblconsulta as a ON (f.ID = a.Estado)
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE  a.Estado = 2 OR a.Estado = 3  
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
        } else { //si es enfermera u otro cargo
            $EsConsulta = false;
            $consulta = " SELECT *, f.Nombre as Estado_Consulta, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, 
            e.Nombres as NombresPaciente,e.Apellidos as ApellidoPaciente, c.Apellidos as ApellidoDoc,
            e.Telefono as TelefonoPac, e.Email as EmailPac 
                    from catestadoconsulta as f 
                    inner join tblconsulta as a ON (f.ID = a.Estado)
                    inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                    inner join tblpersona as c on (b.CodPersona=c.Codigo)
                    inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                    inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE  a.Estado = 1 
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
        }
        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 15);

        //Se utilizará el mismo paginador para Recepcionista y Enfermera Pero las funciones serán diferentes

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
                ';
        //Condicional de cargo para mostrar
        if ($_SESSION['cargo_spm'] == 3) //Si es recepcionista  
        {
            $tabla .= '
                            <th>ACTUALIZAR</th>
                            </tr>
                            </thead>
                            <tbody>
                            ';
        } else { //Si es enfermera
            $tabla .= '
                            <th>TOMAR SIGNOS</th>
                            </tr>
                            </thead>
                            <tbody>
                            ';
        }
        if ($total >= 1) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {

                ///Condicional de cargo para funciones
                if ($_SESSION['cargo_spm'] == 3) //Si es recepcionista  
                {
                    if ($rows['Estado_Consulta'] == 'Asignada') {
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
                        $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                                        <td><span class="badge badge-success">' . $rows['Estado_Consulta'] . '</span></td>
                                        <td>
                                        <a href="' . SERVERURL . 'solicitudConsulta-update/' . mainModel::encryption($rows['Codigo_consulta']) . '" class="btn btn-success">
                                            <i class="fas fa-sync-alt "style="font-size:30px"></i>	
                                        </a>
                                        </td>
                                    </tr>
                                ';
                        $contador++;
                    } else if ($rows['Estado_Consulta'] == 'Revertida') {
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
                        $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                                        <td><span class="badge badge-warning">Revertida</span></td>
                                        <td>
                                        <a href="' . SERVERURL . 'solicitudConsulta-update/' . mainModel::encryption($rows['Codigo_consulta']) /* Modificar
                                        ícono para que sea más intuitivo en actualizar DR */ . '" class="btn btn-success" > 
                                            <i class="fas fa-sync-alt" style="font-size:30px"></i>	
                                        </a>
                                        </td>
                                        </tr>
                                ';
                        $contador++;
                    } else if ($rows['Estado_Consulta'] == 'Prioridad') {
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
                        $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                                        <td><span class="badge badge-danger">' . $rows['Estado_Consulta'] . '</span></td>
                                        <td>
                                        <a href="' . SERVERURL . 'solicitudConsulta-update/' . mainModel::encryption($rows['Codigo_consulta']) /* Modificar
                                        ícono para que sea más intuitivo en actualizar DR */ . '" class="btn btn-success"> 
                                            <i class="fas fa-sync-alt" style="font-size:30px"></i>	
                                        </a>
                                        </td>
                                        </tr>
                                ';
                        $contador++;
                    }
                } else { //En caso de que sea enfermera o doctor
                    if ($rows['Estado_Consulta'] == 'Asignada') {
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
                        $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                                        <td><span class="badge badge-primary">' . $rows['Estado_Consulta'] . '</span></td>
                                        <th>
                                        <a href="' . SERVERURL . 'signos-vitales-auto/' . mainModel::encryption($rows['Codigo_consulta'])/*
                                        Aquí se llama la acción de generar Signos vitales */ . '"data-toggle="tooltip" title="Generar signos!"">
                                        <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                        </a>
                                        </td>
                                        </tr>
                                ';
                        $contador++;
                    } else if ($rows['Estado_Consulta'] == 'Anulada') //No se utiliza
                    {
                        /*
                                $tabla.='
                                <tr class="text-center" >
                                        <td>'.$rows['Codigo_consulta'].'</td>
                                        <th>'.$rows['NombresDoctor'].' '.$rows['ApellidoDoc'].'</th>
                                        <th>'.$rows['IdCita'].'</th>
                                        <th>'.$rows['NombresPaciente'].' '.$rows['ApellidoPaciente'].'</th>
                                        <th>'.$rows['Telefono'].'</th>
                                        <th>'.$rows['FechaYHora'].'</th>
                                        <td><span class="badge badge-danger">'.$rows['Estado_Consulta'].'</span></td>
                                        <td><span class="badge badge-danger">'.$rows['Estado_Consulta'].'</span></td>
                                        </tr>
                                ';$contador++;
                                */
                    }
                }
            } //Termina foreach



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
        return $tabla;
    } //Termina controlador



}
