<?php

//include '../ChromePhp.php';

if ($peticionAjax) {
    require_once "../modelos/recetaModelo.php";
} else {
    require_once "./modelos/recetaModelo.php";
}



class recetaControlador extends recetaModelo
{
    /*-----------------Controlador datos consulta para receta-----------------*/
    public function datos_consulta_controlador($datos)
    {
        return recetaModelo::datos_consulta_modelo($datos);
    }/*Fin de controlador */
    public function datos_medicamentos_controlador()
    {
        return recetaModelo::datos_medicamentos_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar receta-----------------*/
    public function agregar_receta_controlador()
    {


        /*Receta */

        $CodConsulta = mainModel::limpiar_cadena($_POST['codigo_consulta_reg']);

        $CodConsulta2 = $_POST['codigo_consulta_previa_reg']; //LUIS TOCÓ AQUÍ NUEVA VARIABLE DE RECETA AUTOMÁTICA
        //ChromePhp::log($CodConsulta);
        /*Detalle Receta */
        $CodMedicamento = mainModel::limpiar_cadena($_POST['codigo_medicamento_reg']);
        $Dosis = mainModel::limpiar_cadena($_POST['dosis_medicamento_reg']);
        $Frecuencia = mainModel::limpiar_cadena($_POST['frecuencia_medicamento_reg']);
        /*----------------Comprobar campos vacíos -----------------*/

        if ($CodMedicamento == "" || $Dosis == "" || $Frecuencia == "") {
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
        /*
            $check_CodCons=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblconsulta WHERE Codigo='$CodConsulta'");
            if($check_CodCons->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La consulta no está registrada en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        /*
            $check_CodMed=mainModel::ejecutar_consulta_simple("SELECT ID FROM catmedicamentos WHERE ID='$CodMedicamento'");
            if($check_CodMed->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El medicamento no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/


        if ($CodConsulta != "") {
            //buscador de empleado para textbox
            $parametrosConsulta = explode('_', $CodConsulta); //1-Nombre Paciente 2-FechaYHora consulta 3-CodDoctor
            //ChromePhp::log($parametrosConsulta[0]);

            //buscador de empleado para textbox
            $datos_consulta = [
                "NombrePaciente" => $parametrosConsulta[0],
                "FhInicio" => $parametrosConsulta[1]
                //"CodigoMedico"=>$parametrosConsulta[2] YA NO SE NECESITA ESTO POR QUE EL BUSCADOR SOLO TE LANZA REGISTROS DEL DOCTOR LOGUEADO
            ];

            $CodigoConsulta = recetaModelo::buscarCodConsulta($datos_consulta);
            $row3 = $CodigoConsulta->fetch(); //ti
            $CodigoConsulta = $row3['cod_consulta']; //primary key tabla consulta


        } else {
            $CodigoConsulta = $CodConsulta2;
        }

        /*Datos por enviar receta */
        $datos_receta_reg = [
            "CodigoConsulta" => $CodigoConsulta //Variable modificcada por el buscador

        ];

        $agregar_receta = recetaModelo::agregar_receta_modelo($datos_receta_reg);
        /*Datos por enviar detalle receta */
        $codigoReceta = recetaModelo::obtener_codigoreceta_modelo();
        $row1 = $codigoReceta->fetch();/*ti*/
        $codigoR = $row1['Codigo'];
        $datos_detallereceta_reg = [
            "Medicamento" => $CodMedicamento,
            "Dosis" => $Dosis,
            "Frecuencia" => $Frecuencia,
            "CodReceta" => $codigoR
        ];
        $agregar_detallereceta = recetaModelo::agregar_detallereceta_modelo($datos_detallereceta_reg);


        if ($agregar_receta->rowCount() == 1 && $agregar_detallereceta->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Receta registrada",
                "Texto" => "Receta registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la receta",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador_receta_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS *, g.Dosis as dosis, g.Frecuencia as frec, h.Presentacion as pres, h.NombreComercial as Medicamento, a.Codigo as codigoRec,f.Nombres as nombreDoc, f.Apellidos as apellidosDoc
                , d.Nombres as nombrePaciente, d.Apellidos as apellidosPaciente 
                FROM catmedicamentos as h
                INNER JOIN tbldetallereceta as g on h.Codigo = g.Medicamento
                INNER JOIN tblrecetamedicamentos as a ON g.CodReceta = a.Codigo
                INNER JOIN tblconsulta as b ON a.CodigoConsulta = b.Codigo 
                INNER JOIN tblpaciente as c ON b.CodPaciente = c.CodigoP 
                INNER JOIN tblpersona as d ON c.CodPersona = d.Codigo 
                INNER JOIN tblempleado as e ON b.CodMedico = e.Codigo
                INNER JOIN tblpersona as f ON e.CodPersona = f.Codigo
                WHERE (CONCAT(d.Nombres,' ',d.Apellidos) LIKE '%$busqueda%')
                OR (a.Codigo LIKE '$busqueda') 
                OR (c.INSS LIKE '$busqueda') 
                OR (d.Cedula LIKE '$busqueda') 
                OR (d.Telefono LIKE '$busqueda') 
                OR (d.Email LIKE '$busqueda') 
                OR (d.Nombres LIKE '%$busqueda%') 
                OR (d.Apellidos LIKE '%$busqueda%') 
                ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT SQL_CALC_FOUND_ROWS *, g.Dosis as dosis, g.Frecuencia as frec, h.Presentacion as pres, h.NombreComercial as Medicamento, a.Codigo as codigoRec,f.Nombres as nombreDoc, f.Apellidos as apellidosDoc
                , d.Nombres as nombrePaciente, d.Apellidos as apellidosPaciente FROM catmedicamentos as h
                INNER JOIN tbldetallereceta as g on h.Codigo = g.Medicamento
                INNER JOIN tblrecetamedicamentos as a ON g.CodReceta = a.Codigo
                INNER JOIN tblconsulta as b ON a.CodigoConsulta = b.Codigo 
                INNER JOIN tblpaciente as c ON b.CodPaciente = c.CodigoP 
                INNER JOIN tblpersona as d ON c.CodPersona = d.Codigo 
                INNER JOIN tblempleado as e ON b.CodMedico = e.Codigo
                INNER JOIN tblpersona as f ON e.CodPersona = f.Codigo
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
								<th>NOMBRE PACIENTE</th>
                                <th>MEDICAMENTO</th>
                                <th>DOSIS (UND/CUCH)</th>
                                <th>FRECUENCIA (HORAS)</th>
								<th>FECHA EMISIÓN</th>
                                <th>CONSULTORIO</th>
                                <th>REPORTE</th>
                        </tr>
                            </thead>
                            <tbody>
            ';

        //<td>'.$rows['CodigoConsulta'].'</td>
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['codigoRec'] . '</td>
                                
                                <td>' . $rows['nombreDoc'] . ' ' . $rows['apellidosDoc'] . '</td>
                                <td>' . $rows['nombrePaciente'] . ' ' . $rows['apellidosPaciente'] . '</td>
                                <td>' . $rows['Medicamento'] . '-' . $rows['pres'] . '</td>
                                <td>' . $rows['dosis'] . '</td>
                                <td>' . $rows['frec'] . '</td>
                                <td>' . $rows['FechaEmision'] . '</td>
                                <td>' . $rows['CodConsultorio'] . '</td>';
                $tabla .= '<td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-recetaMedica.php?idRecetaMedica='
                    . $rows['codigoRec'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>
                        </tr>';
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
    } //Fin controlador

    public function reporte_receta_medica_controlador($id)
    {
        $id = mainModel::limpiar_cadena($id);
        return recetaModelo::reporte_receta_medica_modelo($id);
    }
}
