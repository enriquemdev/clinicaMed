<?php
if ($peticionAjax) {
    require_once "../modelos/diagnosticoModelo.php";
} else {
    require_once "./modelos/diagnosticoModelo.php";
}
class diagnosticoControlador extends diagnosticoModelo
{
    public function datos_item1_controlador()
    {
        return diagnosticoModelo::datos_item1_modelo();
    }/*Fin de controlador */
    /*-----------------Controlador datos consulta para diagnostico-----------------*/
    public function datos_consulta_controlador($id)
    {
        $id = mainModel::decryption($id);
        $id = mainModel::limpiar_cadena($id);

        return diagnosticoModelo::datos_consulta_modelo($id);
    }/*Fin de controlador */
    /*-----------------Controlador para agregar diagnostico-----------------*/
    public function agregar_diagnostico_controlador()
    {
        $Sintoma = mainModel::limpiar_cadena($_POST['sintoma_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['diagnostico_desc']);
        $IDEnfermedad = mainModel::limpiar_cadena($_POST['cod_enfermedad_reg']);
        $CodConsulta = mainModel::limpiar_cadena($_POST['codigo_consulta_reg']);
        $Notas = mainModel::limpiar_cadena($_POST['NOTA']);




        /*----------------Comprobar campos vacíos -----------------*/

        if ($Sintoma == "" || $Descripcion == "" || $IDEnfermedad == "" || $CodConsulta == "" || $Notas == "") {
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

        $check_ID = mainModel::ejecutar_consulta_simple("SELECT ID FROM catenfermedades WHERE ID='$IDEnfermedad'");
        if ($check_ID->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de la enfermedad no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*
            $check_codigo=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblconsulta WHERE Codigo='$CodConsulta'");
            if($check_codigo->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo de la consulta no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        //buscador de consulta para textbox
        $parametrosConsulta = explode('_', $CodConsulta); //1-Nombre Paciente 2-FechaYHora consulta 3-CodDoctor

        $datos_consulta = [
            "NombrePaciente" => $parametrosConsulta[0],
            "FhInicio" => $parametrosConsulta[1]
            //"CodigoMedico"=>$parametrosConsulta[2] YA NO SE NECESITA ESTO POR QUE EL BUSCADOR SOLO TE LANZA REGISTROS DEL DOCTOR LOGUEADO
        ];

        $CodigoConsulta = diagnosticoModelo::buscarCodConsulta($datos_consulta);
        $row3 = $CodigoConsulta->fetch(); //ti
        $CodigoConsulta = $row3['cod_consulta']; //primary key tabla consulta

        /*DATOS POR ENVIAR DIAGNOSTICO*/
        $datos_consulta_reg = [
            "Descripcion" => $Descripcion,
            "IdEnfermedad" => $IDEnfermedad,
            "CodConsulta" => $CodigoConsulta, //Variable modificada por el buscador en tiempo real
            "Nota" => $Notas
        ];
        //Cambio de estado a Terminado
        //YA NO SE NECESITA 14/05
        $datos = [
            "ID" => $CodigoConsulta
        ];

        $agregar_diagnostico = diagnosticoModelo::diagnostico_modelo($datos_consulta_reg);
        //$actualizar_estado=diagnosticoModelo::actues($datos); YA NO SE NECESITA

        /* 14/06/2022 AQUI OBTENEMOS EL CODIGO PARA INSERTARLO EN LA TABLA DE SINTOMAS*/
        $obtenerDiagnostico = mainModel::ejecutar_consulta_simple("SELECT MAX(Codigo) FROM tbldiagnosticoconsulta
             WHERE CodConsulta='$CodigoConsulta'");
        $obtenerDiagnostico = $obtenerDiagnostico->fetch(); //ti
        $obtenerDiagnostico = $obtenerDiagnostico['MAX(Codigo)'];

        $Sintomas = explode(', ', $Sintoma);

        $Sintomas = array_unique($Sintomas, SORT_STRING);

        //Para cada elemento recibido en sintomas
        foreach ($Sintomas as $elemento) {
            //Buscamos el sintoma en la base de datos
            $obtenerCodSintoma = mainModel::ejecutar_consulta_simple("SELECT idSintoma FROM catsintomas
                    WHERE nombreSintoma='$elemento'");

            //Si ya estaba en la base de datos obtenemos su codigo
            if ($obtenerCodSintoma->rowCount() > 0) {
                $obtenerCodSintoma = $obtenerCodSintoma->fetch(); //ti
                $obtenerCodSintoma = $obtenerCodSintoma['idSintoma'];
            } else {
                //Si no, lo agregamos a la base de datos como en espera y luego obtenemos su id y lo ingresamos
                $datosSintoma = [
                    "nombreSintoma" => $elemento,
                    "estadoSintoma" => 2
                ];

                $agregarSintomas = diagnosticoModelo::catSintomas_modelo($datosSintoma);

                //Obtener el id del ultimo agregado
                $obtenerCodSintoma = mainModel::ejecutar_consulta_simple("SELECT MAX(idSintoma) FROM catsintomas");
                $obtenerCodSintoma = $obtenerCodSintoma->fetch(); //ti
                $obtenerCodSintoma = $obtenerCodSintoma['MAX(idSintoma)'];
            }

            // $agregarSintomas = mainModel::ejecutar_consulta_simple("INSERT INTO tblsintomasdiagnostico(sintoma, diagnostico) 
            //     VALUES('$obtenerCodSintoma', '$obtenerDiagnostico')");
            $datosSintomasDiagnostico = [
                "sintoma" => $obtenerCodSintoma,
                "diagnostico" => $obtenerDiagnostico
            ];

            $agregarSintomas = diagnosticoModelo::sintomas_modelo($datosSintomasDiagnostico);
        }

        if ($agregar_diagnostico->rowCount() == 1 && $agregarSintomas->rowCount() > 0) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Diagnóstico registrado",
                "Texto" => "Diagnóstico registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el diagnóstico",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }
    /*-----------------Controlador para agregar diagnostico auto-----------------*/
    public function agregar_diagnostico_auto_controlador()
    {
        $Sintoma = mainModel::limpiar_cadena($_POST['sintoma_reg']);
        $Descripcion = mainModel::limpiar_cadena($_POST['diagnostico_desc']);
        $IDEnfermedad = mainModel::limpiar_cadena($_POST['cod_enfermedad_reg']);
        $CodConsulta = mainModel::decryption($_POST['codigo_consulta_reg']);
        $CodConsulta = mainModel::limpiar_cadena($CodConsulta);
        $Notas = mainModel::limpiar_cadena($_POST['NOTA']);




        /*----------------Comprobar campos vacíos -----------------*/

        if ($Sintoma == "" || $Descripcion == "" || $IDEnfermedad == "" || $CodConsulta == "" || $Notas == "") {
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

        $check_ID = mainModel::ejecutar_consulta_simple("SELECT ID FROM catenfermedades WHERE ID='$IDEnfermedad'");
        if ($check_ID->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El codigo de la enfermedad no está registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*DATOS POR ENVIAR DIAGNOSTICO*/
        $datos_consulta_reg = [
            "Sintoma" => $Sintoma,
            "Descripcion" => $Descripcion,
            "IdEnfermedad" => $IDEnfermedad,
            "CodConsulta" => $CodConsulta,
            "Nota" => $Notas
        ];


        $agregar_diagnostico = diagnosticoModelo::diagnostico_modelo($datos_consulta_reg);

        /* 14/06/2022 AQUI OBTENEMOS EL CODIGO PARA INSERTARLO EN LA TABLA DE SINTOMAS*/
        $obtenerDiagnostico = mainModel::ejecutar_consulta_simple("SELECT MAX(Codigo) FROM tbldiagnosticoconsulta
             WHERE CodConsulta='$CodConsulta'");
        $obtenerDiagnostico = $obtenerDiagnostico->fetch(); //ti
        $obtenerDiagnostico = $obtenerDiagnostico['MAX(Codigo)'];

        $Sintomas = explode(', ', $Sintoma);

        $Sintomas = array_unique($Sintomas, SORT_STRING);

        //Para cada elemento recibido en sintomas
        foreach ($Sintomas as $elemento) {
            //Buscamos el sintoma en la base de datos
            $obtenerCodSintoma = mainModel::ejecutar_consulta_simple("SELECT idSintoma FROM catsintomas
                    WHERE nombreSintoma='$elemento'");

            //Si ya estaba en la base de datos obtenemos su codigo
            if ($obtenerCodSintoma->rowCount() > 0) {
                $obtenerCodSintoma = $obtenerCodSintoma->fetch(); //ti
                $obtenerCodSintoma = $obtenerCodSintoma['idSintoma'];
            } else {
                //Si no, lo agregamos a la base de datos como en espera y luego obtenemos su id y lo ingresamos
                $datosSintoma = [
                    "nombreSintoma" => $elemento,
                    "estadoSintoma" => 2
                ];

                $agregarSintomas = diagnosticoModelo::catSintomas_modelo($datosSintoma);

                //Obtener el id del ultimo agregado
                $obtenerCodSintoma = mainModel::ejecutar_consulta_simple("SELECT MAX(idSintoma) FROM catsintomas");
                $obtenerCodSintoma = $obtenerCodSintoma->fetch(); //ti
                $obtenerCodSintoma = $obtenerCodSintoma['MAX(idSintoma)'];
            }

            // $agregarSintomas = mainModel::ejecutar_consulta_simple("INSERT INTO tblsintomasdiagnostico(sintoma, diagnostico) 
            //     VALUES('$obtenerCodSintoma', '$obtenerDiagnostico')");
            $datosSintomasDiagnostico = [
                "sintoma" => $obtenerCodSintoma,
                "diagnostico" => $obtenerDiagnostico
            ];

            $agregarSintomas = diagnosticoModelo::sintomas_modelo($datosSintomasDiagnostico);
        }

        if ($agregar_diagnostico->rowCount() == 1 && $agregarSintomas->rowCount() > 0) {
            $alerta = [
                "Alerta" => "redireccion_violenta",
                "Titulo" => "Diagnóstico registrado",
                "Texto" => "Diagnóstico registrado correctamente",
                "Tipo" => "success",
                "URL" => SERVERURL . "diagnostico-list/"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el diagnóstico",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    }
    public function paginador_diagnostico_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT *,A.Codigo as coddiagnostico,A.CodConsulta as CodigoConsulta 
                FROM tbldiagnosticoconsulta AS A
                INNER JOIN tblconsulta AS B ON A.CodConsulta = B.Codigo 
                INNER JOIN tblpaciente as C ON B.CodPaciente = C.CodigoP 
                INNER JOIN tblpersona as D ON C.CodPersona = D.Codigo 
                INNER JOIN catenfermedades as E ON A.IdEnfermedad = E.ID 
                WHERE (CONCAT(D.Nombres,' ',D.Apellidos) LIKE '%$busqueda%')
                OR (D.Codigo LIKE '$busqueda') 
                OR (C.INSS LIKE '$busqueda') 
                OR (D.Cedula LIKE '$busqueda') 
                OR (D.Telefono LIKE '$busqueda') 
                OR (D.Email LIKE '$busqueda') 
                OR (D.Nombres LIKE '%$busqueda%') 
                OR (D.Apellidos LIKE '%$busqueda%') 
                ORDER BY coddiagnostico DESC
                LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT *,tbldiagnosticoconsulta.Codigo as coddiagnostico,tbldiagnosticoconsulta.CodConsulta as CodigoConsulta FROM tbldiagnosticoconsulta INNER JOIN tblconsulta 
                ON tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo 
                INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                INNER JOIN catenfermedades ON tbldiagnosticoconsulta.IdEnfermedad = catenfermedades.ID 
                ORDER BY coddiagnostico DESC
                LIMIT $inicio,$registros";
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
                        <th>NOMBRE PACIENTE</th>
                        <th>ENFERMEDAD</th>
                        <th>DESCRIPCIÓN</th>
                        <th>GENERAR</th>
                        <th>DETALLES</th>
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
                                <td>' . $rows['coddiagnostico'] . '</td>
                                <th>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</th>
                                <th>' . $rows['NombreEnfermedad'] . '</th>
                                <th>' . $rows['Descripcion'] . '</th>
                                <!--Button to display details -->
                                
                                <th>
                                
                                <a href="' . SERVERURL . 'receta-medica-auto/' . mainModel::encryption($rows['CodigoConsulta']) . '"  data-toggle="tooltip" title="Generar Receta!" style="margin-right:20px; text-decoration:none" >
                                <i class="fa-solid fa-notes-medical hover-shadow" style="font-size: 30px; color:#2B8288; " ></i>
                                </a>
                                <a href="' . SERVERURL . 'receta-examen-auto/' . mainModel::encryption($rows['CodigoConsulta']) . '"data-toggle="tooltip" title="Generar orden exámen!" style="text-decoration:none">
                                <i class="fa-solid fa-file-medical hover-shadow" style="font-size: 30px; color:#36475C"></i>
                                </a>
                                </span>
                                <a href="' . SERVERURL . 'constancia-auto/' . mainModel::encryption($rows['coddiagnostico']) . '"data-toggle="tooltip" title="Generar constancia!" style="margin-left:20px; text-decoration:none"">
                                <i class="fa-solid fa-bed-pulse hover-shadow" style="font-size: 30px; color: #264963 "></i>
                                </a>
                                </th>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#myModal" style="font-size: 28px; color: #0384fc " class ="btn btn-info p-0" onclick="loadData(this.getAttribute(`data-id`));" data-id="' . $rows['coddiagnostico'] . '">
                                    <i class="fa-solid fa-list"></i>
                                    </button>
                                </td>
                    ';
                $tabla .= '<td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-diagnostico.php?idDiagnostico='
                    . $rows['coddiagnostico'] . '" 
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


    public function reporte_diagnostico_controlador($id)
    {
        $id = mainModel::limpiar_cadena($id);
        return diagnosticoModelo::reporte_diagnostico_modelo($id);
    }
}
