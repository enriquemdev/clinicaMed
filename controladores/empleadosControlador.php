<?php
if ($peticionAjax) {
    require_once "../modelos/empleadosModelo.php";
} else {
    require_once "./modelos/empleadosModelo.php";
}
class empleadosControlador extends empleadosModelo
{
    public function datos_item1_controlador()
    {
        return empleadosModelo::datos_item1_modelo();
    }/*Fin de controlador */
    public function datos_item2_controlador()
    {
        return empleadosModelo::datos_item2_modelo();
    }/*Fin de controlador */
    public function datos_item3_controlador()
    {
        return empleadosModelo::datos_item3_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar empleado-----------------*/
    /*Tabla Persona */
    public function agregar_persona_controlador()
    {
        $Cedula = mainModel::limpiar_cadena($_POST['empleado_cedula_reg']);
        $Nombres = mainModel::limpiar_cadena($_POST['empleado_nombre_reg']);
        $Apellidos = mainModel::limpiar_cadena($_POST['empleado_apellido_reg']);
        $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['empleado_nacio_reg']);
        $Genero = mainModel::limpiar_cadena($_POST['item_genero_reg']);
        $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_reg']);
        $Direccion = mainModel::limpiar_cadena($_POST['empleado_direccion_reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['empleado_telefono_reg']);
        $Email = mainModel::limpiar_cadena($_POST['empleado_correo_reg']);
        /*Tabla Empleado */
        $INSS = mainModel::limpiar_cadena($_POST['empleado_inss_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Cedula == "" || $Nombres == "" || $Apellidos == "" || $Fecha_de_nacimiento == "" || $Genero == "" || $Estado_civil == "" || $Direccion == "" || $Telefono == "" || $INSS == "") {
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
        /*          VALIDACIONES DE DATOS               aquí tocó luis */
        /*-----------------Verificando integridad de cédula -----------------*/

        if (mainModel::verificar_datos("[a-zA-Z0-9- ]{16,16}", $Cedula)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La cédula no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de inss -----------------*/

        if (mainModel::verificar_datos("[0-9-]{9,9}", $INSS)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El INSS no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de teléfono -----------------*/

        if (mainModel::verificar_datos("[0-9#-+ ]{8,15}", $Telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Telefono no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Cedula (Solo puede existir una)-----------------*/
        $check_cedula = mainModel::ejecutar_consulta_simple("SELECT Cedula FROM tblpersona WHERE Cedula='$Cedula'");
        if ($check_cedula->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La cedula ingresada ya está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando INSS (Solo puede existir uno)-----------------*/
        $check_INSS = mainModel::ejecutar_consulta_simple("SELECT INSS FROM tblempleado WHERE INSS='$INSS'");
        if ($check_INSS->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El INSS ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando EMAIL-----------------*/
        if ($Email != "") {
            if (filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM tblpersona WHERE Email='$Email'");
                if ($check_email->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Email ingresado ya está registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Email ingresado no es valido",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
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
            "Email" => $Email,
            "Estado" => 1

        ];


        $agregar_persona = empleadosModelo::agregar_persona_modelo($datos_persona_reg);
        /*Datos por enviar empleado */


        $obtener_codigo_persona2 = empleadosModelo::obtener_codigo2(0);

        $codigoPersona = empleadosModelo::obtener_persona_modelo($datos_persona_reg);
        $row2 = $codigoPersona->fetch();/*ti*/
        $codigoPersona = $row2['Codigo'];
        //$Codigo=$codigoPersona*10;
        $datos_empleado_reg = [
            //"Codigo"=>$Codigo,
            "INSS" => $INSS,
            "CodPersona" => $codigoPersona
        ];
        $agregar_empleado = empleadosModelo::agregar_empleado_modelo($datos_empleado_reg);



        if ($agregar_persona->rowCount() == 1 && $agregar_empleado->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Empleado registrado",
                "Texto" => "Empleado registrado correctamente",
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
    } //termina controlador

    public function paginador_persona_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $condicion, $condRadio, $condRadio2, $combobox)
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

        if ((isset($busqueda) && $busqueda != "") || $condicion != "" || $condRadio != "" || $condRadio2 != "" || $combobox != "") {
            $EsConsulta = true;

            $consulta = "SELECT 
                tblempleado.Codigo as CodigoEmpleado, 
                tblpersona.Nombres as NombresEmpleado,
                tblpersona.Apellidos as ApellidosEmpleado,
                tblempleado.INSS as INSS,
                tblpersona.Telefono as Telefono, 
                tblpersona.Codigo as CodigoPersona
                FROM tblempleado 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado 
                INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                WHERE ((CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%') 
                OR (tblempleado.Codigo LIKE '$busqueda') 
                OR (INSS LIKE '$busqueda') 
                OR (tblpersona.Cedula LIKE '$busqueda') 
                OR (tblpersona.Telefono LIKE '$busqueda') 
                OR (tblpersona.Email LIKE '$busqueda') 
                OR (Nombres LIKE '%$busqueda%') 
                OR (Apellidos LIKE '%$busqueda%'))";

            /* if ($_SESSION['condicion'] == 1) {
                $consulta = $consulta . "";
            } */

            if ($_SESSION['condRadio'] != "") {
                $consulta = $consulta . "AND (tblpersona.Genero = " . $_SESSION['condRadio'] . ") ";
            }

            if ($_SESSION['condRadio2'] != "") {
                $consulta = $consulta . "AND (tblpersona.Estado = " . $_SESSION['condRadio2'] . ") ";
            }

            if ($_SESSION['combobox'] != "") {
                $consulta = $consulta . "AND (catcargos.ID = " . $_SESSION['combobox'] . ") ";
            }
            /* $consulta = $consulta . ""; */
            $consulta = $consulta . "AND (tblhistorialcargos.Estado=1) ORDER BY CodigoEmpleado DESC LIMIT $inicio,$registros";
        } else {
            //Identificador: 111
            $EsConsulta = false;
            //Remplazar esto
            //$consulta="call sp_ObtenerDatosEmpleados();";
            //Con esto
            $consulta = "SELECT SQL_CALC_FOUND_ROWS 
                tblempleado.Codigo as CodigoEmpleado, 
                tblpersona.Nombres as NombresEmpleado,
                tblpersona.Apellidos as ApellidosEmpleado,
                tblempleado.INSS as INSS,
                tblpersona.Telefono as Telefono,
                tblpersona.Codigo as CodigoPersona
                FROM tblempleado INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                ORDER BY CodigoEmpleado DESC LIMIT $inicio,$registros";
            //Termino identificador: 111
        }

        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        //Identificador: 222
        if ($EsConsulta) {
            $pdf = $conexion->query($consulta);
            $pdf = $pdf->fetchAll();
            $pasarPDF = json_encode($pdf);
        } else {
            $pdf = $conexion->query('CALL sp_ObtenerDatosEmpleados();');
            $pdf = $pdf->fetchAll();
            $pasarPDF = json_encode($pdf);
        }
        //Termino identificador: 222

        $Npaginas = ceil($total / 50);
        $tabla .= '
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>COD EMPLEADO</th>
                        <th>INSS</th>
                        <th>NOMBRE</th>
                        <th>APELLIDO</th>
                        <th>TELEFONO</th>
                        <th>ACTUALIZAR</th>
                        <th>REPORTE</th>

                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                //Aqui manoseo steven
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['CodigoEmpleado'] . '</td>
                                <th>' . $rows['INSS'] . '</th>
                                <th>' . $rows['NombresEmpleado'] . '</th>
                                <th>' . $rows['ApellidosEmpleado'] . '</th>
                                <th>' . $rows['Telefono'] . '</th>
                                ' //Aquí tocó luis
                    . '<td>
                                    <a href="' . SERVERURL . 'empleado-update/' . mainModel::encryption($rows['CodigoEmpleado']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>
                                ' //Aqui manoseo steven
                    . '<td>
                                ' //hasta aquí
                    . '<a href="' . SERVERURL . 'Reportes/reporte-u-empleado.php?idEmpleado='
                    . mainModel::encryption($rows['CodigoEmpleado']) . '&idPersona='
                    . mainModel::encryption($rows['CodigoPersona']) . '" 
                                target="_blank" 
                                class="btn btn-info">
                                <i class="fas fa-file-pdf"></i>
                                </a>
                                </td>
                                ' //Aqui termino
                    . '
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
    } //Termina controlador
    //Aqui manoseo steven
    public function datos_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::datos_empleado_modelo($id);
    }
    public function datos_familiares_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::datos_familiares_empleado_modelo($id);
    }
    public function datos_especialidades_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::datos_especialidades_empleado_modelo($id);
    }
    public function obtener_ultimo_cargo_activo_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::obtener_ultimo_cargo_activo_empleado_modelo($id);
    }
    public function obtener_cargos_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::obtener_cargos_empleado_modelo($id);
    }

    //Aqui termino

    //AQUÍ TOCÓ LUIS
    /*-----------------Controlador datos usuario-----------------*/
    public function datos_empleado_controladorUPDATE($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::datos_empleado_modeloUPDATE($id);
    }/*Fin de controlador */
    /*Aquí tocó STEVEN */
    public function datos_estudios_academicos_empleado_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return empleadosModelo::datos_estudios_academicos_empleado_modelo($id);
    }/*Fin de controlador */
    public function actualizar_empleado_controlador()
    {
        /*Recibe Codigo */
        $codigo = mainModel::decryption($_POST['empleado_codigo_up']);
        $codigo = mainModel::limpiar_cadena($codigo);
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM tblempleado WHERE Codigo='$codigo'");
        if ($check_user->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hemos encontrado el usuario en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campos = $check_user->fetch();
            $Nombres = mainModel::limpiar_cadena($_POST['empleado_nombre_up']);
            $Apellidos = mainModel::limpiar_cadena($_POST['empleado_apellido_up']);
            $INSS = mainModel::limpiar_cadena($_POST['empleado_inss_up']);
            $Cedula = mainModel::limpiar_cadena($_POST['empleado_cedula_up']);
            $Genero = mainModel::limpiar_cadena($_POST['item_genero_up']);
            $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['empleado_nacio_up']);
            $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_up']);
            $Direccion = mainModel::limpiar_cadena($_POST['empleado_direccion_up']);
            $Telefono = mainModel::limpiar_cadena($_POST['empleado_telefono_up']);
            $Email = mainModel::limpiar_cadena($_POST['empleado_correo_up']);
            $Estado = mainModel::limpiar_cadena($_POST['item_estado_up']);
            $cedulaoriginal = mainModel::limpiar_cadena($_POST['cedoriginal_up']);

            /*Comprueba datos vacíos */
            if (
                $Nombres == "" || $Apellidos == "" || $INSS == "" || $Cedula == "" || $Genero == ""
                || $Fecha_de_nacimiento == "" || $Estado_civil == "" || $Direccion == "" || $Telefono == "" || $Email == ""
                || $Estado == ""
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
            /*          VALIDACIONES DE DATOS               aquí tocó luis */
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
            /*-----------------Verificando integridad de los datos -----------------*/

            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,70}", $Nombres)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El nombre no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ1-9 ]{3,70}", $Apellidos)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El apellido no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*          VALIDACIONES DE DATOS               aquí tocó luis */
            /*-----------------Verificando integridad de los datos -----------------*/
            /*-----------------Verificando integridad de cedula -----------------*/
            if (mainModel::verificar_datos("[a-zA-Z0-9- ]{16,16}", $Cedula)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cédula no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de INSS -----------------*/
            if (mainModel::verificar_datos("[0-9-]{9,9}", $INSS)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El codigo INSS no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de Dirección -----------------*/
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
            /*-----------------Verificando integridad de teléfono -----------------*/

            if (mainModel::verificar_datos("[0-9#-+ ]{8,15}", $Telefono)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Telefono no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Comprobando EMAIL-----------------*/
            if ($Email != "") {
                if (filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Email ingresado no es valido",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
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

            /*Se envian datos por actualizar*/

            /*DATOS POR ENVIAR */
            /*Cambios */
            /*AQUÍ SE CAMBIÓ ESTRATEGIA POR OTRA CÉDULA OCULTA PERO PRIMERO SE VALIDARÁ QUE EXISTE */
            /*                  VALIDACIÓN DE CÉDULA ACTUAL              */
            /*-----------------Comprobando Cedula-----------------*/
            $check_cedula = mainModel::ejecutar_consulta_simple("SELECT Cedula FROM tblpersona WHERE Cedula='$cedulaoriginal'");
            if ($check_cedula->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La cedula de este empleado no está registrada en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $datos_persona_up = [
                "Cedula" => $cedulaoriginal
            ];
            $codigoPersona = empleadosModelo::obtener_persona_modelo($datos_persona_up);

            $row2 = $codigoPersona->fetch();/*ti*/
            $codigoPersona = $row2['Codigo'];
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
                "Email" => $Email,
                "INSS" => $INSS,
                "Estado" => $Estado
            ];
            $actualizar_persona = empleadosModelo::actualizar_personayempleado_modelo($datos_persona_up2);
            /*Datos por enviar empleado 
                
                $datos_empleado_up = [
                    "INSS"=>$INSS,
                    "CodPersona"=>$codigoPersona
                ];
                $actualizar_empleado=empleadosModelo::actualizar_empleado_modelo($datos_empleado_up);

                */
            //Actu by Luis
            if ($actualizar_persona->rowCount() > 0) {
                $alerta = [
                    "Alerta" => "redireccion_violenta",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Se han actualizado los datos del empleado",
                    "Tipo" => "success",
                    "URL" => SERVERURL . "empleado-list/"
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró actualizar el empleado, verifique que realizó cambios",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    }/*Fin de controlador */
}
