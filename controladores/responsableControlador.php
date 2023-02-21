<?php
if ($peticionAjax) {
    require_once "../modelos/familiarModelo.php";
} else {
    require_once "./modelos/familiarModelo.php";
}
class responsableControlador extends familiarModelo
{ //Se creó una nueva clase y controlador, pero se mantiene el mismo modelo

    public function datos_item3_controlador()
    {
        return familiarmodelo::datos_item3_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar cargo-----------------*/
    /*Tabla Persona */
    public function agregar_responsable_controlador()
    {
        $Cedula = mainModel::limpiar_cadena($_POST['cedula_reg']);
        $Nombres = mainModel::limpiar_cadena($_POST['nombre_familiar_reg']);
        $Apellidos = mainModel::limpiar_cadena($_POST['apellido_familiar_reg']);
        $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['familiar_fecha_reg']);
        $Genero = mainModel::limpiar_cadena($_POST['item_genero_reg']);
        $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_reg']);
        $Direccion = mainModel::limpiar_cadena($_POST['direccion_fam_reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['familiar_telefono_reg']);
        //Tutor = 1 ya que siempre será tutor en este caso, al ser tutor de niño
        $Tutor = 1;


        /*----------------Comprobar campos vacíos -----------------*/

        if (
            $Cedula == "" || $Nombres == "" || $Apellidos == "" || $Fecha_de_nacimiento == "" || $Genero == "" ||
            $Estado_civil == "" ||  $Telefono == ""  || $Tutor == "" || $Direccion == ""
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
        /*Datos por enviar familiar */
        $obtener_codigo_persona2 = familiarModelo::obtener_codigo2(0); //función para aumentar el código de persona
        $codigoPersona = familiarModelo::obtener_persona_modelo($datos_persona_reg);
        $row2 = $codigoPersona->fetch();/*ti*/
        $codigoPersona = $row2['Codigo'];
        $ID = $codigoPersona * 5;
        $datos_familiar = [
            "ID" => $ID,
            "CodPersona" => $codigoPersona,
            "ContactoEmergencia" => $Telefono,

        ];

        $agregar_tutor = familiarModelo::agregar_familiar_modelo($datos_familiar);

        if ($agregar_persona->rowCount() == 1 && $agregar_tutor->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Responsable registrado",
                "Texto" => "Responsable registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el Responsable",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador
    public function paginador_responsable_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    { //Función para paginar responsables
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
                INNER JOIN catparentesco ON tblfamiliares.parentesco = catparentesco.ID
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
                ORDER BY tblfamiliares.ID DESC LIMIT $inicio,$registros";
        }



        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 5);
        $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                            <tr class="text-center roboto-medium">
                            <th>ID DE FAMILIAR</th>
                            <th>NOMBRES</th>
                            <th>APELLIDOS</th>
                            <th>CONTACTO</th>
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
                                    <td>' . $rows['Apellidos'] . '</td>';
                if ($rows['Telefono'] != null) {
                    $tabla .= '<td>' . $rows['Telefono'] . '</td>';
                } else {
                    $tabla .= '<td>' . $rows['Email'] . '</td>';
                }
                $tabla .= '</tr>';
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

}
