<?php
if ($peticionAjax) {
    require_once "../modelos/catalogosModelo.php";
} else {
    require_once "./modelos/catalogosModelo.php";
}
class catalogosControlador extends catalogosModelo
{
    /*-----------------Controlador para agregar cargo-----------------*/
    public function agregar_cargo_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['CARGO_NOMBRE_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['DESCRIPCION_NOMBRE_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Descripcion == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9]{4,40}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del cargo no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{10,100}", $Descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La descripción del cargo no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM catcargos WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del cargo ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_cargo_reg = [
            "Nombre" => $Nombre,
            "Descripcion" => $Descripcion
        ];


        $agregar_cargo = catalogosModelo::agregar_cargo_modelo($datos_cargo_reg);
        if ($agregar_cargo->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cargo registrado",
                "Texto" => "Cargo registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_cargo_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        if (isset($busqueda) && $busqueda != "") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catcargos WHERE ((Nombre LIKE '%$busqueda' OR Descripcion LIKE '%$busqueda')) ORDER BY Nombre ASC LIMIT $inicio,$registros";
        } else {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catcargos ORDER BY Nombre ASC LIMIT $inicio,$registros";
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
                                <th>ID</th>
                                <th>NOMBRE CARGO</th>
                                <th>DESCRIPCIÓN CARGO</th>
                                <th>FECHA DE REGISTRO</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $contador . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
                                <td>' . explode(" ", $rows['FechaRegistro'])[0] . '</td>
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
    public function agregar_proveedor_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['PROEEDOR_NOMBRE_reg']);
        $telefono = mainModel::limpiar_cadena($_POST['PROVEEDOR_TELEFONO_reg']);
        $direccion = mainModel::limpiar_cadena($_POST['PROVEEDOR_DIRECCION_reg']);
        $email = mainModel::limpiar_cadena($_POST['PROVEEDOR_EMAIL_reg']);
        $estado = mainModel::limpiar_cadena($_POST['PROVEEDOR_ESTADO_reg']);
        $ranking = mainModel::limpiar_cadena($_POST['PROVEEDOR_RANKING_reg']);
        $tiempo = mainModel::limpiar_cadena($_POST['PROVEEDOR_TIEMPO_reg']);
        $desc = mainModel::limpiar_cadena($_POST['PROVEEDOR_DESC_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $telefono == "" || $estado == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9]{4,40}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del proveedor no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT nombreProveedor FROM tblproveedores WHERE nombreProveedor='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del proveedor ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_proveedor_reg = [
            "nombreProveedor" => $Nombre,
            "telefonoProveedor" => $telefono,
            "direccionProveedor" => $direccion,
            "emailProveedor" => $email,
            "ranking" => $ranking,
            "tiempoEntrega" => $tiempo,
            "estadoProveedor" => $estado,
            "descripcionProveedor" => $desc
        ];


        $agregar_proveedor = catalogosModelo::agregar_proveedor_modelo($datos_proveedor_reg);
        if ($agregar_proveedor->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Proveedor registrado",
                "Texto" => "Proveedor registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_proveedor_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        if (isset($busqueda) && $busqueda != "") {
            $consulta = "SELECT a.idProveedor,a.nombreProveedor,a.telefonoProveedor,a.direccionProveedor,a.emailProveedor,a.ranking,a.tiempoEntrega,a.estadoProveedor,a.descripcionProveedor FROM tblproveedores as a ORDER BY idProveedor DESC  LIMIT $inicio,$registros";
        } else {
            $consulta = "SELECT a.idProveedor,a.nombreProveedor,a.telefonoProveedor,a.direccionProveedor,a.emailProveedor,a.ranking,a.tiempoEntrega,a.estadoProveedor,a.descripcionProveedor FROM tblproveedores as a ORDER BY idProveedor DESC  LIMIT $inicio,$registros";
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
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>TELEFONO</th>
                                <th>DIRECCIÓN</th>
                                <th>EMAIL</th>
                                <th>ESTADO</th>
                                <th>RANKING</th>
                                <th>LEAD TIME/DIAS</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['idProveedor'] . '</td>
                                <td>' . $rows['nombreProveedor'] . '</td>
                                <td>' . $rows['telefonoProveedor'] . '</td>
                                <td>' . $rows['direccionProveedor'] . '</td>
                                <td>' . $rows['emailProveedor'] . '</td>';
                if ($rows['estadoProveedor'] == 1) {
                    $tabla .= '<td><span class="badge badge-success">Activo</span></td>
                                    <td>' . $rows['ranking'] . '</td>
                                    <td>' . $rows['tiempoEntrega'] . '</td>';
                } else if ($rows['estadoProveedor'] == 2) {
                    $tabla .= '<td><span class="badge badge-dark">Inactivo</span></td>
                                    <td>' . $rows['ranking'] . '</td>
                                    <td>' . $rows['tiempoEntrega'] . '</td>';
                } else {
                    $tabla .= '<td><span class="badge badge-dark">WTF</span></td>
                                    <td>' . $rows['ranking'] . '</td>
                                    <td>' . $rows['tiempoEntrega'] . '</td>';
                }
                ';
                                
                                
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
    /*-----------------Controlador para agregar consultorio-----------------*/
    public function agregar_consultorio_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['CONSULTORIO-NOMBRE_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['CONSULTORIO-DESCRIPCION_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Descripcion == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,40}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del consultorio no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{10,100}", $Descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La descripción del consultorio no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM catconsultorio WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del cargo ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_consultorio_reg = [
            "Nombre" => $Nombre,
            "Descripcion" => $Descripcion
        ];


        $agregar_consultorio = catalogosModelo::agregar_consultorio_modelo($datos_consultorio_reg);
        if ($agregar_consultorio->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Consultorio registrado",
                "Texto" => "Consultorio registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_consultorio_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catconsultorio ORDER BY Nombre ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>Nombre CONSULTORIO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>FECHA DE REGISTRO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $contador . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
                                <td>' . $rows['FechaRegistro'] . '</td>
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
    /*-----------------Controlador para agregar enfermedad-----------------*/
    public function agregar_enfermedad_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['ENFERMEDAD-NOMBRE_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['ENFERMEDAD-DESCRIPCION_reg']);
        $Tipo = mainModel::limpiar_cadena($_POST['ENFERMEDAD_TIPO_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Descripcion == "" || $Tipo == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre de la enfermedad no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{10,150}", $Descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La descripción de la enfermedad no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreEnfermedad FROM catenfermedades WHERE NombreEnfermedad='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de la enfermedad ingresada ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_enfermedad_reg = [
            "Nombre" => $Nombre,
            "Descripcion" => $Descripcion,
            "TipoEnfermedad" => $Tipo
        ];


        $agregar_enfermedad = catalogosModelo::agregar_enfermedades_modelo($datos_enfermedad_reg);
        if ($agregar_enfermedad->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Enfermedad registrada",
                "Texto" => "Enfermedad registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_enfermedad_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catenfermedades ORDER BY NombreEnfermedad ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>ENFERMEDAD</th>
                            <th>TIPO</th>
                            <th>DESCRIPCIÓN</th>
                            <th>FECHA DE REGISTRO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['NombreEnfermedad'] . '</td>
                                <td>' . $rows['TipoEnfermedad'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
                                <td>' . explode(" ", $rows['FechaRegistro'])[0] . '</td>
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
    /*-----------------Controlador para agregar especialidades-----------------*/
    public function agregar_especialidad_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['ESPECIALIDAD-NOMBRE_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['ESPECIALIDAD-DESCRIPCION_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Descripcion == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{5,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre de la especialidad no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{10,150}", $Descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La descripción de la especialidad no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreEnfermedad FROM catenfermedades WHERE NombreEnfermedad='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de la especialidad ingresada ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_especialidad_reg = [
            "Nombre" => $Nombre,
            "Descripcion" => $Descripcion
        ];


        $agregar_especialidad = catalogosModelo::agregar_especialidad_modelo($datos_especialidad_reg);
        if ($agregar_especialidad->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Especialidad registrada",
                "Texto" => "Especialidad registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_especialidad_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catespecialidades ORDER BY Nombre ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE ESPECIALIDAD</th>
                            <th>DESCRIPCIÓN</th>
                            <th>FECHA DE REGISTRO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
                                <td>' . $rows['FechaRegistro'] . '</td>
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
    /*-----------------Controlador para agregar estados-----------------*/
    public function agregar_estado_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['ESTADO_NOMBRE_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT NombreEstado FROM catestado WHERE NombreEstado='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del estado ingresada ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_estado_reg = [
            "Nombre" => $Nombre
        ];


        $agregar_estado = catalogosModelo::agregar_estado_modelo($datos_estado_reg);
        if ($agregar_estado->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Estado registrada",
                "Texto" => "Estado registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_estado_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catestado ORDER BY NombreEstado ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['NombreEstado'] . '</td>
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
    /*-----------------Controlador para agregar estado cita-----------------*/
    public function agregar_estadocita_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['ESTADO-CITA-NOMBRE_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM catestadocita WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del estado ingresada ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_estadocita_reg = [
            "Nombre" => $Nombre
        ];


        $agregar_estadocita = catalogosModelo::agregar_estadocita_modelo($datos_estadocita_reg);
        if ($agregar_estadocita->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Estado registrado",
                "Texto" => "Estado registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_estadocita_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catestadocita ORDER BY Nombre ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE ESTADO CITA</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
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
    /*-----------------Controlador para agregar estado consulta-----------------*/
    public function agregar_estadoconsulta_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['ESTADO-CONSULTA_NOMBRE_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT Nombre FROM catestadoconsulta WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del estado ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_estadoconsulta_reg = [
            "Nombre" => $Nombre
        ];


        $agregar_estadoconsulta = catalogosModelo::agregar_estadoconsulta_modelo($datos_estadoconsulta_reg);
        if ($agregar_estadoconsulta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Estado registrado",
                "Texto" => "Estado registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_estadoconsulta_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catestadoconsulta ORDER BY ID ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE ESTADO CONSULTA</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
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
    /*-----------------Controlador para agregar examen medico-----------------*/
    public function agregar_examen_medico_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['EXAMEN_MEDICO-NOMBRE_reg']);
        $Precio = mainModel::limpiar_cadena($_POST['EXAMEN_MEDICO-PRECIO_reg']);



        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Precio == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM catexamenesmedicos WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del examen ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_examenmedico_reg = [
            "Nombre" => $Nombre,
            "Precio" => $Precio
        ];


        $agregar_examenmedico = catalogosModelo::agregar_examen_medico_modelo($datos_examenmedico_reg);
        if ($agregar_examenmedico->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Examen medico registrado",
                "Texto" => "Examen medico registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_examen_medico_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catexamenesmedicos ORDER BY Nombre ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['Precio'] . '</td>
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
    /*-----------------Controlador para agregar grupo sanguíneo-----------------*/
    public function agregar_grupo_sanguineo_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['GRUPO-SANGUINEO-NOMBRE_reg']);



        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM catgruposanguineo WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del grupo sanguíneo ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_gruposangui_reg = [
            "Nombre" => $Nombre
        ];


        $agregar_gruposanguineo = catalogosModelo::agregar_grupo_sanguineo_modelo($datos_gruposangui_reg);
        if ($agregar_gruposanguineo->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Examen medico registrado",
                "Texto" => "Examen medico registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_grupo_sanguineo_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catgruposanguineo  LIMIT $inicio,$registros";
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
                        <th>ID</th>
                        <th>Nombre GRUPO SANGUINEO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
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
    /*-----------------Controlador para agregar maquinaria-----------------*/
    public function agregar_maquinaria_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['MAQUINARIA-NOMBRE_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['DESCRIPCION-NOMBRE_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" && $Descripcion == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del estado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------
            $check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM catgruposanguineo WHERE Nombre='$Nombre'");
            if($check_user->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El Nombre del grupo sanguíneo ingresado ya está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            */
        /*Datos por enviar */
        $datos_maquinaria_reg = [
            "NombreMaquinaria" => $Nombre,
            "Descripcion" => $Nombre
        ];


        $agregar_maquinaria = catalogosModelo::agregar_maquinaria_modelo($datos_maquinaria_reg);
        if ($agregar_maquinaria->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Maquinaria registrada",
                "Texto" => "Maquinaria registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_maquinaria_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catmaquinaria  LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['NombreMaquinaria'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
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
    /*-----------------Controlador para agregar medicamento medico-----------------*/
    public function agregar_medicamento_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['MEDICAMENTO-NOMBRE_COMERCIAL_reg']);
        $NombreGenerico = mainModel::limpiar_cadena($_POST['MEDICAMENTO-NOMBRE_GENERICO_reg']);
        $Formula = mainModel::limpiar_cadena($_POST['MEDICAMENTO-FORMULA_reg']);
        $Presentacion = mainModel::limpiar_cadena($_POST['MEDICAMENTO-PRESENTACION_reg']);
        $desc = mainModel::limpiar_cadena($_POST['MED_DESC_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $NombreGenerico == "" || $Formula == "" || $Presentacion == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del medicamento no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $NombreGenerico)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del medicamento no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Formula)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La fórmula del medicamento no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,50}", $Presentacion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La presentación del medicamento no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------
            $check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM catexamenesmedicos WHERE Nombre='$Nombre'");
            if($check_user->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El Nombre del medicamento ingresado ya está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        /*Datos por enviar */
        $datos_medicamentos_reg = [
            "NombreComercial" => $Nombre,
            "NombreGenerico" => $NombreGenerico,
            "Formula" => $Formula,
            "Presentacion" => $Presentacion,
            "Descripcion" => $desc
        ];


        $agregar_medicamento = catalogosModelo::agregar_medicamento_modelo($datos_medicamentos_reg);
        if ($agregar_medicamento->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Medicamento registrado",
                "Texto" => "Medicamento registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_medicamento_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT a.Codigo,a.nombreComercial,a.nombreGenerico,a.formula,a.presentacion,a.descripcionMedicamento from catmedicamentos as a
        ORDER BY a.Codigo ASC LIMIT $inicio,$registros";
        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 10);
        $tabla .= '
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>#</th>
                        <th>NOMBRE COMERCIAL</th>
                        <th>NOMBRE GENERICO</th>
                        <th>FORMULA</th>
                        <th>PRESENTACION</th>
                        <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['Codigo'] . '</td>
                                <td>' . $rows['nombreComercial'] . '</td>
                                <td>' . $rows['nombreGenerico'] . '</td>
                                <td>' . $rows['formula'] . '</td>
                                <td>' . $rows['presentacion'] . '</td>
                                <td>' . $rows['descripcionMedicamento'] . '</td>
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
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 10);
        }
        return $tabla;
    }
    /*-----------------Controlador para metodos de pago-----------------*/
    public function agregar_metodo_de_pago_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['nombre_metodoPago_reg']);
        $desc = mainModel::limpiar_cadena($_POST['descripcion_metodoPago_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $desc == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,255}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre método de pago no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //-----------------Comprobando Nombre (Solo puede existir uno)-----------------
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM catmetodosdepago WHERE NombreMetodoPago='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del método de pago ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_metodo_reg = [
            "Nombre" => $Nombre,
            "desc" => $desc
        ];


        $agregar_metodo = catalogosModelo::agregar_metodo_de_pago_modelo($datos_metodo_reg);
        if ($agregar_metodo->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Metodo de pago registrado",
                "Texto" => "Metodo de pago registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_metodo_de_pago_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catmetodosdepago  LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE</th>
                            <th>DESCRIPCION</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['idMetodoPago'] . '</td>
                                <td>' . $rows['NombreMetodoPago'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
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
    /*-----------------Controlador para metodos de pago-----------------*/
    public function agregar_nivel_academico_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['NIVEL-ACADEMICO-NOMBRE_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre método de pago no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM catgruposanguineo WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre del nivel académico ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_nivel_reg = [
            "NombreNivelAcademico" => $Nombre
        ];


        $agregar_nivel = catalogosModelo::agregar_nivel_academico_modelo($datos_nivel_reg);
        if ($agregar_nivel->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Nivel académico registrado",
                "Texto" => "Nivel académico registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_nivel_academico_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catnivelacademico  LIMIT $inicio,$registros";
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
                        <th>ID</th>
                        <th>Nombre NIVEL ACADEMICO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['NombreNivelAcademico'] . '</td>
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
    /*-----------------Controlador para metodos de pago-----------------*/
    public function agregar_parentesco_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['PARESTENCO-NOMBRE_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_user = mainModel::ejecutar_consulta_simple("SELECT * FROM catparentesco WHERE Nombre='$Nombre'");
        if ($check_user->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de parentesco ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_parentesco_reg = [
            "Nombre" => $Nombre
        ];


        $agregar_parentesco = catalogosModelo::agregar_parentesco_modelo($datos_parentesco_reg);
        if ($agregar_parentesco->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Nombre de parentesco registrado",
                "Texto" => "Nombre de parentesco registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_parentesco_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catparentesco  LIMIT $inicio,$registros";
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
                        <th>ID</th>
                        <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
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
    /*-----------------Controlador para sala examenes-----------------*/
    public function agregar_sala_examen_controlador()
    {
        $Nombre = mainModel::limpiar_cadena($_POST['SALA_DE_EXAMEN-NOMBRE_reg']);
        $Dimensiones = mainModel::limpiar_cadena($_POST['CONSULTORIO-DIMENSIONES_reg']);

        /*----------------Comprobar campos vacíos -----------------*/

        if ($Nombre == "" || $Dimensiones == "") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\-+ ]{1,50}", $Nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre método de pago no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------
            $check_user=mainModel::ejecutar_consulta_simple("SELECT * FROM catgruposanguineo WHERE Nombre='$Nombre'");
            if($check_user->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El Nombre del nivel académico ingresado ya está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        /*Datos por enviar */
        $datos_sala_examen_reg = [
            "Nombre" => $Nombre,
            "Dimensiones" => $Dimensiones
        ];


        $agregar_sala = catalogosModelo::agregar_sala_examen_modelo($datos_sala_examen_reg);
        if ($agregar_sala->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Sala Examen registrada",
                "Texto" => "Sala Examen registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_sala_examen_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catsalaexamen  LIMIT $inicio,$registros";
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
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Dimensiones</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['ID'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['Dimensiones'] . '</td>
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

    public function agregar_moneda_controlador()
    {
        $nombre = mainModel::limpiar_cadena($_POST['nombre_moneda_reg']);
        $simbolo = mainModel::limpiar_cadena($_POST['simbolo_moneda_reg']);
        $descripcion = mainModel::limpiar_cadena($_POST['descripcion_moneda_reg']);


        /*----------------Comprobar campos vacíos -----------------*/

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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,60}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del consultorio no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_nombre = mainModel::ejecutar_consulta_simple("SELECT nombreMoneda FROM catmoneda WHERE nombreMoneda='$nombre'");
        if ($check_nombre->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de la moneda ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_moneda_reg = [
            "nombre" => $nombre,
            "simbolo" => $simbolo,
            "descripcion" => $descripcion,
            "referencia" => 2
        ];


        $agregar_moneda = catalogosModelo::agregar_moneda_modelo($datos_moneda_reg);
        if ($agregar_moneda->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Moneda registrado",
                "Texto" => "Moneda registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }
    public function paginador_monedas_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catmoneda ORDER BY EsReferencia ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>SIMBOLO</th>
                            <th>NOMBRE </th>
                            <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['idMoneda'] . '</td>
                                <td>' . $rows['simbolo'] . '</td>
                                <td>' . $rows['nombreMoneda'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
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

    /*-----------------Controlador para agregar consultorio-----------------*/
    public function agregar_caja_controlador()
    {
        $nombre = mainModel::limpiar_cadena($_POST['nombre_caja_reg']);
        $descripcion = mainModel::limpiar_cadena($_POST['descripcion_caja_reg']);

        /*----------------Comprobar campos vacíos -----------------*/
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{3,60}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre del consultorio no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando Nombre (Solo puede existir uno)-----------------*/
        $check_nombre = mainModel::ejecutar_consulta_simple("SELECT nombreCaja FROM catcaja WHERE nombreCaja='$nombre'");
        if ($check_nombre->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Nombre de la caja ingresado ya está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Datos por enviar */
        $datos_caja_reg = [
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "EstadoCaja" => 2
        ];


        $agregar_caja = catalogosModelo::agregar_caja_modelo($datos_caja_reg);
        if ($agregar_caja->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Caja registrado",
                "Texto" => "Caja registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        }
    }

    public function paginador_caja_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM catcaja ORDER BY idCaja ASC LIMIT $inicio,$registros";
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
                            <th>ID</th>
                            <th>NOMBRE </th>
                            <th>DESCRIPCIÓN</th>
                            <th>ESTADO CAJA</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['idCaja'] . '</td>
                                <td>' . $rows['nombreCaja'] . '</td>
                                <td>' . $rows['Descripcion'] . '</td>
                                <td>' . $rows['EstadoCaja'] . '</td>
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
}
