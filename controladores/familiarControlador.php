<?php
if ($peticionAjax) {
    require_once "../modelos/familiarModelo.php";
} else {
    require_once "./modelos/familiarModelo.php";
}
class familiarControlador extends familiarModelo
{

    public function datos_item3_controlador()
    {
        return familiarmodelo::datos_item3_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar cargo-----------------*/
    /*Tabla Persona */
    public function agregar_familiar_empleado_controlador()
    {
        $Cedula = mainModel::limpiar_cadena($_POST['cedula_familiar_empleado_reg']);
        $Nombres = mainModel::limpiar_cadena($_POST['nombre_familiar_empleado_reg']);
        $Apellidos = mainModel::limpiar_cadena($_POST['apellido_familiar_reg']);
        $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['familiar_fecha_reg']);
        $Genero = mainModel::limpiar_cadena($_POST['item_genero_reg']);
        $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_reg']);
        $Direccion = mainModel::limpiar_cadena($_POST['direccion_fam_reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['familiar_telefono_reg']);
        $FamiliarDe = mainModel::limpiar_cadena(explode("__", $_POST['familiar_de_reg'])[1]);
        $Parentesco = mainModel::limpiar_cadena($_POST['parentesco_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if (
            $Cedula == "" || $Nombres == "" || $Apellidos == "" || $Fecha_de_nacimiento == "" || $Genero == "" ||
            $Estado_civil == "" ||  $Telefono == "" || $FamiliarDe == "" || $Direccion == "" || $Parentesco == ""
        ) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR NOMBRE----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}", $Nombres)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR APELLIDO----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}", $Apellidos)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El apellido del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR CÉDULA----------------------------------------*/
        if (mainModel::verificar_datos("[a-zA-Z0-9-]{16,16}", $Cedula)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La cédula del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR DIRECCIÓN----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,70}", $Direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La dirección del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Genero-----------------*/
        if ($Genero < 1 || $Genero > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un genero valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Estado civil-----------------*/
        if ($Estado_civil < 1 || $Estado_civil > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un estado civil valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //Validación de Fecha By Luis---Compuesto ahora por steven
        $fechaactual = mainModel::ejecutar_consulta_simple("SELECT CURRENT_DATE fechaActual;");
        if ($fechaactual->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se pudo obtener la fecha actual del servidor",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $fechaactual = $fechaactual->fetch();
        $fecha = explode("-", $Fecha_de_nacimiento, 3); //y-m-d
        $fecAct = explode("-", $fechaactual['fechaActual'], 3);

        if (($fecha[0] > $fecAct[0])) { //si es una fecha después de hoy
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Por favor seleccione una año valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Nombre de empleado-----------------*/
        //Esto será eliminado ya que funcionará con buscador
        /* $codigoP = mainModel::ejecutar_consulta_simple("SELECT * FROM tblpersona WHERE Nombres = '$FamiliarDe'");
        if ($codigoP->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La persona de la que es familiar no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } */
        //Se obtiene el código de la persona registrada que tiene relación con el familiar nuevo
        /* $row1 = $codigoP->fetch();
        $codigoP = $row1['Codigo']; */


        /*-----------------Comprobando EMAIL o correo-----------------*/
        $correo = $Telefono;
        if ($correo != "") {
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM tblpersona WHERE Email='$correo'");
                if ($check_email->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Email ingresado ya está registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                } else {
                    /*Datos por enviar persona */
                    $datos_persona_reg = [
                        "Cedula" => $Cedula,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Genero" => $Genero,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "correo" => $correo,
                        "Estado" => 1
                    ];
                }
            } else {
                /*Datos por enviar persona */
                $datos_persona_reg = [
                    "Cedula" => $Cedula,
                    "Nombres" => $Nombres,
                    "Apellidos" => $Apellidos,
                    "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                    "Genero" => $Genero,
                    "Estado_civil" => $Estado_civil,
                    "Direccion" => $Direccion,
                    "Telefono" => $Telefono,
                    "Estado" => 1
                ];
            }
        }


        $agregar_persona = familiarModelo::agregar_persona_modelo($datos_persona_reg); //Se agrega la persona familiar
        if ($agregar_persona->rowCount() != 1) { //Validación por inserción
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la persona",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $obtener_codigo_persona2 = familiarModelo::obtener_codigo2(0); //Se le asigna código de persona
        /*Datos por enviar familiar */
        $codigoPersona = familiarModelo::obtener_persona_modelo($datos_persona_reg);
        $row2 = $codigoPersona->fetch();
        $codigoPersona = $row2['Codigo'];
        $ID = $codigoPersona * 5;

        $datos_familiar = [
            "ID" => $ID,
            "CodPersona" => $codigoPersona,
            "ContactoEmergencia" => $correo,
            "Tutor" => 0
        ];
        //Se agrega el familiar
        $agregar_familiar = familiarModelo::agregar_familiar_modelo($datos_familiar);
        if ($agregar_familiar->rowCount() != 1) { //Validación por inserción
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el familiar",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $datos_relacion = [
            "Codigo_Persona" => $FamiliarDe,
            "Codigo_Familiar" => $ID,
            "ID_Parentesco" => $Parentesco,
            "Tutor" => 2

        ];
        //Se agrega relación entre familiar y empleado

        $agregar_relacion = familiarModelo::agregar_relacion_modelo($datos_relacion);
        if ($agregar_relacion->rowCount() != 1) { //Validación por inserción
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la relación",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($agregar_persona->rowCount() == 1 && $agregar_familiar->rowCount() == 1 && $agregar_relacion->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Familiar registrado",
                "Texto" => "Familiar registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el familiar",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador
    public function paginador_familiar_empleado_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $tipo)
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

        if (isset($busqueda) && $busqueda != "") {
            $EsConsulta = true;
            $consulta = "SELECT *, tblfamiliares.ID as IdFamiliar ,d.Nombres as NombreEmpleado, a.Nombres as NombreFamiliar, 
            a.Apellidos as ApellidosFamiliar, a.Telefono as TelefonoFam, a.Email as EmailFam
            FROM tblfamiliares 
            INNER JOIN tblpersona as a ON tblfamiliares.CodPersona = a.Codigo
            INNER JOIN tblrelacionpersonafamiliar as b ON tblfamiliares.ID = b.Codigo_Familiar
            inner join tblpersona as d ON b.Codigo_Persona = d.Codigo
            INNER JOIN catparentesco as e ON b.ID_Parentesco = e.ID
            INNER JOIN tblempleado as x on (x.CodPersona=b.Codigo_Persona)
                WHERE (CONCAT(a.Nombres,' ',a.Apellidos) LIKE '%$busqueda%')
                OR (tblfamiliares.ID LIKE '$busqueda')
                OR (a.Cedula LIKE '$busqueda')
                OR (a.Telefono LIKE '$busqueda')
                OR (a.Email LIKE '$busqueda')
               /*  OR (d.Nombres LIKE '%$busqueda%')
                OR (d.Apellidos LIKE '%$busqueda%') */
                ORDER BY tblfamiliares.ID DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT *, tblfamiliares.ID as IdFamiliar ,d.Nombres as NombreEmpleado, a.Nombres as NombreFamiliar, 
            a.Apellidos as ApellidosFamiliar, a.Telefono as TelefonoFam, a.Email as EmailFam
            FROM tblfamiliares 
            INNER JOIN tblpersona as a ON tblfamiliares.CodPersona = a.Codigo
            INNER JOIN tblrelacionpersonafamiliar as b ON tblfamiliares.ID = b.Codigo_Familiar
            inner join tblpersona as d ON b.Codigo_Persona = d.Codigo
            INNER JOIN catparentesco as e ON b.ID_Parentesco = e.ID
            INNER JOIN tblempleado as x on (x.CodPersona=b.Codigo_Persona)
            ORDER BY tblfamiliares.ID DESC LIMIT $inicio,$registros";
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
                        <th>COD. FAMILIAR</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>CONTACTO</th>
                        <th>PARENTESCO</th>
                        <th>FAMILIAR DE</th>
                        <th>ACTUALIZAR</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['IdFamiliar'] . '</td>
                                <td>' . $rows['NombreFamiliar'] . '</td>
                                <td>' . $rows['ApellidosFamiliar'] . '</td>';
                if ($rows['TelefonoFam'] != null) {
                    $tabla .= '<td>' . $rows['TelefonoFam'] . '</td>';
                } else {
                    $tabla .= '<td>' . $rows['EmailFam'] . '</td>';
                }

                $tabla .= '<td>' . $rows['Nombre']/*Esto es del nombre de parentesco */ . '</td>
                                <td>' . $rows['NombreEmpleado'] . '</td>
                                ' //Aquí tocó luis
                    . '<td>
                                    <a href="' . SERVERURL . 'familiar-update/' . mainModel::encryption($rows['IdFamiliar']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                        </tr>
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
        return $tabla;
    }
    public function paginador_familiar_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $tipo)
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

        if (isset($busqueda) && $busqueda != "") {
            $EsConsulta = true;
            $consulta = "SELECT *, tblfamiliares.ID as IdFamiliar
            FROM tblfamiliares
            INNER JOIN tblpersona ON tblfamiliares.CodPersona = tblpersona.Codigo
            INNER JOIN tblrelacionpersonafamiliar ON tblfamiliares.ID = tblrelacionpersonafamiliar.Codigo_Familiar
            INNER JOIN catparentesco ON tblrelacionpersonafamiliar.ID_Parentesco = catparentesco.ID
            INNER JOIN tblpaciente as x on (x.CodPersona=tblrelacionpersonafamiliar.Codigo_Persona)
                WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                OR (tblfamiliares.ID LIKE '$busqueda')
                OR (tblpersona.Cedula LIKE '$busqueda')
                OR (tblpersona.Telefono LIKE '$busqueda')
                OR (tblpersona.Email LIKE '$busqueda')
                OR (Nombres LIKE '%$busqueda%')
                OR (Apellidos LIKE '%$busqueda%')
                ORDER BY tblfamiliares.ID DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT *, tblfamiliares.ID as IdFamiliar
            FROM tblfamiliares
            INNER JOIN tblpersona ON tblfamiliares.CodPersona = tblpersona.Codigo
            INNER JOIN tblrelacionpersonafamiliar ON tblfamiliares.ID = tblrelacionpersonafamiliar.Codigo_Familiar
            INNER JOIN catparentesco ON tblrelacionpersonafamiliar.ID_Parentesco = catparentesco.ID
            INNER JOIN tblpaciente as x on (x.CodPersona=tblrelacionpersonafamiliar.Codigo_Persona)
            ORDER BY tblfamiliares.ID DESC LIMIT $inicio,$registros";
        }



        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 15);
        if ($tipo == 0) {
            $tabla .= '
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>ID</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>CONTACTO</th>
                        <th>PARENTESCO</th>
                        <th>TUTOR</th>
                        <th>ACTUALIZAR</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    if ($rows['Tutor'] == 1) {
                        $Tutor = "Es tutor";
                    } else {
                        $Tutor = "No es tutor";
                    }
                    $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['IdFamiliar'] . '</td>
                                <td>' . $rows['Nombres'] . '</td>
                                <td>' . $rows['Apellidos'] . '</td>';
                    if ($rows['Telefono'] != null) {
                        $tabla .= '<td>' . $rows['Telefono'] . '</td>';
                    } else {
                        $tabla .= '<td>' . $rows['Email'] . '</td>';
                    }
                    $tabla .= '<td>' . $rows['Nombre']/*Esto es del nombre de parentesco */ . '</td>
                                <td>' . $Tutor . '</td>
                                ' //Aquí tocó luis
                        . '<td>
                                    <a href="' . SERVERURL . 'familiar-update/' . mainModel::encryption($rows['IdFamiliar']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                        </tr>
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
        } else {
            $tabla .= '
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>ID DE RESPONSABLE</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>TELEFONO</th>
                        <th>PARENTESCO</th>
                        <th>ACTUALIZAR</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['IdFamiliar'] . '</td>
                                <td>' . $rows['Nombres'] . '</td>
                                <td>' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['ContactoEmergencia'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                ' //Aquí tocó luis
                        . '<td>
                                    <a href="' . SERVERURL . 'familiar-update/' . mainModel::encryption($rows['IdFamiliar']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </td>
                        </tr>
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
        }
        $tabla .= '</tbody></table></div>';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        return $tabla;
    }
    //Nueva función para familiares de pacientes
    public function agregar_familiar_paciente()
    {
        $Cedula = mainModel::limpiar_cadena($_POST['cedula_familiar_reg']);
        $Nombres = mainModel::limpiar_cadena($_POST['nombre_familiar_reg']);
        $Apellidos = mainModel::limpiar_cadena($_POST['apellido_familiar_paciente_reg']);
        $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['familiar_paciente_fecha_reg']);
        $Genero = mainModel::limpiar_cadena($_POST['item_genero_reg']);
        $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_reg']);
        $Direccion = mainModel::limpiar_cadena($_POST['direccion_fam_paciente_reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['familiar_paciente_telefono_reg']);
        $FamiliarDe = mainModel::limpiar_cadena(explode("__", $_POST['familiar_de_reg'])[1]);
        $Parentesco = mainModel::limpiar_cadena($_POST['parentesco_reg']);
        if (isset($_POST['tutor_paciente_reg'])) {
            $Tutor = mainModel::limpiar_cadena($_POST['tutor_paciente_reg']);
        } else {
            $Tutor = 2; //Si tutor no viene presionado se envía con 2 porque no es tutor
        }


        /*----------------Comprobar campos vacíos -----------------*/

        if (
            $Cedula == "" || $Nombres == "" || $Apellidos == "" || $Fecha_de_nacimiento == "" || $Genero == "" ||
            $Estado_civil == "" ||  $Telefono == "" || $FamiliarDe == "" || $Direccion == ""
        ) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR NOMBRE----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}", $Nombres)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR APELLIDO----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,50}", $Apellidos)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El apellido del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR CÉDULA----------------------------------------*/
        if (mainModel::verificar_datos("[a-zA-Z0-9-]{16,16}", $Cedula)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La cédula del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------------------------------VALIDAR DIRECCIÓN----------------------------------------*/
        if (mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,70}", $Direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La dirección del familiar no coincide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Genero-----------------*/
        if ($Genero < 1 || $Genero > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un genero valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Estado civil-----------------*/
        if ($Estado_civil < 1 || $Estado_civil > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un estado civil valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Nombre de paciente-----------------*/
        $codigoP = mainModel::ejecutar_consulta_simple("SELECT * FROM tblpersona WHERE Cedula = '$FamiliarDe'");
        if ($codigoP->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La persona de la que es familiar no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $row1 = $codigoP->fetch();
        $codigoP = $row1['Codigo'];

        /*-----------------Comprobando EMAIL-----------------*/
        $correo = $Telefono;
        if ($correo != "") {
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM tblpersona WHERE Email='$correo'");
                if ($check_email->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Email ingresado ya está registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                } else {
                    /*Datos por enviar persona */
                    $datos_persona_reg = [
                        "Cedula" => $Cedula,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Genero" => $Genero,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "correo" => $correo,
                        "Estado" => 1
                    ];
                }
            } else {
                /*Datos por enviar persona */
                $datos_persona_reg = [
                    "Cedula" => $Cedula,
                    "Nombres" => $Nombres,
                    "Apellidos" => $Apellidos,
                    "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                    "Genero" => $Genero,
                    "Estado_civil" => $Estado_civil,
                    "Direccion" => $Direccion,
                    "Telefono" => $Telefono,
                    "Estado" => 1
                ];
            }
        }

        $agregar_persona = familiarModelo::agregar_persona_modelo($datos_persona_reg);

        $obtener_codigo_persona2 = familiarModelo::obtener_codigo2(0);
        /*Datos por enviar familiar */
        $codigoPersona = familiarModelo::obtener_persona_modelo($datos_persona_reg);
        $row2 = $codigoPersona->fetch();/*ti*/
        $codigoPersona = $row2['Codigo'];
        $ID = $codigoPersona * 5;
        $datos_familiar = [
            "ID" => $ID,
            "CodPersona" => $codigoPersona,
            "ContactoEmergencia" => $Telefono,

        ];

        $agregar_familiar = familiarModelo::agregar_familiar_modelo($datos_familiar);

        //Se inserta la relación entre familiar y la persona (paciente)
        $DatosPaRelacion = [
            "Codigo_Persona" => $codigoP,
            "Codigo_Familiar" => $ID,
            "ID_Parentesco" => $Parentesco,
            "Tutor" => $Tutor
        ];

        $insertar_relacion = familiarModelo::agregar_relacion_modelo($DatosPaRelacion);

        if ($agregar_persona->rowCount() == 1 && $agregar_familiar->rowCount() == 1) {

            if ($insertar_relacion->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Familiar registrado",
                    "Texto" => "Familiar registrado correctamente",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró añadir relación con el familiar",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el familiar",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }
    //AQUÍ TOCÓ LUIS
    /*-----------------Controlador datos usuario-----------------*/
    public function datos_familiar_controladorUPDATE($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return familiarModelo::datos_familiar_modeloUPDATE($id);
    }/*Fin de controlador */
    public function actualizar_familiar_empleado_controlador($IdFamiliar)
    {
        /*Recibe Codigo */
        $codigo = mainModel::decryption($IdFamiliar);
        $codigo = mainModel::limpiar_cadena($codigo);
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM tblfamiliares WHERE ID='$codigo'");
        if ($check_user->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos encontrado el familiar en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $Nombres = mainModel::limpiar_cadena($_POST['nombre_familiar_up']);
            $Apellidos = mainModel::limpiar_cadena($_POST['apellido_familiar_up']);
            $Cedula = mainModel::limpiar_cadena($_POST['cedula_familiar_up']);
            $Genero = mainModel::limpiar_cadena($_POST['item_genero_up']);
            $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['familiar_fecha_up']);
            $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_up']);
            $Direccion = mainModel::limpiar_cadena($_POST['direccion_fam_up']);
            $Telefono = mainModel::limpiar_cadena($_POST['familiar_telefono_up']);
            $Famde = mainModel::limpiar_cadena(explode("__", $_POST['familiar_de_up'])[1]);
            $Parentesco = mainModel::limpiar_cadena($_POST['parentesco_up']);
            $cedulaoriginal = mainModel::limpiar_cadena($_POST['cedoriginal_up']);
            $Estado = mainModel::limpiar_cadena($_POST['estado_up']);


            /*Comprueba datos vacíos */
            if (
                $Nombres == "" || $Apellidos == "" || $Cedula == "" || $Genero == ""
                || $Fecha_de_nacimiento == "" || $Estado_civil == "" || $Direccion == "" || $Telefono == "" || $Estado == ""
            ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se han llenado los campos obligatorios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de los datos -----------------*/

            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,70}", $Nombres)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El nombre no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,70}", $Apellidos)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El apellido no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*----------------------------------------VALIDAR CÉDULA----------------------------------------*/
            if (mainModel::verificar_datos("[a-zA-Z0-9-]{16,16}", $Cedula)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cédula del familiar no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $Direccion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La dirección no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Comprobando Nombre de empleado y consiguiendo código-----------------*/
            $codigoP = mainModel::ejecutar_consulta_simple("SELECT * FROM tblpersona as a 
            INNER JOIN tblempleado as b on (a.Codigo=b.CodPersona)
            WHERE b.Codigo = '$Famde'");
            if ($codigoP->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La persona de la que es familiar no está registrada en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            //Validación de Fecha By Luis---Compuesto ahora por steven
            $fechaactual = mainModel::ejecutar_consulta_simple("SELECT CURRENT_DATE fechaActual;");
            if ($fechaactual->rowCount() <= 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se pudo obtener la fecha actual del servidor",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $fechaactual = $fechaactual->fetch();
            $fecha = explode("-", $Fecha_de_nacimiento, 3); //y-m-d
            $fecAct = explode("-", $fechaactual['fechaActual'], 3);

            if (($fecha[0] > $fecAct[0])) { //si es una fecha después de hoy
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "Por favor seleccione una año valido",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $row1 = $codigoP->fetch();
            $codigoP = $row1['Codigo'];

            /* Se consigue el código de persona por el número de cédula */
            /* Se modificará y cambiará estrategia con dato oculto en vista, además que se validará */
            /*                  VALIDACIÓN DE CÉDULA ACTUAL              */
            /*-----------------Comprobando Cedula-----------------*/
            $check_cedula = mainModel::ejecutar_consulta_simple("SELECT Cedula FROM tblpersona WHERE Cedula='$cedulaoriginal'");
            if ($check_cedula->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cedula de esta persona no está registrada en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $datos_persona_up = [
                "Cedula" => $cedulaoriginal
            ];
            $codigoPersona = familiarModelo::obtener_persona_modelo($datos_persona_up);

            /* VALIDACIÓN EMAIL MAMALONA */
            /*-----------------Comprobando EMAIL-----------------*/
            $row2 = $codigoPersona->fetch();/*ti*/
            $codigoPersona = $row2['Codigo'];

            $correo = $Telefono;
            if ($correo != "") {
                if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {

                    /*Datos por enviar persona */
                    $datos_persona_up2 = [
                        "Codigo" => $codigoPersona,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Cedula" => $Cedula,
                        "Genero" => $Genero,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "Email" => $Telefono,
                        "ContactoEmergencia" => $Telefono,
                        "familiarde" => $codigoP,
                        "parentesco" => $Parentesco,
                        "Estado" => $Estado
                    ];
                } else {
                    /*Datos por enviar persona */
                    $datos_persona_up2 = [
                        "Codigo" => $codigoPersona,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Cedula" => $Cedula,
                        "Genero" => $Genero,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "Telefono" => $Telefono,
                        "ContactoEmergencia" => $Telefono,
                        "familiarde" => $codigoP,
                        "parentesco" => $Parentesco,
                        "Estado" => $Estado
                    ];
                }
            }


            $actualizar_persona = familiarModelo::actualizar_personayFAMILIAR_modelo($datos_persona_up2);


            if ($actualizar_persona->rowCount() != 0) {
                $alerta = [
                    "Alerta" => "redireccion_violenta",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos del familiar han sido actualizados.",
                    "Tipo" => "success",
                    "URL" => SERVERURL . "familiares-list/"
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró actualizar el Familiar, verifique que realizó cambios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    }/*Fin de controlador */
    public function actualizar_familiar_controlador()
    {
        /*Recibe Codigo */
        $codigo = mainModel::decryption($_POST['fam_id']);
        $codigo = mainModel::limpiar_cadena($codigo);
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM tblfamiliares WHERE ID='$codigo'");
        if ($check_user->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos encontrado el familiar en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $Nombres = mainModel::limpiar_cadena($_POST['nombre_familiar_up']);
            $Apellidos = mainModel::limpiar_cadena($_POST['apellido_familiar_up']);
            $Cedula = mainModel::limpiar_cadena($_POST['cedula_familiar_up']);
            $Genero = mainModel::limpiar_cadena($_POST['item_genero_up']);
            $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['familiar_fecha_up']);
            $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_up']);
            $Direccion = mainModel::limpiar_cadena($_POST['direccion_fam_up']);
            $Telefono = mainModel::limpiar_cadena($_POST['familiar_telefono_up']);
            $Famde = mainModel::limpiar_cadena($_POST['familiar_de_up']);
            $Parentesco = mainModel::limpiar_cadena($_POST['parentesco_up']);
            $Tutor = mainModel::limpiar_cadena($_POST['tutor_up']);
            $cedulaoriginal = mainModel::limpiar_cadena($_POST['cedoriginal_up']);


            /*Comprueba datos vacíos */
            if (
                $Nombres == "" || $Apellidos == "" || $Cedula == "" || $Genero == ""
                || $Fecha_de_nacimiento == "" || $Estado_civil == "" || $Direccion == "" || $Telefono == ""
            ) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se han llenado los campos obligatorios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de los datos -----------------*/

            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,70}", $Nombres)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El nombre no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,70}", $Apellidos)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El apellido no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*----------------------------------------VALIDAR CÉDULA----------------------------------------*/
            if (mainModel::verificar_datos("[a-zA-Z0-9-]{16,16}", $Cedula)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cédula del familiar no coincide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $Direccion)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La dirección no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Comprobando Nombre de empleado y consiguiendo código-----------------*/
            $codigoP = mainModel::ejecutar_consulta_simple("SELECT * FROM tblpersona WHERE Nombres = '$Famde'");
            if ($codigoP->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La persona de la que es familiar no está registrada en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $row1 = $codigoP->fetch();
            $codigoP = $row1['Codigo'];

            /* Se consigue el código de persona por el número de cédula */
            /* Se modificará y cambiará estrategia con dato oculto en vista, además que se validará */
            /*                  VALIDACIÓN DE CÉDULA ACTUAL              */
            /*-----------------Comprobando Cedula-----------------*/
            $check_cedula = mainModel::ejecutar_consulta_simple("SELECT Cedula FROM tblpersona WHERE Cedula='$cedulaoriginal'");
            if ($check_cedula->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cedula de esta persona no está registrada en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $datos_persona_up = [
                "Cedula" => $cedulaoriginal
            ];
            $codigoPersona = familiarModelo::obtener_persona_modelo($datos_persona_up);

            /* VALIDACIÓN EMAIL MAMALONA */
            /*-----------------Comprobando EMAIL-----------------*/
            $row2 = $codigoPersona->fetch();/*ti*/
            $codigoPersona = $row2['Codigo'];

            $correo = $Telefono;
            if ($correo != "") {
                if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {

                    /*Datos por enviar persona */
                    $datos_persona_up2 = [
                        "Codigo" => $codigoPersona,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Cedula" => $Cedula,
                        "Genero" => $Genero,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "Email" => $Telefono,
                        "contacto" => $Telefono,
                        "familiarde" => $codigoP,
                        "parentesco" => $Parentesco,
                        "tutor" => $Tutor,
                        "Estado" => 1
                    ];
                } else {
                    /*Datos por enviar persona */
                    $datos_persona_up2 = [
                        "Codigo" => $codigoPersona,
                        "Nombres" => $Nombres,
                        "Apellidos" => $Apellidos,
                        "Cedula" => $Cedula,
                        "Genero" => $Genero,
                        "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
                        "Estado_civil" => $Estado_civil,
                        "Direccion" => $Direccion,
                        "Telefono" => $Telefono,
                        "contacto" => $Telefono,
                        "familiarde" => $codigoP,
                        "parentesco" => $Parentesco,
                        "tutor" => $Tutor,
                        "Estado" => 1
                    ];
                }
            }


            $actualizar_persona = familiarModelo::actualizar_personayFAMILIAR_modelo($datos_persona_up2);


            if ($actualizar_persona->rowCount() != 0) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Familiar actualizado",
                    "Texto" => "Familiar actualizado correctamente",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró actualizar el Familiar, verifique que realizó cambios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    }/*Fin de controlador */
}
