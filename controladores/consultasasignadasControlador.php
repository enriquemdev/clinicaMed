<?php

if ($peticionAjax) {
    require_once "../modelos/solicitudConsultaModelo.php";
} else {
    require_once "./modelos/solicitudConsultaModelo.php";
}
class consultasasignadas extends solicitudConsultaModelo
{
    public function paginador_consultas_asignadas_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
    {
        //Listar asignaciones de consulta
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
        if ($_SESSION['cargo_spm'] == 2) //Si es doctor
        {

            $consulta = " SELECT *, f.Nombre as Estado_Consulta, a.Codigo as Codigo_consulta, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as ApellidoPaciente, c.Apellidos as ApellidoDoc,E.Telefono as TelefonoPac, e.Email as EmailPac 
            from catestadoconsulta as f 
            inner join tblconsulta as a ON (f.ID = a.Estado)
            inner join tblempleado as b ON (a.CodMedico=b.Codigo)
            inner join tblpersona as c on (b.CodPersona=c.Codigo)
            inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
            inner join tblpersona as e on(d.CodPersona=e.Codigo) 
            WHERE  a.Estado = 1 /* OR a.Estado = 3  */
                    ORDER BY a.Codigo DESC LIMIT $inicio,$registros";
        }
        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $Npaginas = ceil($total / 15);

        //Se utilizará el mismo paginador para Recepcionista y Enfermera Pero las funciones serán diferentes

        $tabla .= '
                <div class="table-responsive">
                    <table class="table table-dark table-sm">
                        <thead>
                        <tr class="text-center roboto-medium">
                                
                        <th>#</th>
                        <th>NOMBRE MEDICO</th>
                        <th>CÓDIGO CITA</th>
                        <th>PACIENTE</th>
                        <th>CONTACTO</th>
                        <th>FECHA Y HORA</th>
                        <th>ESTADO</th>
                ';
        //Condicional de cargo para mostrar
        if ($_SESSION['cargo_spm'] == 3) //Si es recepcionista  
        {
            $tabla .= '
                            <th>ACTUALIZAR</th>
                            </tr>
                            </thead>
                            <tbody>
                            ';
        } else { //Si es enfermera
            $tabla .= '
                            <th>TOMAR SIGNOS</th>
                            </tr>
                            </thead>
                            <tbody>
                            ';
        }
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {

                //En caso de que sea enfermera o doctor
                if ($rows['Estado_Consulta'] == 'Asignada') {
                    $tabla .= '
                                <tr class="text-center" >
                                        <td>' . $rows['Codigo_consulta'] . '</td>
                                        <th>' . $rows['NombresDoctor'] . ' ' . $rows['ApellidoDoc'] . '</th>
                                        ';
                    if ($rows['IdCita'] == 0) {
                        $tabla .= '
                                        <th>Sin cita</th>
                                        ';
                    } else {
                        $tabla .= '
                                        <th>' . $rows['IdCita'] . '</th>
                                        ';
                    }
                    $tabla .= '
                                        <th>' . $rows['NombresPaciente'] . ' ' . $rows['ApellidoPaciente'] . '</th>';
                                        if($rows['TelefonoPac']!=null){
                                            $tabla .= '<th>' . $rows['TelefonoPac'] . '</th>';
                                        }else{
                                            $tabla .= '<th>' . $rows['EmailPac'] . '</th>';
                                        }
                                        $tabla .= '<th>' . $rows['FechaYHora'] . '</th>
                                        <td><span class="badge badge-primary">' . $rows['Estado_Consulta'] . '</span></td>
                                        <th>
                                        <a href="' . SERVERURL . 'signos-vitales-auto/' . mainModel::encryption($rows['Codigo_consulta'])/*
                                        Aquí se llama la acción de generar Signos vitales */ . '"data-toggle="tooltip" title="Generar signos!"">
                                        <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                        </a>
                                        </td>
                                        </tr>
                                ';
                    $contador++;
                }
            } //Termina foreach



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
