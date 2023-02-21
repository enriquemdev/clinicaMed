<?php
if ($peticionAjax) {
    require_once "../modelos/especialidadModelo.php";
} else {
    require_once "./modelos/especialidadModelo.php";
}
class especialidadControlador extends especialidadModelo
{
    public function datos_item1_controlador()
    {
        return especialidadModelo::datos_item1_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar receta-----------------*/
    public function asignar_especialidad_controlador()
    {
        $codigo_doc_especialidad_reg = mainModel::limpiar_cadena(explode("__", $_POST['codigo_doc_especialidad_reg'])[1]);
        $especialidad_reg = mainModel::limpiar_cadena($_POST['especialidad_reg']);
        /*----------------Comprobar campos vacíos -----------------*/

        if ($codigo_doc_especialidad_reg == "" || $especialidad_reg == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de codigo -----------------*/
        if (mainModel::verificar_datos("[0-9]{1,11}", $codigo_doc_especialidad_reg)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El código de empleado no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/

        $check_CodE = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$codigo_doc_especialidad_reg'");
        if ($check_CodE->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo del empleado no está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $check_CodP = mainModel::ejecutar_consulta_simple("SELECT ID FROM catespecialidades WHERE ID='$especialidad_reg'");
        if ($check_CodP->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de la especialidad no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }


        /*Datos por enviar receta */
        $datos_especialidad_reg = [
            "CodDoctor" => $codigo_doc_especialidad_reg,
            "IDEspecialidad" => $especialidad_reg,
        ];


        $agregar_especialidad = especialidadModelo::agregar_especialidad_modelo($datos_especialidad_reg);

        if ($agregar_especialidad->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Especialidad registrada",
                "Texto" => "Especialidad registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la Especialidad",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador_especialidad_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT * FROM tblespecialidad  
                INNER JOIN tblempleado ON tblespecialidad.CodDoctor = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catespecialidades ON tblespecialidad.IDEspecialidad=catespecialidades.ID
                WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                OR (tblespecialidad.ID LIKE '$busqueda') 
                OR (tblpersona.Cedula LIKE '$busqueda') 
                OR (tblpersona.Telefono LIKE '$busqueda') 
                OR (tblpersona.Email LIKE '$busqueda') 
                OR (Nombres LIKE '%$busqueda%') 
                OR (catespecialidades.Nombre LIKE '%$busqueda%') 
                OR (Apellidos LIKE '%$busqueda%') 
                ORDER BY tblespecialidad.ID DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT * FROM tblespecialidad  
                INNER JOIN tblempleado ON tblespecialidad.CodDoctor = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catespecialidades ON tblespecialidad.IDEspecialidad=catespecialidades.ID
                ORDER BY tblespecialidad.ID DESC LIMIT $inicio,$registros";
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
								<th>DOCTOR</th>
								<th>ESPECIALIDAD</th>
								<th>FECHA REGISTRO</th>
                        </tr>
                            </thead>
                            <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['Nombre'] . '</td>
                                <td>' . $rows['FechaRegistro'] . '</td>
                        </tr>
                    ';
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
