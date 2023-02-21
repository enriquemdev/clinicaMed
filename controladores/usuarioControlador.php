<?php
if ($peticionAjax) {
    require_once "../modelos/usuarioModelo.php";
} else {
    require_once "./modelos/usuarioModelo.php";
}
class usuarioControlador extends usuarioModelo
{
    /*-----------------Controlador para agregar usuario-----------------*/
    public function agregar_usuario_controlador()
    {
        $usuario = mainModel::limpiar_cadena($_POST['usuario_usuario_reg']);
        $nombrePersona = mainModel::limpiar_cadena($_POST['usuario_empleado_reg']);
        $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
        $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);

        $extensiones = [];
        $extensiones[] = 'image/png';
        $extensiones[] = 'image/jpeg';
        $extensiones[] = 'image/jpg';

        $tamañoMaximo = 100000;

        $imgenTMP = $_FILES['usuario_imagen_reg']['tmp_name'];
        $imgenTYPE = $_FILES['usuario_imagen_reg']['type'];
        $imgenSIZE = $_FILES['usuario_imagen_reg']['size'];

        $ruta = "";

        if (isset($imgenTMP) && !empty($imgenTMP)) {
            if (in_array($imgenTYPE, $extensiones)) {
                if ($imgenSIZE < $tamañoMaximo) {
                    $ruta = 'FotosReferencia/' . time() . '_' . $_FILES['usuario_imagen_reg']['name'];
                    if (move_uploaded_file($imgenTMP, '../' . $ruta)) {
                        //
                    } else {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro subir la imagen seleccionada",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El tamaño de la imagen no es permitido",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La extención de la imagen no es valida",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        if (isset($_POST['cbESTADO_reg'])) {
            $estadoUsuario = mainModel::limpiar_cadena($_POST['cbESTADO_reg']);/*ESTADO ACTIVO*/
        } else {
            $estadoUsuario = 2;/*ESTADO INACTIVO*/
        }

        /*----------------Comprobar campos vacíos -----------------*/

        if ($usuario == "" || $clave1 == "" || $clave2 == "" || $nombrePersona == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*----------------Comprobar campos vacíos -----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreUsuario FROM tblusuarios WHERE NombreUsuario='$usuario'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de usuario ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Verificando integridad de los datos -----------------*/

        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,14}", $usuario)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre de usuario no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Las contraseñas no coiniciden con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*
                if(mainModel::verificar_datos("[0-9]{1,35}",$codempleado)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El codigo de empleado no coinicide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }*/
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/
        /*
                $check_CodE=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$codempleado'");
                if($check_CodE->rowCount()==0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El codigo del empleado no está registrado en el sistema",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }*/


        /*-----------------Comprobando USUARIO (Solo puede existir uno)-----------------*/

        $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreUsuario FROM tblusuarios WHERE NombreUsuario='$usuario'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de usuario ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Claves-----------------*/
        if ($clave1 != $clave2) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Las claves que acaba de ingresar no coinciden",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $clave = mainModel::encryption($clave1);
        }

        //buscador de empleado para textbox

        $parametrosPersona = explode('_', $nombrePersona); //Nombre_Apellido_Cedula


        $datos_Persona = [
            "NombrePersona" => $parametrosPersona[0],
            "ApellidoPersona" => $parametrosPersona[1],
            "CedulaPersona" => $parametrosPersona[2]
        ];


        $CodigoPersona = usuarioModelo::buscarCodPersona($datos_Persona);
        $row2 = $CodigoPersona->fetch(); //ti
        $CodigoPersona = $row2['cod_persona']; //primary key tabla Persona



        /*DATOS POR ENVIAR */
        $datos_usuario_reg = [
            "Usuario" => $usuario,
            "Clave" => $clave,
            "CodigoPersona" => $CodigoPersona,
            "EstadoUsuario" => $estadoUsuario,
            "ImagenUsuario" => $ruta
        ];



        $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);
        if ($agregar_usuario->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Usuario registrado",
                "Texto" => "Usuario registrado correctamente",
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



        /*                        
            /*UPILI*/
        $codUsuarioAnadido = usuarioModelo::obtener_usuario_modelo($datos_usuario_reg);
        $row2 = $codUsuarioAnadido->fetch();/*ti*/

        /*privilegios cita*/
        if (isset($_POST['cita1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 1;
        }

        if (isset($_POST['cita2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 1;
        }

        if (isset($_POST['cita3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 1;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 1;
        }
        /*usuario*/
        if (isset($_POST['usuario1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 2;
        }

        if (isset($_POST['usuario2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 2;
        }

        if (isset($_POST['usuario3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 2;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 2;
        }


        /*empleado*/
        if (isset($_POST['empleado1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 3;
        }

        if (isset($_POST['empleado2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 3;
        }

        if (isset($_POST['empleado3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 3;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 3;
        }

        //Especialidad
        if (isset($_POST['especialidades1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 4;
        }

        if (isset($_POST['especialidades2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 4;
        }

        if (isset($_POST['especialidades3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 4;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 4;
        }

        //Estudio academico empleado
        if (isset($_POST['estudios1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 5;
        }

        if (isset($_POST['estudios2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 5;
        }

        if (isset($_POST['estudios3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 5;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 5;
        }

        //familiares empleado
        if (isset($_POST['familiares1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 6;
        }

        if (isset($_POST['familiares2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 6;
        }

        if (isset($_POST['familiares3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 6;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 6;
        }

        //Historial cargos
        if (isset($_POST['historial1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 7;
        }

        if (isset($_POST['historial2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 7;
        }

        if (isset($_POST['historial3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 7;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 7;
        }

        //paciente
        if (isset($_POST['paciente1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 8;
        }

        if (isset($_POST['paciente2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 8;
        }

        if (isset($_POST['paciente3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 8;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 8;
        }


        //antecedentes
        if (isset($_POST['antecedentes1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 9;
        }

        if (isset($_POST['antecedentes2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 9;
        }

        if (isset($_POST['antecedentes3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 9;
        }

        //fampaciente
        if (isset($_POST['fampaciente1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 10;
        }

        if (isset($_POST['fampaciente2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 10;
        }

        if (isset($_POST['fampaciente3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 10;
        }

        //ocupacion paciente
        if (isset($_POST['ocupacion1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 11;
        }

        if (isset($_POST['ocupacion2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 11;
        }

        if (isset($_POST['ocupacion3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 11;
        }

        //consulta
        if (isset($_POST['soli1'])) { //SOLCITUD CONSULTA
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 22;
        }

        if (isset($_POST['soli2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 22;
        }

        if (isset($_POST['soli3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 22;
            $submodulo[] = 22; //Doble para que pueda ver cuando pueda actualizar
        }

        if (isset($_POST['consulta1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 12;
        }

        if (isset($_POST['consulta2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 12;
        }

        if (isset($_POST['consulta3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 12;
            $submodulo[] = 12; //Doble para que pueda ver cuando pueda actualizar
        }

        //signos vitales
        if (isset($_POST['signos1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 13;
        }

        if (isset($_POST['signos2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 13;
        }

        if (isset($_POST['signos3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 13;
            $submodulo[] = 13;
        }

        //Diagnostico
        if (isset($_POST['diagnostico1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 14;
        }

        if (isset($_POST['diagnostico2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 14;
        }

        if (isset($_POST['diagnostico3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 14;
            $submodulo[] = 14;
        }

        //Receta Médica
        if (isset($_POST['recmed1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 15;
        }

        if (isset($_POST['recmed2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 15;
        }

        if (isset($_POST['recmed3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 15;
            $submodulo[] = 15;
        }

        //Receta Exam
        if (isset($_POST['recex1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 16;
        }

        if (isset($_POST['recex2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 16;
        }

        if (isset($_POST['recex3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 16;
            $submodulo[] = 16;
        }

        //Constancia
        if (isset($_POST['constancia1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 17;
        }

        if (isset($_POST['constancia2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 17;
        }

        if (isset($_POST['constancia3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 17;
            $submodulo[] = 17;
        }

        //exam
        if (isset($_POST['exam1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 18;
        }

        if (isset($_POST['exam2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 18;
        }

        if (isset($_POST['exam3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 18;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 18;
        }

        //Resultados
        if (isset($_POST['result1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 19;
        }

        if (isset($_POST['result2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 19;
        }

        if (isset($_POST['result3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 19;
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 19;
        }

        //Maquinaria
        if (isset($_POST['maq1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 20;
        }

        if (isset($_POST['maq2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 20;
        }

        if (isset($_POST['maq3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 20;
        }

        //Catalogos
        if (isset($_POST['cat1'])) {
            $privilegios[] = 2; //privilegio ver
            $submodulo[] = 21;
        }

        if (isset($_POST['cat2'])) {
            $privilegios[] = 1; //privilegio agregar
            $submodulo[] = 21;
        }

        if (isset($_POST['cat3'])) {
            $privilegios[] = 3; //privilegio actualizar
            $submodulo[] = 21;
        }

        /*Caja
            if(isset($_POST['cbPrivCaja_reg'])){//Si el cb de admin fue marcado
                $privilegios[]=$_POST['cbPrivCaja_reg'];//añadir el privilegio admin al array

                if(isset($_POST['cbEditCaja_reg'])){//y si el cb de edicion fue marcado
                    $pEdicion[]= $_POST['cbEditCaja_reg'];//añadir su valor al array
                }else{
                    $pEdicion[]= 2;//si no, añadir al array que no es editable
                }           
            } */


        if (empty($privilegios)) {
        } else {
            $N = count($privilegios);

            for ($i = 0; $i < $N; $i++) {
                $datos_privilegio_reg = [
                    "CodUsuario" => $row2['Codigo'],
                    "CodPrivilegio" => $privilegios[$i],
                    "CodigoSubModulo" => $submodulo[$i]
                ];
                $agregar_privilegio = usuarioModelo::agregar_privilegio_modelo($datos_privilegio_reg);
            }
        }

        /*funcional
            foreach($_POST['cbprivilegio_reg'] as $selecc)
                                {
                                $datos_privilegio_reg = ["CodUsuario"=>$row2['Codigo'],
                                "CodPrivilegio"=>$selecc,
                                "PermisoEdicion"=>1];
                                $agregar_privilegio=usuarioModelo::agregar_privilegio_modelo($datos_privilegio_reg);

                                }
                */

        /*
                $privilegios = $_POST['cbprivilegio_reg[]'];
                if(empty($privilegios)) 
            {
                
            } 
            else 
            {
                $N = count($privilegios);

                print("You selected $N privilegio(s): ");
                for($i=0; $i < $N; $i++)
                {
                $privilegios[$i]=mainModel::limpiar_cadena($_POST['cbprivilegio_reg[]']); 
                }
            }*/
    } /*En esta llave termina el controlador */
    /*Ti */

    /*-----------------Controlador para paginar usuario-----------------*/
    public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $condRadio2)
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

        if (isset($busqueda) && $busqueda != "" || $condRadio2 != "") {

            $consulta = "SELECT SQL_CALC_FOUND_ROWS *,tblusuarios.Codigo as codigousuario , catcargos.Nombre as cargoasignado 
                FROM tblusuarios INNER JOIN catestado ON tblusuarios.Estado = catestado.ID
                INNER JOIN tblpersona ON tblusuarios.CodPersonaU = tblpersona.Codigo
                INNER JOIN tblempleado ON tblpersona.Codigo = tblempleado.CodPersona
                LEFT JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
                LEFT JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID
                WHERE tblusuarios.Codigo!='$id' AND tblusuarios.Codigo!='1' AND tblhistorialcargos.Estado=1
                AND ((tblusuarios.Codigo LIKE '$busqueda')
                OR (tblusuarios.NombreUsuario LIKE '%$busqueda%')
                OR (tblpersona.Nombres LIKE '%$busqueda%')
                OR (tblpersona.Apellidos LIKE '%$busqueda%')) ";

            if ($_SESSION['condRadio2'] != "") {
                $consulta = $consulta . "AND (tblusuarios.Estado = " . $_SESSION['condRadio2'] . ") ";
            }

            $consulta = $consulta . "ORDER BY NombreUsuario DESC LIMIT $inicio,$registros";
        } else {
            /*
                $consulta="SELECT SQL_CALC_FOUND_ROWS * FROM tblusuarios INNER JOIN catestado ON tblusuarios.Estado = catestado.ID  WHERE Codigo!='$id' 
                AND Codigo!='1' ORDER BY NombreUsuario  DESC LIMIT $inicio,$registros";*/
            $consulta = "SELECT SQL_CALC_FOUND_ROWS *,tblusuarios.Codigo as codigousuario , catcargos.Nombre as cargoasignado FROM tblusuarios INNER JOIN catestado ON tblusuarios.Estado = catestado.ID
                INNER JOIN tblpersona ON tblusuarios.CodPersonaU = tblpersona.Codigo
                INNER JOIN tblempleado ON tblpersona.Codigo = tblempleado.CodPersona
                LEFT JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
                LEFT JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID
                WHERE tblusuarios.Codigo!='$id' AND tblusuarios.Codigo!='1' AND tblhistorialcargos.Estado=1 ORDER BY codigousuario  DESC LIMIT $inicio,$registros ";
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
                                    <th>ID</th>
                                    <th>USUARIO</th>
                                    <th>NOMBRE</th>
                                    <th>CARGO</th>
                                    <th>ESTADO</th>
                                    <th>ACTUALIZAR</th>
                                </tr>
                            </thead>
                            <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                if ($rows['NombreEstado'] == "Inactivo") {
                    $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['codigousuario'] . '</td>
                                <td>' . $rows['NombreUsuario'] . '</td>
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['cargoasignado'] . '</td>
                                <td><span class="badge badge-dark">' . $rows['NombreEstado'] . '</span></td>
                                <td>
                                    <a href="' . SERVERURL . 'user-update/' . mainModel::encryption($rows['codigousuario']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>
                        </tr>
                    ';
                    $contador++;/* ) */
                } else if ($rows['NombreEstado'] == "Activo") {
                    $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['codigousuario'] . '</td>
                                <td>' . $rows['NombreUsuario'] . '</td>
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['cargoasignado'] . '</td>
                                <td><span class="badge badge-primary">' . $rows['NombreEstado'] . '</span></td>
                                <td>
                                    <a href="' . SERVERURL . 'user-update/' . mainModel::encryption($rows['codigousuario']) . '" class="btn btn-success">
                                        <i class="fas fa-sync-alt"></i>	
                                    </a>
                                </td>
                        </tr>
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
        return $tabla;
    }
    /*-----------------Controlador datos usuario-----------------*/
    public function datos_usuario_controlador($tipo, $id)
    {
        $tipo = mainModel::limpiar_cadena($tipo);
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return usuarioModelo::datos_usuario_modelo($tipo, $id);
    }/*Fin de controlador */

    public function actualizar_usuario_controlador()
    {
        /*Recibe Codigo */
        $codigo = mainModel::decryption($_POST['usuario_codigo_up']);
        $codigo = mainModel::limpiar_cadena($codigo);
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM tblusuarios WHERE Codigo='$codigo'");
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
            $nombre = mainModel::limpiar_cadena($_POST['usuario_usuario_up']);
            $clave1 = mainModel::limpiar_cadena($_POST['usuario_clave_1_reg']);
            $clave2 = mainModel::limpiar_cadena($_POST['usuario_clave_2_reg']);

            /*Comprueba datos vacíos */
            if ($nombre == "") {
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
            /* if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}", $nombre)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El nombre de usuario no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            } */

            /*-----------------Comprobando USUARIO (Solo puede existir uno)-----------------*/
            if ($nombre != $campos['NombreUsuario']) {
                $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreUsuario FROM tblusuarios WHERE NombreUsuario='$nombre'");
                if ($check_user->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Nombre de usuario ingresado ya está registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            /*-----------------Comprobando Claves-----------------*/
            if ($_POST['usuario_clave_1_reg'] != "" || $_POST['usuario_clave_2_reg'] != "") {
                if ($clave1 != $clave2) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Las claves que acaba de ingresar no coinciden",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                } else {
                    if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "Las contraseñas nuevas no coiniciden con el formato solicitado",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $clave = mainModel::encryption($clave1);
                }
            } else {
                $clave = $campos['Pass'];
            }

            /*Foto loco*/
            $extensiones = [];
            $extensiones[] = 'image/png';
            $extensiones[] = 'image/jpeg';
            $extensiones[] = 'image/jpg';

            $subirImagen = false;

            $tamañoMaximo = 300000;

            $imgenTMP = $_FILES['usuario_imagen_editar_reg']['tmp_name'];
            $imgenTYPE = $_FILES['usuario_imagen_editar_reg']['type'];
            $imgenSIZE = $_FILES['usuario_imagen_editar_reg']['size'];

            $ruta = "";

            if (isset($imgenTMP) && !empty($imgenTMP)) {
                if (in_array($imgenTYPE, $extensiones)) {
                    if ($imgenSIZE < $tamañoMaximo) {
                        $ruta = 'FotosReferencia/' . time() . '_' . $_FILES['usuario_imagen_editar_reg']['name'];
                        if (move_uploaded_file($imgenTMP, '../' . $ruta)) {
                            $subirImagen = true;
                        } else {
                            $alerta = [
                                "Alerta" => "simple",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "No se logro subir la imagen seleccionada",
                                "Tipo" => "error"
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                    } else {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "El tamaño de la imagen no es permitido",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La extención de la imagen no es valida",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }

            if (isset($_POST['cbESTADO_reg'])) {
                $estado = mainModel::limpiar_cadena($_POST['cbESTADO_reg']);
                $estado = 1;
            } else {
                $estado = 2;
            }

            if ($subirImagen) {
                $sinImagen = false;
                /*-----------------Recuperando ruta imagen anterior-----------------*/
                $ConsultaImagenAnterior = mainModel::ejecutar_consulta_simple("SELECT imgUsuario FROM tblusuarios WHERE NombreUsuario='$nombre'");
                if ($ConsultaImagenAnterior->rowCount() <= 0) {
                    $sinImagen = true;
                } else {
                    $ConsultaImagenAnterior = $ConsultaImagenAnterior->fetch();
                    $rutaImegebAnterior = $ConsultaImagenAnterior['imgUsuario'];
                }
            }

            /*Se envian datos por actualizar */

            /*DATOS POR ENVIAR */
            $datos_usuario_up = [
                "Nombre" => $nombre,
                "Contra" => $clave,
                "Estado" => $estado,
                "Cod" => $codigo,
                "ImagenUsuario" => $ruta
            ];

            if (usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)) {
                if ($subirImagen) {
                    if (!$sinImagen) {
                        unlink('../' . $rutaImegebAnterior);
                    }
                } else {
                    if (isset($imgenTMP) && !empty($imgenTMP)) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No fue posible actualizar la imagen",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                }

                $alerta = [
                    "Alerta" => "redireccion_violenta",
                    "Titulo" => "Datos actualizados",
                    "Texto" => "Los datos han sido actualizados con exito",
                    "Tipo" => "success",
                    "URL" => SERVERURL . "user-list/"
                ];
                echo json_encode($alerta);
            } else {
                if ($subirImagen) {
                    unlink('../' . $ruta);
                    //move_uploaded_file($imgenTMP, '../'.$ruta);
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No fue posible remover la imagen anterior",
                        "Tipo" => "error"
                    ];

                    echo json_encode($alerta);
                    exit();
                }

                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logro actualizar los datos",
                    "Tipo" => "error"
                ];

                echo json_encode($alerta);
                exit();
            }
        }
    }/*Fin de controlador */
}
