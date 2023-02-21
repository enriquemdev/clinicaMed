<?php
if ($peticionAjax) {
    require_once "../modelos/resultadoExamenModelo.php";
} else {
    require_once "./modelos/resultadoExamenModelo.php";
}
class resultadoExamenControlador extends resultadoExamenModelo
{

    /*-----------------Controlador para agregar receta-----------------*/
    public function agregar_resultado_examen_controlador()
    {
        $CodExamen = mainModel::limpiar_cadena(explode("__", $_POST['codigo_examen_reg'])[1]);
        $Fecha = mainModel::limpiar_cadena($_POST['examen_date_reg']);

        $extensiones = [];
        $extensiones[] = 'doc';
        $extensiones[] = 'docx';
        $extensiones[] = 'pdf';

        $archivo = $_FILES['archivoExamen_reg']['name'];
        $extension = pathinfo($archivo, PATHINFO_EXTENSION);

        $tamañoMaximo = 100000000;

        $imgenTMP = $_FILES['archivoExamen_reg']['tmp_name'];
        $imgenTYPE = $_FILES['archivoExamen_reg']['type'];
        $imgenSIZE = $_FILES['archivoExamen_reg']['size'];

        $ruta = "";

        if (isset($imgenTMP) && !empty($imgenTMP)) {
            if (in_array($extension, $extensiones)) {
                if ($imgenSIZE < $tamañoMaximo) {
                    $ruta = 'ArchivosExamen/' . time() . '_' . $_FILES['archivoExamen_reg']['name'];
                    if (move_uploaded_file($imgenTMP, '../' . $ruta)) {
                        //
                    } else {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro subir el archivo seleccionado",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El tamaño del archivo no es permitido",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "La extención del archivo no es valida",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /*----------------Comprobar campos vacíos -----------------*/

        if ($CodExamen == "" || $Fecha == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/

        $check_codigo = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblexamen WHERE Codigo='$CodExamen'");
        if ($check_codigo->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo del examen no está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }



        /*Datos por enviar receta */
        $datos__result_examen_reg = [
            "CodExamen" => $CodExamen,
            "ArchivoResultado" => $ruta,
            "FechaYHora" => $Fecha
        ];


        $agregar_resultado_examen = resultadoExamenModelo::agregar_resultado_examen_modelo($datos__result_examen_reg);

        if ($agregar_resultado_examen->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Resultado de examen registrado",
                "Texto" => "Resultado de examen registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el resultado de examen",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador__resultado_examen_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
        $pagina = mainModel::limpiar_cadena($pagina);
        $registros = mainModel::limpiar_cadena($registros);
        $privilegio = mainModel::limpiar_cadena($privilegio);
        $id = mainModel::limpiar_cadena($id);
        $url = mainModel::limpiar_cadena($url);
        $url = SERVERURL . $url . "/";

        //Identificador: 000
        $EsConsulta = false;
        //Termino identificador: 000

        $busqueda = mainModel::limpiar_cadena($busqueda);
        $tabla = "";
        $pagina = (isset($pagina) && $pagina > 0) ? (int)$pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
        //Identificador: 111
        if (isset($busqueda) && $busqueda != "") {
            $EsConsulta = true;
            $consulta = "SELECT SQL_CALC_FOUND_ROWS *,tblresultado.Codigo as codResultadoExamen 
                FROM tblresultado  
                INNER JOIN tblexamen ON tblresultado.CodExamen = tblexamen.Codigo 
                INNER JOIN tblpaciente ON tblexamen.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                OR (tblresultado.Codigo LIKE '$busqueda') 
                OR (tblpersona.Cedula LIKE '$busqueda') 
                OR (tblpersona.Telefono LIKE '$busqueda') 
                OR (tblpersona.Email LIKE '$busqueda') 
                OR (Nombres LIKE '%$busqueda%') 
                OR (Apellidos LIKE '%$busqueda%') 
                ORDER BY tblresultado.Codigo DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT SQL_CALC_FOUND_ROWS *,tblresultado.Codigo as codResultadoExamen 
                FROM tblresultado  
                INNER JOIN tblexamen ON tblresultado.CodExamen = tblexamen.Codigo 
                INNER JOIN tblpaciente ON tblexamen.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                ORDER BY tblresultado.Codigo DESC LIMIT $inicio,$registros";
        }
        //Termino identificador: 111


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
                            <th>CODIGO</th>
                            <th>NOMBRE DE PACIENTE</th>
                            <th>FECHA Y HORA</th>
                            <th>Reporte</th>
                        </tr>
                            </thead>
                            <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['codResultadoExamen'] . '</td>
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['FechaYHora'] . '</td>
                                <td>
                                    <a href="' . SERVERURL . 'Reportes/reporte-u-resultado-examen.php?idResultadoExamen='
                    . mainModel::encryption($rows['codResultadoExamen']) . '" 
                                    target="_blank"  
                                    class="btn btn-info">
                                    <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
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
    public function datos_resultado_examen_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return resultadoExamenModelo::datos_resultado_examen_modelo($id);
    }
}
