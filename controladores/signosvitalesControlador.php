<?php
if ($peticionAjax) {
    require_once "../modelos/signosvitalesModelo.php";
} else {
    require_once "./modelos/signosvitalesModelo.php";
}
class signosvitalesControlador extends signosvitalesModelo
{
    /*-----------------Controlador para agregar signos-----------------*/
    public function agregar_signosvitales_controlador()
    {

        //$CodPaciente=mainModel::limpiar_cadena($_POST['codigo_p_reg']);
        $CodConsulta = mainModel::limpiar_cadena($_POST['codigo_consulta_reg']);
        $Peso = mainModel::limpiar_cadena($_POST['peso_signos_reg']);
        $Altura = mainModel::limpiar_cadena($_POST['altura_signos_reg']);
        $PresionA = mainModel::limpiar_cadena($_POST['presion_arterial_signos_reg']);
        $FrecuenciaR = mainModel::limpiar_cadena($_POST['frecuencia_respiratoria_signos_reg']);
        $FrecuenciaC = mainModel::limpiar_cadena($_POST['frecuencia_cardiaca_signos_reg']);
        $Temperatura = mainModel::limpiar_cadena($_POST['temperatura_signos_reg']);
        //$CodEnfermera=mainModel::limpiar_cadena($_POST['enfermera_signos_reg']);
        //$CodEnfermera=intval($CodEnfermera);

        /*24/03/2022 */
        session_start(['name' => 'SPM']);
        $registradoPor = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $registradoPor = $registradoPor->fetch();
        $registradoPor = $registradoPor['Codigo']; //Registra el empleado (recepcionista) que hizo el registro
        /*----------------Comprobar campos vacíos -----------------*/

        if ($Peso == "" || $Altura == "" || $PresionA == "" || $FrecuenciaR == "" || $FrecuenciaC == "" || $Temperatura == "" || $CodConsulta == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Verificando datos numéricos*/
        if ($Peso < 1 || $Altura < 1 || $PresionA < 1 || $FrecuenciaR < 1 || $FrecuenciaC < 1 ||  $Temperatura < 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Revise los datos ingresados",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/
        /*
            $check_CodP=mainModel::ejecutar_consulta_simple("SELECT CodigoP FROM tblpaciente WHERE CodigoP='$CodPaciente'");
            if($check_CodP->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo del paciente no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/
        /*
            $check_CodEmpleado=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$CodEnfermera'");
            if($check_CodEmpleado->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo de la enfermera no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        //buscador codigo Consulta textBox
        //=====================SE NECESITA MODIFICAR, NO MUESTRA LAS CONSULTAS ASIGNADAS=============================
        $parametrosConsulta = explode('_', $CodConsulta); //NombrePaciente_FechaRegistro

        $datos_consulta = [
            "NombrePaciente" => $parametrosConsulta[0],
            "FechaYHora" => $parametrosConsulta[2]
        ];

        $CodigoConsulta = signosvitalesModelo::buscarCodConsulta($datos_consulta);
        $row3 = $CodigoConsulta->fetch(); //ti
        $CodigoConsulta = $row3['cod_consulta']; //primary key tabla consulta
        $registradoPor;
        $check_cargo = mainModel::ejecutar_consulta_simple("SELECT * FROM tblhistorialcargos WHERE CodEmpleado='$registradoPor' AND Estado = 1 AND IdCargo = 2");
        if ($check_cargo->rowCount() == 0) { //Si es 0 es enfermera
            $actualizarestado = signosvitalesModelo::actualizar_consulta_en_espera($CodigoConsulta);
        } else { //Si no, es dr y procede a validar lo siguiente
            //checkear estado actual de la consulta
            $checkestadoactual = mainModel::ejecutar_consulta_simple("SELECT * FROM tblconsulta WHERE Codigo='$CodigoConsulta'");
            $row4 = $checkestadoactual->fetch(); //ti
            $estadoconsulta = $row4['Estado']; //primary key tabla consulta
            if ($estadoconsulta == 1) {
                $actualizarestado = signosvitalesModelo::actualizar_consulta_en_espera($CodigoConsulta);
            } //En caso de que no sea 1 significa que es prioridad o ya está con el dr por lo que no se le cambia el estado
            /*Datos por enviar receta */
        }
        $datos_signosV_reg = [
            "CodConsulta" => $CodigoConsulta,
            "Peso" => $Peso,
            "Altura" => $Altura,
            "Presion_Arterial" => $PresionA,
            "Frecuencia_Respiratoria" => $FrecuenciaR,
            "Frecuencia_Cardiaca" => $FrecuenciaC,
            "Temperatura" => $Temperatura,
            "CodEnfermera" => $registradoPor //Automatico                         
        ];


        $agregar_signos = signosvitalesModelo::agregar_signosvitales_modelo($datos_signosV_reg);

        if ($agregar_signos->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Signos vitales registrados",
                "Texto" => "Signos vitales registrados correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir los Signos vitales",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador
    public function agregar_signosvitales_controlador_auto()
    {

        $CodConsulta = mainModel::limpiar_cadena($_POST['codigo_consulta_reg']);
        $Peso = mainModel::limpiar_cadena($_POST['peso_signos_reg']);
        $Altura = mainModel::limpiar_cadena($_POST['altura_signos_reg']);
        $PresionA = mainModel::limpiar_cadena($_POST['presion_arterial_signos_reg']);
        $FrecuenciaR = mainModel::limpiar_cadena($_POST['frecuencia_respiratoria_signos_reg']);
        $FrecuenciaC = mainModel::limpiar_cadena($_POST['frecuencia_cardiaca_signos_reg']);
        $Temperatura = mainModel::limpiar_cadena($_POST['temperatura_signos_reg']);

        session_start(['name' => 'SPM']);
        $registradoPor = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $registradoPor = $registradoPor->fetch();
        $registradoPor = $registradoPor['Codigo']; //Registra el empleado (recepcionista) que hizo el registro
        /*----------------Comprobar campos vacíos -----------------*/

        if ($Peso == "" || $Altura == "" || $PresionA == "" || $FrecuenciaR == "" || $FrecuenciaC == "" || $Temperatura == "" || $CodConsulta == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*Verificando datos numéricos*/
        if ($Peso < 1 || $Altura < 1 || $PresionA < 1 || $FrecuenciaR < 1 || $FrecuenciaC < 1 ||  $Temperatura < 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Revise los datos ingresados",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/
        /*
            $check_CodP=mainModel::ejecutar_consulta_simple("SELECT CodigoP FROM tblpaciente WHERE CodigoP='$CodPaciente'");
            if($check_CodP->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo del paciente no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/
        /*
            $check_CodEmpleado=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE Codigo='$CodEnfermera'");
            if($check_CodEmpleado->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo de la enfermera no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

        $Estado = 2;
        $CodigoConsulta = $CodConsulta;
        $check_cargo = mainModel::ejecutar_consulta_simple("SELECT * FROM tblhistorialcargos WHERE CodEmpleado='$registradoPor' AND Estado = 1 AND IdCargo = 2");
        if ($check_cargo->rowCount() == 0) { //Si es 0 es enfermera
            $actualizarestado = signosvitalesModelo::actualizar_consulta_en_espera($CodigoConsulta);
        } else { //Si no, es dr y procede a validar lo siguiente
            //checkear estado actual de la consulta
            $checkestadoactual = mainModel::ejecutar_consulta_simple("SELECT * FROM tblconsulta WHERE Codigo='$CodigoConsulta'");
            $row4 = $checkestadoactual->fetch(); //ti
            $estadoconsulta = $row4['Estado']; //primary key tabla consulta
            if ($estadoconsulta == 1) {
                $actualizarestado = signosvitalesModelo::actualizar_consulta_en_espera($CodigoConsulta);
            } //En caso de que no sea 1 significa que es prioridad o ya está con el dr por lo que no se le cambia el estado
            /*Datos por enviar receta */
        }

        /*Datos por enviar receta */
        $datos_signosV_reg = [
            "CodConsulta" => $CodConsulta,
            "Peso" => $Peso,
            "Altura" => $Altura,
            "Presion_Arterial" => $PresionA,
            "Frecuencia_Respiratoria" => $FrecuenciaR,
            "Frecuencia_Cardiaca" => $FrecuenciaC,
            "Temperatura" => $Temperatura,
            "CodEnfermera" => $registradoPor, //Automatico                   
        ];


        $agregar_signos = signosvitalesModelo::agregar_signosvitales_modelo($datos_signosV_reg);
        if ($agregar_signos->rowCount() == 1) {
            $alerta = [
                "Alerta" => "redireccion_violenta",
                "Titulo" => "Signos vitales registrados",
                "Texto" => "Signos vitales registrados correctamente",
                "Tipo" => "success",
                "URL" => SERVERURL . "solicitud-consulta-list/"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir los Signos vitales",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //termina controlador

    /*-----------------Controlador para paginar signos----------------- */
    public function paginador_signosvitales_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        /*Aquí tocó luis */
        if ($_SESSION['cargo_spm'] == 2) //Si es doctor
        { //inicia condicional 1
            if (isset($busqueda) && $busqueda != "") {
                $EsConsulta = true;
                $consulta = "SELECT  *, A.Codigo as CD,E.Nombres as NombreE, 
                    E.Apellidos as ApellidoE,
                    C.Nombres as NombreP,
                    C.Apellidos as ApellidoP
                    FROM tblsignosvitales AS A
                    inner join tblconsulta AS B on A.CodConsulta = B.Codigo
                    inner join tblpaciente AS F on B.CodPaciente = F.CodigoP
                    inner join tblpersona AS C on F.CodPersona = C.Codigo
                    inner join tblempleado AS D on A.CodEnfermera = D.Codigo
                    inner join tblpersona AS E on D.CodPersona = E.Codigo 
                    inner join tblconsulta AS Z on A.CodConsulta = Z.Codigo
                    WHERE Z.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "')
                    AND (CONCAT(C.Nombres,' ',C.Apellidos) LIKE '%$busqueda%')
                    OR (A.Codigo LIKE '$busqueda') 
                    OR (F.INSS LIKE '$busqueda') 
                    OR (C.Cedula LIKE '$busqueda') 
                    OR (C.Telefono LIKE '$busqueda') 
                    OR (C.Email LIKE '$busqueda') 
                    OR (C.Nombres LIKE '%$busqueda%') 
                    OR (C.Apellidos LIKE '%$busqueda%') 
                    ORDER BY A.Codigo DESC LIMIT $inicio,$registros";
            } else {
                //Identificador: 111
                $EsConsulta = false;
                //Ternina identificador: 111
                $consulta = "SELECT  *, A.Codigo as CD,E.Nombres as NombreE, 
                    E.Apellidos as ApellidoE,
                    C.Nombres as NombreP,
                    C.Apellidos as ApellidoP
                    FROM tblsignosvitales AS A
                    inner join tblconsulta AS B on A.CodConsulta = B.Codigo
                    inner join tblpaciente AS F on B.CodPaciente = F.CodigoP
                    inner join tblpersona AS C on F.CodPersona = C.Codigo
                    inner join tblempleado AS D on A.CodEnfermera = D.Codigo
                    inner join tblpersona AS E on D.CodPersona = E.Codigo 
                    inner join tblconsulta AS Z on A.CodConsulta = Z.Codigo
                    WHERE Z.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "')
                     ORDER BY A.Codigo DESC LIMIT $inicio,$registros";
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
                                
                        <th>CÓDIGO SIGNOS</th>
                            <th>NOMBRE PACIENTE</th>
								<th>PESO</th>
								<th>ALTURA</th>
                                <th>PRESIÓN ARTERIAL</th>
								<th>FRECUENCIA RESPIRATORIA</th>
								<th>FRECUENCIA CARDÍACA</th>
								<th>FECHA</th>
                                <th>NOMBRE ENFERMER@</th>
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
                                    <td>' . $rows['CD'] . '</td>
                                    <td>' . $rows['NombreP'] . ' ' . $rows['ApellidoP'] . '</td>
                                    <td>' . $rows['Peso'] . '</td>
                                    <td>' . $rows['Altura'] . '</td>
                                    <td>' . $rows['Presion_Arterial'] . '</td>
                                    <td>' . $rows['Frecuencia_Respiratoria'] . '</td>
                                    <td>' . $rows['Frecuencia_Cardiaca'] . '</td>
                                    <td>' . $rows['HoraRegistro'] . '</td>
                                    <td>' . $rows['NombreE'] . ' ' . $rows['ApellidoE'] . '</td>
                                    <td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-signos-vitales.php?id='
                        . $rows['CD'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>
                                    
                        ';
                    $contador++;
                } //FALTA AGREGAR EL DE RECHAZO Y HAY QUE VER COMO HACER QUE HAGAN LA ACCION DE EJECUTAR SQL



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
        } //Termina condicional 1
        else if ($_SESSION['cargo_spm'] == 1) //Si es enfermera
        { //inicia condicional 2
            $consulta = "SELECT  *, A.Codigo as CD,E.Nombres as NombreE, 
                E.Apellidos as ApellidoE,
                C.Nombres as NombreP,
                C.Apellidos as ApellidoP
                FROM tblsignosvitales AS A
                inner join tblconsulta AS B on A.CodConsulta = B.Codigo
                inner join tblpaciente AS F on B.CodPaciente = F.CodigoP
                inner join tblpersona AS C on F.CodPersona = C.Codigo
                inner join tblempleado AS D on A.CodEnfermera = D.Codigo
                inner join tblpersona AS E on D.CodPersona = E.Codigo 
                inner join tblconsulta AS Z on A.CodConsulta = Z.Codigo
                WHERE A.CodEnfermera = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "')
                 ORDER BY A.Codigo DESC LIMIT $inicio,$registros";


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
                                
                        <th>CÓDIGO SIGNOS</th>
                            <th>NOMBRE PACIENTE</th>
								<th>PESO</th>
								<th>ALTURA</th>
                                <th>PRESIÓN ARTERIAL</th>
								<th>FRECUENCIA RESPIRATORIA</th>
								<th>FRECUENCIA CARDÍACA</th>
								<th>FECHA</th>
                                <th>NOMBRE ENFERMER@</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['CD'] . '</td>
                                    <td>' . $rows['NombreP'] . ' ' . $rows['ApellidoP'] . '</td>
                                    <td>' . $rows['Peso'] . '</td>
                                    <td>' . $rows['Altura'] . '</td>
                                    <td>' . $rows['Presion_Arterial'] . '</td>
                                    <td>' . $rows['Frecuencia_Respiratoria'] . '</td>
                                    <td>' . $rows['Frecuencia_Cardiaca'] . '</td>
                                    <td>' . $rows['HoraRegistro'] . '</td>
                                    <td>' . $rows['NombreE'] . ' ' . $rows['ApellidoE'] . '</td>
                                    
                        ';
                    $contador++;
                } //FALTA AGREGAR EL DE RECHAZO Y HAY QUE VER COMO HACER QUE HAGAN LA ACCION DE EJECUTAR SQL



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
        } //Termina condicional 2
        else //Si es un cargo adicional
        { //inicia condicional 2
            $consulta = "SELECT  *, A.Codigo as CD,E.Nombres as NombreE, 
                E.Apellidos as ApellidoE,
                C.Nombres as NombreP,
                C.Apellidos as ApellidoP
                FROM tblsignosvitales AS A
                inner join tblconsulta AS B on A.CodConsulta = B.Codigo
                inner join tblpaciente AS F on B.CodPaciente = F.CodigoP
                inner join tblpersona AS C on F.CodPersona = C.Codigo
                inner join tblempleado AS D on A.CodEnfermera = D.Codigo
                inner join tblpersona AS E on D.CodPersona = E.Codigo 
                inner join tblconsulta AS Z on A.CodConsulta = Z.Codigo
                 ORDER BY A.Codigo DESC LIMIT $inicio,$registros";


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
                                
                        <th>CÓDIGO SIGNOS</th>
                            <th>NOMBRE PACIENTE</th>
								<th>PESO</th>
								<th>ALTURA</th>
                                <th>PRESIÓN ARTERIAL</th>
								<th>FRECUENCIA RESPIRATORIA</th>
								<th>FRECUENCIA CARDÍACA</th>
								<th>FECHA</th>
                                <th>NOMBRE ENFERMER@</th>
                    </tr>
                        </thead>
                        <tbody>
                ';
            if ($total >= 1 && $pagina <= $Npaginas) {
                $contador = $inicio + 1;
                foreach ($datos as $rows) {
                    $tabla .= '
                            <tr class="text-center" >
                                    <td>' . $rows['CD'] . '</td>
                                    <td>' . $rows['NombreP'] . ' ' . $rows['ApellidoP'] . '</td>
                                    <td>' . $rows['Peso'] . '</td>
                                    <td>' . $rows['Altura'] . '</td>
                                    <td>' . $rows['Presion_Arterial'] . '</td>
                                    <td>' . $rows['Frecuencia_Respiratoria'] . '</td>
                                    <td>' . $rows['Frecuencia_Cardiaca'] . '</td>
                                    <td>' . $rows['HoraRegistro'] . '</td>
                                    <td>' . $rows['NombreE'] . ' ' . $rows['ApellidoE'] . '</td>
                                    
                        ';
                    $contador++;
                } //FALTA AGREGAR EL DE RECHAZO Y HAY QUE VER COMO HACER QUE HAGAN LA ACCION DE EJECUTAR SQL



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
        } //Termina condicional 2
        return $tabla;
    }
    /*-----------------Controlador para paginar signos----------------- */
    public function paginador2_signosvitales_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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


        $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM tblsignosvitales 
            inner join tblpaciente on tblsignosvitales.CodPaciente = tblpaciente.CodigoP
            inner join tblpersona on tblpaciente.CodPersona = tblpersona.Codigo  LIMIT $inicio,$registros";
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
							
                            <th>CÓDIGO SIGNOS</th>
                            <th>NOMBRE PACIENTE</th>
								<th>PESO</th>
								<th>ALTURA</th>
                                <th>PRESIÓN ARTERIAL</th>
								<th>FRECUENCIA RESPIRATORIA</th>
								<th>FRECUENCIA CARDÍACA</th>
								<th>TEMPERATURA</th>
                                <th>CÓDIGO ENFERMERA</th>
                                <th>ELIMINAR</th>
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
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['Peso'] . '</td>
                                <td>' . $rows['Altura'] . '</td>
                                <td>' . $rows['Presion_Arterial'] . '</td>
                                <td>' . $rows['Frecuencia_Respiratoria'] . '</td>
                                <td>' . $rows['Frecuencia_Cardiaca'] . '</td>
                                <td>' . $rows['HoraRegistro'] . '</td>
                                <td>' . $rows['CodEnfermera'] . '</td>
                                <td>
                                    <form class="FormularioAjax" action="echo ' . SERVERURL . 'ajax/usuarioAjax.php" method="POST" data-form= "delete" autocomplete="off">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
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
    public function reporte_signos_vitales_controlador($id)
    {
        $id = mainModel::limpiar_cadena($id);

        return signosvitalesModelo::reporte_signos_vitales_modelo($id);
    }
}
