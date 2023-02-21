<?php
if ($peticionAjax) {
    require_once "../modelos/constanciaModelo.php";
} else {
    require_once "./modelos/constanciaModelo.php";
}
class constanciaControlador extends constanciaModelo
{
    /*-----------------Controlador datos consulta para constancia-----------------*/
    public function datos_diagnostico_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return constanciaModelo::datos_diagnostico_modelo($id);
    }/*Fin de controlador */
    /*-----------------Controlador para agregar consulta-----------------*/
    public function agregar_constancia_controlador()
    {
        $CodDiagnostico = mainModel::limpiar_cadena($_POST['codigo_diagnostico_reg']);
        $CodDiagnostico2 = mainModel::limpiar_cadena($_POST['codigo_diagnostico_reg2']);
        $RAZON = mainModel::limpiar_cadena($_POST['razon_constancia_reg']);
        $Inicio = mainModel::limpiar_cadena($_POST['comienzo_cons_reg']);
        $Final = mainModel::limpiar_cadena($_POST['fin_cons_reg']);




        /*----------------Comprobar campos vacíos -----------------*/

        if ($RAZON == "" || $Inicio == "" || $Final == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }


        //AQUÍ TOCÓ LUIS PARA RECETA AUTO
        if ($CodDiagnostico != "") {
            //AQUÍ IRÁ CÓDIGO AUTOCOMPLETAR
            //buscador de diagnostico para textbox
            $parametrosDiagnostico = explode('_', $CodDiagnostico); //1-Nombre Paciente 2-FechaYHora diagnostico 3-CodDoctor
            //ChromePhp::log($parametrosdiagnostico[0]);

            //buscador de empleado para tex,tbox
            $datos_diagnostico = [
                "NombrePaciente" => $parametrosDiagnostico[0],
                "FhInicio" => $parametrosDiagnostico[1],
                "NombreEnfermedad" => $parametrosDiagnostico[2]
            ];

            $CodDiagnostico = constanciaModelo::buscarCodDiagnostico($datos_diagnostico);
            $row3 = $CodDiagnostico->fetch(); //ti
            $CodDiagnostico = $row3['cod_diagnostico']; //primary key tabla consulta


        } else {
            $CodDiagnostico = $CodDiagnostico2;
            /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/

            $check_Cod = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tbldiagnosticoconsulta WHERE Codigo='$CodDiagnostico'");
            if ($check_Cod->rowCount() == 0) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El codigo del diagnostico no está registrado en el sistema",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        /*DATOS POR ENVIAR */
        $datos_cosntancia_reg = [
            "CodDiagnostico" => $CodDiagnostico,
            "Razon" => $RAZON,
            "HoraEntrada" => $Inicio,
            "HoraSalida" => $Final
        ];


        $agregar_constancia = constanciaModelo::agregar_constancia_modelo($datos_cosntancia_reg);

        if ($agregar_constancia->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Constancia registrada",
                "Texto" => "Constancia registrada correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir la constancia",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }
    public function paginador_constancia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT  *, a.Nombres as NombrePaciente, a.Apellidos as ApellidosPaciente
                , b.Nombres as NombreDoc, b.Apellidos as ApellidosDoc, c.Codigo as codcons 
                FROM tblconstancia as c
                INNER JOIN tbldiagnosticoconsulta ON c.CodDiagnostico = tbldiagnosticoconsulta.Codigo 
                INNER JOIN tblconsulta ON tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo
                INNER JOIN catconsultorio ON tblconsulta.CodConsultorio = catconsultorio.ID
                INNER JOIN tblpaciente as d ON tblconsulta.CodPaciente = d.CodigoP
                INNER JOIN tblpersona as a ON d.CodPersona = a.Codigo
                INNER JOIN tblempleado ON tblconsulta.CodMedico = tblempleado.Codigo
                INNER JOIN tblpersona as b ON tblempleado.CodPersona = b.Codigo
                WHERE (CONCAT(a.Nombres,' ',a.Apellidos) LIKE '%$busqueda%')
                OR (c.Codigo LIKE '$busqueda') 
                OR (d.INSS LIKE '$busqueda') 
                OR (a.Cedula LIKE '$busqueda') 
                OR (a.Telefono LIKE '$busqueda') 
                OR (a.Email LIKE '$busqueda') 
                OR (a.Nombres LIKE '%$busqueda%') 
                OR (a.Apellidos LIKE '%$busqueda%') 
                ORDER BY codcons DESC
                LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT  *, a.Nombres as NombrePaciente, a.Apellidos as ApellidosPaciente
                , b.Nombres as NombreDoc, b.Apellidos as ApellidosDoc, tblconstancia.Codigo as codcons FROM tblconstancia 
                INNER JOIN tbldiagnosticoconsulta ON tblconstancia.CodDiagnostico = tbldiagnosticoconsulta.Codigo 
                INNER JOIN tblconsulta ON tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo
                INNER JOIN catconsultorio ON tblconsulta.CodConsultorio = catconsultorio.ID
                INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP
                INNER JOIN tblpersona as a ON tblpaciente.CodPersona = a.Codigo
                INNER JOIN tblempleado ON tblconsulta.CodMedico = tblempleado.Codigo
                INNER JOIN tblpersona as b ON tblempleado.CodPersona = b.Codigo
                ORDER BY codcons DESC
                LIMIT $inicio,$registros";
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
							
                    <th>#</th>
								<th>CÓDIGO MEDICO</th>
								<th>CÓDIGO CITA</th>
								<th>NOMBRE PACIENTE</th>
								<th>TELEFONO</th>
								<th>RAZÓN DE CONSTANCIA</th>
                                <th>CONSULTORIO</th>
                                <th>FECHA</th>
                                <th>REPORTE</th>
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
                                <th>' . $rows['NombreDoc'] . ' ' . $rows['ApellidosDoc'] . '</th>
                                <th>' . $rows['IdCita'] . '</th>
                                <th>' . $rows['NombrePaciente'] . ' ' . $rows['ApellidosPaciente'] . '</th>
                                <th>' . $rows['Telefono'] . '</th>
                                <th>' . $rows['Razon'] . '</th>
                                <th>' . $rows['Nombre'] . '</th>
                                <th>' . $rows['FechaYHora'] . '</th>
                        
                    ';
                $tabla .= '<td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-constancia.php?idConstancia='
                    . $rows['codcons'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>';
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


    public function reporte_constancia_controlador($id)
    {
        $id = mainModel::limpiar_cadena($id);
        return constanciaModelo::reporte_constancia_modelo($id);
    }
}
