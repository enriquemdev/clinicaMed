<?php
if ($peticionAjax) {
    require_once "../modelos/examenModelo.php";
} else {
    require_once "./modelos/examenModelo.php";
}
class examenControlador extends examenModelo
{
    public function datos_item1_controlador()
    {
        return examenModelo::datos_item1_modelo();
    }/*Fin de controlador */
    public function datos_maquinaria_controlador()
    {
        return examenModelo::datos_maquinaria_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador para agregar receta-----------------*/
    public function agregar_examen_controlador()
    {
        $CodReceta = mainModel::limpiar_cadena(explode("__", $_POST['receta_examen_reg'])[1]);
        $CodPaciente = mainModel::limpiar_cadena(explode("__", $_POST['receta_examen_reg'])[2]);
        $Sala = mainModel::limpiar_cadena($_POST['item_sala_medica_reg']);
        $Maquinaria = mainModel::limpiar_cadena($_POST['maquina_examen_reg']);
        $especialista = mainModel::limpiar_cadena(explode("__", $_POST['especialista_examen_reg'])[1]);
        $Fecha = mainModel::limpiar_cadena($_POST['fecha_examen_reg']);


        /* -------------------------------------------Codigo Medico automático-------------------------------------------*/
        /*21/03/2022*/
        session_start(['name' => 'SPM']);
        $CodMedico = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $CodMedico = $CodMedico->fetch();
        $CodMedico = $CodMedico['Codigo'];
        /*----------------Comprobar campos vacíos -----------------*/

        if ($CodReceta == "" || $CodPaciente == "" || $Sala == "" || $Maquinaria == "" || $especialista == "" || $Fecha == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de los datos de codigo de receta -----------------*/

        if (mainModel::verificar_datos("[0-9]{1,11}", $CodReceta)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de receta no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de los datos de codigo de paciente -----------------*/

        if (mainModel::verificar_datos("[0-9]{1,11}", $CodPaciente)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de paciente no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de los datos de codigo de maquinaria -----------------*/

        if ($Maquinaria < 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de maquinaria no es valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de los datos de codigo de empleado -----------------*/

        if (mainModel::verificar_datos("[0-9]{1,11}", $especialista)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de especialista no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/

        $check_CodE = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblrecetaexamen WHERE Codigo='$CodReceta'");
        if ($check_CodE->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de la receta no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $check_CodP = mainModel::ejecutar_consulta_simple("SELECT CodigoP FROM tblpaciente WHERE CodigoP='$CodPaciente'");
        if ($check_CodP->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo del paciente no está registrado en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $check_CodE = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$especialista'");
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
        /* Antes de guardar el examen se guarda el servicio brindados */

        $precioExamen = mainModel::ejecutar_consulta_simple("SELECT b.Precio FROM tblrecetaexamen as a
        INNER JOIN catexamenesmedicos as b on (a.TipoExamen=b.ID)
        WHERE a.Codigo=$CodReceta");
        $precioExamen = $precioExamen->fetch();
        $precioExamen = $precioExamen['Precio'];

        $datos_servicio_reg = [
            "TipoServicio" => 1,/* Hace referencia a tipo servicio de examen */
            "EstadoServicio" => 1,/* 1=Activo */
            "MontoServicio" => $precioExamen,
            "RebajaServicio" => 0
        ];
        $agregar_servicio = examenModelo::agregar_servicio_modelo($datos_servicio_reg);
        if ($agregar_servicio->rowCount() == 1) {
            $servicio = mainModel::ejecutar_consulta_simple("SELECT MAX(idServiciosBrindados) as idServiciosBrindados  
            from tblserviciosbrindados;");
            $servicio = $servicio->fetch();
            $servicio = $servicio['idServiciosBrindados'];
            /*Datos por enviar receta */
            $datos_examen_reg = [
                "RecetaExamen" => $CodReceta,
                "PacienteExamen" => $CodPaciente,
                "SalaMedicaExamen" => $Sala,
                "MaquinariaExamen" => $Maquinaria,
                "EspecialistaExamen" => $especialista,
                "FechaExamen" => $Fecha,
                "ServicioExamen" => $servicio
            ];
            $agregar_examen = examenModelo::agregar_examen_modelo($datos_examen_reg);

            if ($agregar_examen->rowCount() == 1) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Examen registrado",
                    "Texto" => "Examen registrado correctamente",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró añadir el examen",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el examen",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador_examen_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.Codigo AS CodigoExamen, a.RecetaPrevia,c.Nombres as NombresDoctor,
                c.Apellidos as ApellidosDoctor,e.Nombres as NombresPaciente,e.Apellidos as ApellidosPaciente,
                f.Nombre as NombreSalaMedica,a.FechaYHora
                FROM tblexamen as a
                INNER JOIN tblempleado as b ON (a.EmpleadoRealizacion=b.Codigo)
                INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
                INNER JOIN tblpaciente as d ON (a.CodPaciente=d.CodigoP)
                INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
                INNER JOIN catsalaexamen as f ON (a.SalaMedica=f.ID)
                WHERE (CONCAT(c.Nombres,' ',c.Apellidos) LIKE '%$busqueda%')
                OR (CONCAT(e.Nombres,' ',e.Apellidos) LIKE '%$busqueda%')
                OR (a.Codigo LIKE '$busqueda') 
                OR (c.Cedula LIKE '$busqueda') 
                OR (c.Telefono LIKE '$busqueda') 
                OR (c.Email LIKE '$busqueda') 
                OR (e.Cedula LIKE '$busqueda') 
                OR (e.Telefono LIKE '$busqueda') 
                OR (e.Email LIKE '$busqueda') 
                OR (c.Nombres LIKE '%$busqueda%') 
                OR (c.Apellidos LIKE '%$busqueda%') 
                OR (e.Nombres LIKE '%$busqueda%') 
                OR (e.Apellidos LIKE '%$busqueda%') 
                ORDER BY a.Codigo DESC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.Codigo AS CodigoExamen, a.RecetaPrevia,c.Nombres as NombresDoctor,
                c.Apellidos as ApellidosDoctor,e.Nombres as NombresPaciente,e.Apellidos as ApellidosPaciente,
                f.Nombre as NombreSalaMedica,a.FechaYHora
                FROM tblexamen as a
                INNER JOIN tblempleado as b ON (a.EmpleadoRealizacion=b.Codigo)
                INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
                INNER JOIN tblpaciente as d ON (a.CodPaciente=d.CodigoP)
                INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
                INNER JOIN catsalaexamen as f ON (a.SalaMedica=f.ID)
                ORDER BY a.Codigo DESC LIMIT $inicio,$registros;";
        }

        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 15);
        //Aqui manoseo steven
        $tabla .= '
                <div class="table-responsive">
                        <table class="table table-dark table-sm">
                            <thead>
                            <tr class="text-center roboto-medium">
                                <th>Codigo</th>
								<th>CÓDIGO RECETA</th>
								<th>NOMBRE MEDICO</th>
								<th>NOMBRE PACIENTE</th>
                                <th>NOMBRE SALA</th>
								<th>FECHA DE EMISIÓN</th>
                                <th>Reporte</th>
                        </tr>
                            </thead>
                            <tbody>
            ';
        //Aqui termino
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                //Aqui manoseo steven
                $tabla .= '
                        <tr class="text-center" >
                                <td>' . $rows['CodigoExamen'] . '</td>
                                <td>' . $rows['RecetaPrevia'] . '</td>
                                <td>' . $rows['NombresDoctor'] . ' ' . $rows['ApellidosDoctor'] . '</td>
                                <td>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidosPaciente'] . '</td>
                                <td>' . $rows['NombreSalaMedica'] . '</td>
                                <td>' . $rows['FechaYHora'] . '</td>
                                
                                <td>
                                <a href="' . SERVERURL . 'Reportes/reporte-u-examen.php?idExamen='
                    . mainModel::encryption($rows['CodigoExamen']) . '" 
                                target="_blank"  
                                class="btn btn-info">
                                <i class="fas fa-file-pdf"></i>
                                </a>
                                </td>
                            ' //Aqui termino
                    . '
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
    //Aqui manoseo steven
    public function datos_examen_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return examenModelo::datos_examen_modelo($id);
    }
}
