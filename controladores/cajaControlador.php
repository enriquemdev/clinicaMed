<?php
if ($peticionAjax) {
    require_once "../modelos/cajaModelo.php";
} else {
    require_once "./modelos/cajaModelo.php";
}
class cajaControlador extends cajaModelo
{

    public function paginador_paciente_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $condRadio, $condRadio2)
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

        if ((isset($busqueda) && $busqueda != "") || $condRadio != "" || $condRadio2 != "") {
            $EsConsulta = true;

            $consulta = "SELECT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento, b.Cedula as Cedula
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        WHERE ((CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                        OR (a.CodigoP LIKE '$busqueda') 
                        OR (INSS LIKE '$busqueda') 
                        OR (b.Cedula LIKE '$busqueda') 
                        OR (b.Telefono LIKE '$busqueda') 
                        OR (b.Email LIKE '$busqueda') 
                        OR (b.Nombres LIKE '%$busqueda%') 
                        OR (b.Apellidos LIKE '%$busqueda%')) ";


            if ($_SESSION['condRadio'] != "") {
                $consulta = $consulta . "AND (b.Genero = " . $_SESSION['condRadio'] . ") ";
            }

            if ($_SESSION['condRadio2'] != "") {
                $consulta = $consulta . "AND (b.Estado = " . $_SESSION['condRadio2'] . ") ";
            }

            $consulta = $consulta . "ORDER BY CodigoP DESC LIMIT $inicio,$registros";
        } else {
            //DEVUELVE LOS PACIENTES QUE TIENEN SERVICIOS POR PAGAR
            $consulta2 = "SELECT DISTINCT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                b.Fecha_de_nacimiento as FechaNacimiento, b.Cedula as Cedula
                FROM tblpaciente as a
                INNER JOIN tblpersona as b ON a.CodPersona = b.Codigo 
                INNER JOIN tblconsulta as c ON a.CodigoP = c.CodPaciente
                INNER JOIN tblserviciosbrindados as d ON c.idServicio = d.idServiciosBrindados
                WHERE (
                    (a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    d.estadoServicio = 1)
                    OR
                    (a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    d.estadoServicio = 2)
                )
                ORDER BY d.fechaYHora ASC LIMIT $inicio,$registros";

            $consulta2 = "SELECT SQL_CALC_FOUND_ROWS
                d.idServiciosBrindados,
                d.tipoServicio,
                d.estadoServicio,
                d.MontoServicio as MontoTblServicio,
                d.fechaYHora as FHservicio,
                e.nombreServicio,
                e.PrecioGeneral as precioServicio,
                j.Nombre as nombreExamen,
                j.Precio as precioCatExamen
                FROM catservicios as e 
                INNER JOIN tblserviciosbrindados as d ON e.idServicio = d.tipoServicio
                LEFT JOIN tblconsulta as c ON d.idServiciosBrindados = c.idServicio 
                LEFT JOIN tblpaciente as a ON c.CodPaciente = a.CodigoP
                LEFT JOIN tblpersona as b ON  a.CodPersona = b.Codigo 
                LEFT JOIN tblexamen as f ON d.idServiciosBrindados = f.idServicio
                LEFT JOIN tblpaciente as g ON f.CodPaciente = g.CodigoP
                LEFT JOIN tblpersona as h ON  g.CodPersona = h.Codigo
                LEFT JOIN tblrecetaexamen as i ON f.RecetaPrevia = i.Codigo
                LEFT JOIN catexamenesmedicos as j ON i.TipoExamen = j.ID
                WHERE (
                    ('' = a.CodigoP AND
                    a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                    
                    OR
                    ('' = g.CodigoP AND
                    g.CodigoP = f.CodPaciente AND
                    f.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
               )
                ORDER BY d.fechaYHora ASC LIMIT $inicio,$registros";

            // g.CodigoP as CodigoPacienteExamen, g.CodPersona as CodigoPersonaExamen,
            // h.Nombres as NombresPacienteExamen, h.Apellidos as ApellidosPacienteExamen, 
            // h.Fecha_de_nacimiento as FechaNacimientoExamen, h.Cedula as CedulaExamen,

            // LEFT JOIN tblpaciente as g ON f.CodPaciente = g.CodigoP
            // LEFT JOIN tblpersona as h ON  g.CodPersona = h.Codigo

            // (g.CodigoP = f.CodPaciente AND
            // f.idServicio = d.idServiciosBrindados AND
            // (d.estadoServicio = 1 OR d.estadoServicio = 2))

            ///// GROUP BY CodigoP


            $EsConsulta = false;
            $consulta = "SELECT DISTINCT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                b.Fecha_de_nacimiento as FechaNacimiento, b.Cedula as Cedula,

                d.tipoServicio

                FROM catservicios as e 
                INNER JOIN tblserviciosbrindados as d ON e.idServicio = d.tipoServicio
                LEFT JOIN tblconsulta as c ON d.idServiciosBrindados = c.idServicio 
                
                LEFT JOIN tblexamen as f ON d.idServiciosBrindados = f.idServicio
                LEFT JOIN tblrecetaexamen as i ON f.RecetaPrevia = i.Codigo
                LEFT JOIN catexamenesmedicos as j ON i.TipoExamen = j.ID

                LEFT JOIN tblventafarmacia as k ON d.idServiciosBrindados = k.servicio
                LEFT JOIN tblrecetamedicamentos as l ON k.recetaMedica = l.Codigo
                LEFT JOIN tblconsulta as m ON l.CodigoConsulta = m.Codigo

                LEFT JOIN tblpaciente as a ON c.CodPaciente = a.CodigoP OR f.CodPaciente = a.CodigoP OR m.CodPaciente = a.CodigoP
                LEFT JOIN tblpersona as b ON  a.CodPersona = b.Codigo
                WHERE (
                    (a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                    OR
                    (a.CodigoP = f.CodPaciente AND
                    f.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                    OR
                    (a.CodigoP = m.CodPaciente AND
                    m.Codigo = l.CodigoConsulta AND
                    l.Codigo = k.recetaMedica AND
                    k.servicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                )
                GROUP BY CodigoP
                ORDER BY d.fechaYHora ASC LIMIT $inicio,$registros";
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
                    <tr class="text-center" >
                        <th>CÓDIGO PACIENTE</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>EDAD</th>
                        <th>CEDULA</th>
                        <th>COBRAR</th>
                    </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            foreach ($datos as $rows) {

                // if($rows['tipoServicio'] == 2)//EL tipo de consulta
                // {
                $tabla .= '
                        <tr class="text-center" >
                            <th>' . $rows['CodigoPaciente'] . '</th>
                            <th>' . $rows['NombresPaciente'] . '</th>
                            <th>' . $rows['ApellidosPaciente'] . '</th>
                            <th>' . $edad = mainModel::calculaedad($rows['FechaNacimiento']) . '</th>
                            <th>' . $rows['Cedula'] . '</th>
                            '
                    . '
                            <td>
                                <a href="' . SERVERURL . 'cajaServicios-list/1/' . $rows['CodigoPaciente']
                    . '"data-toggle="tooltip" title="Cobrar">
                                <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                </a>
                            </td>
                            '
                    . '    
                        ';
                // }
                // else//El tipo de examen
                // {
                //     $tabla.='
                //     <tr class="text-center" >
                //         <th>'.$rows['CodigoPacienteExamen'].'</th>
                //         <th>'.$rows['NombresPacienteExamen'].'</th>
                //         <th>'.$rows['ApellidosPacienteExamen'].'</th>
                //         <th>'.$edad=mainModel::calculaedad($rows['FechaNacimientoExamen']).'</th>
                //         <th>'.$rows['CedulaExamen'].'</th>
                //         '
                //         .'
                //         <td>
                //             <a href="'.SERVERURL.'cajaServicios-list/1/'.$rows['CodigoPacienteExamen']
                //             .'"data-toggle="tooltip" title="Cobrar">
                //             <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                //             </a>
                //         </td>
                //         '
                //         .'    
                //     ';
                // }

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
    } //Termina controlador buscador paciente

    public function paginador_serviciosPaciente_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $condRadio, $condRadio2, $codPaciente, $datosPaciente)
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

        if ((isset($busqueda) && $busqueda != "") || $condRadio != "" || $condRadio2 != "") {
            $EsConsulta = true;

            $consulta = "SELECT SQL_CALC_FOUND_ROWS a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento, b.Cedula as Cedula
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        WHERE ((CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                        OR (a.CodigoP LIKE '$busqueda') 
                        OR (INSS LIKE '$busqueda') 
                        OR (b.Cedula LIKE '$busqueda') 
                        OR (b.Telefono LIKE '$busqueda') 
                        OR (b.Email LIKE '$busqueda') 
                        OR (b.Nombres LIKE '%$busqueda%') 
                        OR (b.Apellidos LIKE '%$busqueda%')) ";


            if ($_SESSION['condRadio'] != "") {
                $consulta = $consulta . "AND (b.Genero = " . $_SESSION['condRadio'] . ") ";
            }

            if ($_SESSION['condRadio2'] != "") {
                $consulta = $consulta . "AND (b.Estado = " . $_SESSION['condRadio2'] . ") ";
            }

            $consulta = $consulta . "ORDER BY CodigoP DESC LIMIT $inicio,$registros";
        } else {
            //DEVUELVE LOS PACIENTES QUE TIENEN SERVICIOS POR PAGAR
            $EsConsulta = false;

            //LEFT JOIN tbldetpagoservicios as g ON d.idServiciosBrindados = g.ServicioBrindado 
            $consulta = "SELECT SQL_CALC_FOUND_ROWS
                d.idServiciosBrindados,
                d.tipoServicio,
                d.estadoServicio,
                d.MontoServicio as MontoTblServicio,
                d.fechaYHora as FHservicio,
                e.nombreServicio,
                e.PrecioGeneral as precioServicio,
                j.Nombre as nombreExamen,
                j.Precio as precioCatExamen
                FROM catservicios as e 
                INNER JOIN tblserviciosbrindados as d ON e.idServicio = d.tipoServicio
                LEFT JOIN tblconsulta as c ON d.idServiciosBrindados = c.idServicio 
                LEFT JOIN tblpaciente as a ON c.CodPaciente = a.CodigoP
                LEFT JOIN tblpersona as b ON  a.CodPersona = b.Codigo 
                LEFT JOIN tblexamen as f ON d.idServiciosBrindados = f.idServicio
                LEFT JOIN tblpaciente as g ON f.CodPaciente = g.CodigoP
                LEFT JOIN tblpersona as h ON  g.CodPersona = h.Codigo
                LEFT JOIN tblrecetaexamen as i ON f.RecetaPrevia = i.Codigo
                LEFT JOIN catexamenesmedicos as j ON i.TipoExamen = j.ID
                
                LEFT JOIN tblventafarmacia as k ON d.idServiciosBrindados = k.servicio
                LEFT JOIN tblrecetamedicamentos as l ON k.recetaMedica = l.Codigo
                LEFT JOIN tblconsulta as m ON l.CodigoConsulta = m.Codigo
                LEFT JOIN tblpaciente as n ON m.CodPaciente = n.CodigoP
                LEFT JOIN tblpersona as o ON n.CodPersona = o.Codigo

                WHERE (
                    ('" . $codPaciente . "' = a.CodigoP AND
                    a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                    
                    OR
                    ('" . $codPaciente . "' = g.CodigoP AND
                    g.CodigoP = f.CodPaciente AND
                    f.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))

                    OR
                    ('" . $codPaciente . "' = n.CodigoP AND
                    n.CodigoP = m.CodPaciente AND
                    m.Codigo = l.CodigoConsulta AND
                    l.Codigo = k.recetaMedica AND
                    k.servicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
               )
                ORDER BY d.fechaYHora ASC LIMIT $inicio,$registros";
        }

        /* LA MEJOR ES ESTA YA DETECTA LOS EXAMENES PERO MULTIPLICA LAS DE consulta
            SELECT SQL_CALC_FOUND_ROWS

                d.idServiciosBrindados,
                d.tipoServicio,
                d.estadoServicio,
                d.MontoServicio,
                d.fechaYHora as FHservicio,
                e.nombreServicio,
                e.PrecioGeneral as precioServicio
                FROM catservicios as e 
                INNER JOIN tblserviciosbrindados as d ON e.idServicio = d.tipoServicio 
                LEFT JOIN tblconsulta as c ON d.idServiciosBrindados = c.idServicio 
                LEFT JOIN tblpaciente as a ON c.CodPaciente = a.CodigoP
                LEFT JOIN tblpersona as b ON  a.CodPersona = b.Codigo 
                LEFT JOIN tblexamen as f ON d.idServiciosBrindados = f.idServicio
                LEFT JOIN tblpaciente as g ON f.CodPaciente = g.CodigoP
                LEFT JOIN tblpersona as h ON  a.CodPersona = b.Codigo
                WHERE (
                    ('1' = a.CodigoP AND
                    a.CodigoP = c.CodPaciente AND
                    c.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
                    
                    OR
                    ('1' = g.CodigoP AND
                    g.CodigoP = f.CodPaciente AND
                    f.idServicio = d.idServiciosBrindados AND
                    (d.estadoServicio = 1 OR d.estadoServicio = 2))
               )
                ORDER BY d.fechaYHora ASC
            */

        /*Se establece la conexión con la bd */
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetchAll();
        $total = $conexion->query("SELECT FOUND_ROWS()");
        $total = (int) $total->fetchColumn();

        $obtenerMetodosPago = mainModel::ejecutar_consulta_simple("SELECT idMetodoPago, NombreMetodoPago FROM catmetodosdepago");
        $obtenerMetodosPago = $obtenerMetodosPago->fetchAll();
        //echo "<script>alert('holis ".$total."')</script>";

        $Npaginas = ceil($total / 100);
        //ction="'.SERVERURL.'cajaServiciosCobro-list"
        $tabla .= '
            
            <div class="table-responsive">
            <form class="form-neon formSinEstilo FormularioAjax" action="' . SERVERURL . 'ajax/cajaAjax.php" method="POST" data-form= "save" autocomplete="off">
            <input type="hidden" name="nombrePersona" value="' . $datosPaciente['Nombres'] . '">
            <input type="hidden" name="apellidoPersona" value="' . $datosPaciente['Apellidos'] . '">
            <input type="hidden" name="cedulaPersona" value="' . $datosPaciente['Cedula'] . '">
            <div class="container-fluid">
                <!-- style="background-color: red;" -->
                <div class="row d-flex justify-content-center">
                    <div class="col-md-5 col-10" >
                        <input id="cbPagaPaciente" class="form-check-input" type="checkbox" name="cbPagaPaciente" style="margin-left: 5px;" checked>
                        <label for="cbPagaPaciente" class="form-check-label" style="margin-left: 30px;">¿PAGA ÉL MISMO?</label>
                        
                        <div id="NoPagaElMismo">
                            <div class="form-group" title="FORMATO: NOMBRE__APELLIDO_CÉDULA">

                                <label for="cliente_pago_reg" class="bmd-label-floating">NOMBRE CLIENTE A PAGAR &nbsp; <span class="badge bg-warning rounded-pill position-absolute top-0 start-100 translate-middle" style="font-size:0.68em; background-color: #ffc107!important; color: black; ">AUTOCOMPLETADO</span></label>
                                <div class="autocompletar">
                                    <input type="text" class="form-control" name="cliente_pago_reg" id="cliente_pago_reg" maxlength="150">
                                </div>
                            </div>
                            
                            <!-- <button type="button" class="btn btn-primary">AGREGAR CLIENTE NO REGISTRADO</button> -->
                            <br>
                            <!-- <button type="button" data-bs-toggle="modal" data-bs-target="#clientesNewModal" class="btn btn-raised btn-success btn-sm"><i class="fas fa-plus"></i> &nbsp; AGREGAR CLIENTE NO REGISTRADO</button> -->
                        </div>
                        
                    </div>
                    <div class="col-md-5 col-10 d-flex justify-content-center">
                        <div>
                        <h5>Seleccione un método de pago</h5>
                    ';

        foreach ($obtenerMetodosPago as $metodoPago) {
            $tabla .= '<div class="form-check">
                                    <input class="form-check-input" type="radio" name="metodoPago" id="metodo' . $metodoPago['idMetodoPago'] . '" value="' . $metodoPago['idMetodoPago'] . '" required>
                                    <label class="form-check-label" for="metodo' . $metodoPago['idMetodoPago'] . '">
                                    ' . $metodoPago['NombreMetodoPago'] . '
                                    </label>
                                </div>';
        }

        $tabla .= '<br></div></div>
                    
                    
                </div>

                <div class="row">
                    
                    <div class="col" style="padding: 0;">

                    
                <table class="table table-dark table-sm">
                    <thead>
                    <tr class="text-center" >
                        <th>ID</th>
                        <th>TIPO SERVICIO</th>
                        <th>FECHA Y HORA</th>
                        <th>TOTAL SERVICIO</th>
                        <th>REBAJA</th>
                        <th>TOTAL BRUTO</th>
                        <th>COBRO FINAL</th>
                        
                        <th>SELEC.</th>
                        <th></th><!-- AQUI IRIA DETALLES -->
                    </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador1 = 0;
            foreach ($datos as $rows) {
                $contador1++;

                $DetallesDePagoDeServicio = mainModel::ejecutar_consulta_simple("SELECT a.idDetPago, a.Monto as MontoDetalle, a.RebajaPago as RebajaDetalle
                        FROM tbldetpagoservicios as a 
                        INNER JOIN tblserviciosbrindados as b ON a.ServicioBrindado = b.idServiciosBrindados
                        WHERE (b.idServiciosBrindados = '" . $rows['idServiciosBrindados'] . "')");

                $montoPagadoServicio = 0;
                $rebajaTotalServicio = 0;
                $banderaDetalles = false;
                if ($DetallesDePagoDeServicio->rowCount() > 0) {
                    $banderaDetalles = true; //SI TIENE DETALLES ES TRUE
                    $DetallesDePagoDeServicio = $DetallesDePagoDeServicio->fetchAll(PDO::FETCH_ASSOC);

                    //foreach para determinar el monto total pagado en este servicio
                    $montoPagadoServicio = 0;
                    $rebajaTotalServicio = 0;
                    foreach ($DetallesDePagoDeServicio as $detallePago) {
                        $montoPagadoServicio += (float)$detallePago['MontoDetalle'];
                        $rebajaTotalServicio += (float)$detallePago['RebajaDetalle'];
                    }
                }


                if ($rows['nombreServicio'] == 'Consulta general' || $rows['nombreServicio'] == 'Venta medicamentos') {
                    $tabla .= '
                        <tr class="text-center" id="' . $rows['idServiciosBrindados'] . '">
                            <input class="" contador="' . $contador1 . '" type="hidden" value="' . $rows['idServiciosBrindados'] . '" name="idServiciosBrindados[]">
                            <td>' . $contador1 . '</td>
                            <td>' . $rows['nombreServicio'] . '</td>
                            <td>' . $rows['FHservicio'] . '</td>
                            <td class="preciosServicios" contador="' . $contador1 . '">' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio']) . '</td>
                            <td><input class="inputRebajaCaja" contador="' . $contador1 . '" type="number" value="0.00" name="rebajaServicio[]" maxlength="10" min="0" step="0.01" contador="' . $contador1 . '"></td>
                            <td class="totalCaja" contador="' . $contador1 . '">' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio']) . '</td>
                            <td><input class="inputCobroCaja" type="number" value="' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio']) . '" name="cobroServicio[]" maxlength="10" min="0" step="0.01" contador="' . $contador1 . '"></td>    
                            '
                        . '
                            <!-- <td>
                                <a href="' . SERVERURL . 'cajaServicios-list/1/' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio'])
                        . '"data-toggle="tooltip" title="Cobrar">
                                <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                </a>
                            </td> -->
                            '
                        . '
                            <td>
                            <input class="checkboxsCaja" type="checkbox" name="cbServicio[]" contador="' . $contador1 . '" value="' . $contador1 . '" checked>
                            </td>
                            '
                        . '    
                    ';
                } else {
                    //examenes (ANTES QUE NO SE GUARDARA EL MONTO EN SERVICIOS) 
                    //     $tabla.='
                    //     <tr class="text-center" id="'.$rows['idServiciosBrindados'].'">
                    //         <input class="" contador="'.$contador1.'" type="hidden" value="'.$rows['idServiciosBrindados'].'" name="idServiciosBrindados[]">
                    //         <td>'.$contador1.'</td>
                    //         <td>Examen '. (($rows['nombreExamen'] == NULL) ? '' : $rows['nombreExamen']) .'</td>
                    //         <td>'.$rows['FHservicio'].'</td>
                    //         <td class="preciosServicios" contador="'.$contador1.'">'. (($rows['precioCatExamen'] == NULL) ? $rows['precioServicio'] : $rows['precioCatExamen']) .'</td>
                    //         <td><input class="inputRebajaCaja" contador="'.$contador1.'" type="number" value="0.00" name="rebajaServicio[]" maxlength="10" min="0" step="0.01" contador="'.$contador1.'"></td>
                    //         <td class="totalCaja" contador="'.$contador1.'">'. (($rows['precioCatExamen'] == NULL) ? $rows['precioServicio'] : $rows['precioCatExamen'])  .'</td>
                    //         <td><input class="inputCobroCaja" type="number" value="'. (($rows['precioCatExamen'] == NULL) ? $rows['precioServicio'] : $rows['precioCatExamen'])  .'" name="cobroServicio[]" maxlength="10" min="0" step="0.01" contador="'.$contador1.'"></td>    
                    //         '
                    //         .'
                    //         <td>
                    //             <a href="'.SERVERURL.'cajaServicios-list/1/'. (($rows['precioCatExamen'] == NULL) ? $rows['precioServicio'] : $rows['precioCatExamen']) 
                    //             .'"data-toggle="tooltip" title="Cobrar">
                    //             <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                    //             </a>
                    //         </td>
                    //         '
                    //         .'
                    //         <td>
                    //         <input class="checkboxsCaja" type="checkbox" name="cbServicio[]" contador="'.$contador1.'" value="'.$contador1.'" checked >
                    //         </td>
                    //         '
                    //         .'    
                    // ';

                    //EXAMENES AHORA   
                    $tabla .= '
                        <tr class="text-center" id="' . $rows['idServiciosBrindados'] . '">
                            <input class="" contador="' . $contador1 . '" type="hidden" value="' . $rows['idServiciosBrindados'] . '" name="idServiciosBrindados[]">
                            <td>' . $contador1 . '</td>
                            <td>Examen ' . (($rows['nombreExamen'] == NULL) ? '' : $rows['nombreExamen']) . '</td>
                            <td>' . $rows['FHservicio'] . '</td>
                            <td class="preciosServicios" contador="' . $contador1 . '">' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio']) . '</td>
                            <td><input class="inputRebajaCaja" contador="' . $contador1 . '" type="number" value="0.00" name="rebajaServicio[]" maxlength="10" min="0" step="0.01" contador="' . $contador1 . '"></td>
                            <td class="totalCaja" contador="' . $contador1 . '">' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio'])  . '</td>
                            <td><input class="inputCobroCaja" type="number" value="' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio'])  . '" name="cobroServicio[]" maxlength="10" min="0" step="0.01" contador="' . $contador1 . '"></td>    
                            '
                        . '
                            <!-- <td>
                                <a href="' . SERVERURL . 'cajaServicios-list/1/' . (($banderaDetalles == true) ? ((float)$rows['MontoTblServicio'] - (float)$montoPagadoServicio - (float)$rebajaTotalServicio) : $rows['MontoTblServicio'])
                        . '"data-toggle="tooltip" title="Cobrar">
                                <i class="fas fa-file-medical" style="font-size:30px; color: orange"></i>
                                </a>
                            </td>-->
                            '
                        . '
                            <td>
                            <input class="checkboxsCaja" type="checkbox" name="cbServicio[]" contador="' . $contador1 . '" value="' . $contador1 . '" checked >
                            </td>
                            '
                        . '    
                    ';
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

        //TFOOT
        $tabla .= '

            
                    <tfoot id="tfootCaja">
                    <tr class="text-center" >
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>TOTAL SERVICIOS</th>
                        <th>TOTAL REBAJA</th>
                        <th>TOTAL BRUTO</th>
                        <th>COBRO FINAL</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr class="text-center" >
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><input id="totalServicios" name="totalServicios" class="inputSinEstilo" type="number" readonly></th>
                        <th><input id="totalRebaja" name="totalRebaja" class="inputSinEstilo" type="number" readonly></th>
                        <th><input id="totalTotal" name="totalTotal" class="inputSinEstilo" type="number" readonly></th>
                        <th><input id="totalFinal" name="totalFinal" class="inputSinEstilo" type="number" readonly></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>

            ';

        $tabla .= '</tbody></table>
            <p class="text-center" style="margin-top: 20px;">
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; COBRAR</button>
            </p>
                    </div><!-- Antes de comenzar el col de la tabla  -->
                    </div>

                </div>
            </form>
            </div>
            ';
        // if($total>=1 && $pagina<=$Npaginas){

        //     $tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);

        // }
        return $tabla;
    } //Termina controlador sserviciosPaciente

    public function datosPacienteCaja($idPaciente)
    {
        $consulta = "SELECT Nombres, Apellidos, Cedula from tblpersona
            INNER JOIN tblpaciente ON tblpersona.Codigo = tblpaciente.CodPersona
            WHERE tblpaciente.CodigoP = '" . $idPaciente . "'";
        $conexion = mainModel::conectar();
        $datos = $conexion->query($consulta);
        $datos = $datos->fetch();
        return $datos;
    }

    public function cobroServicios2()
    {
        require_once "../config/APP.php";
        header("Location: " . SERVERURL . "cajaServiciosCobro-list");
        exit();
    }

    //Aqui termino
    public function agregar_cliente_controlador()
    {

        $Cedula = mainModel::limpiar_cadena($_POST['cliente_cedula_reg']);
        $Estado_civil = mainModel::limpiar_cadena($_POST['item_civil_reg']);
        $Telefono = mainModel::limpiar_cadena($_POST['cliente_telefono_reg']);
        $Email = mainModel::limpiar_cadena($_POST['cliente_correo_reg']);
        /*Tabla cliente */
        //$INSS=mainModel::limpiar_cadena($_POST['cliente_inss_reg']);


        if ($Cedula == "" || $Estado_civil == "" || $Telefono == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Verificando integridad de cedula -----------------*/
        if (mainModel::verificar_datos("[a-zA-Z0-9- ]{16,16}", $Cedula)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La cédula no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de INSS -----------------*/
        // if(mainModel::verificar_datos("[0-9-]{9,9}",$INSS)){
        //     $alerta=[
        //         "Alerta"=>"simple",
        //         "Titulo"=>"Ocurrió un error inesperado",
        //         "Texto"=>"El codigo INSS no coinicide con el formato solicitado",
        //         "Tipo"=>"error"
        //     ];
        //     echo json_encode($alerta);
        //     exit();
        // }

        /*-----------------Comprobando Estado civil-----------------*/
        if ($Estado_civil < 1 || $Estado_civil > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un estado civil valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando INSS (Solo puede existir uno)-----------------*/
        // $check_INSS=mainModel::ejecutar_consulta_simple("SELECT INSS FROM tblcliente WHERE INSS='$INSS'");
        // if($check_INSS->rowCount()>0){
        //     $alerta=[
        //         "Alerta"=>"simple",
        //         "Titulo"=>"Ocurrió un error inesperado",
        //         "Texto"=>"El INSS ingresado ya está registrado en el sistema",
        //         "Tipo"=>"error"
        //     ];
        //     echo json_encode($alerta);
        //     exit();
        // }
        /*-----------------Verificando integridad de teléfono -----------------*/

        if (mainModel::verificar_datos("[0-9#-+ ]{8,15}", $Telefono)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El Telefono no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Comprobando EMAIL-----------------*/
        if ($Email != "") {
            if (filter_var($Email, FILTER_VALIDATE_EMAIL)) {
                $check_email = mainModel::ejecutar_consulta_simple("SELECT Email FROM tblpersona WHERE Email='$Email'");
                if ($check_email->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "El Email ingresado ya está registrado en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El Email ingresado no es valido",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        //Aquí termina caso de mayor -- Esto de abajo ocurre siempre
        $Nombres = mainModel::limpiar_cadena($_POST['cliente_nombre_reg']);
        $Apellidos = mainModel::limpiar_cadena($_POST['cliente_apellido_reg']);
        $Fecha_de_nacimiento = mainModel::limpiar_cadena($_POST['cliente_nacio_reg']);
        $Genero = mainModel::limpiar_cadena($_POST['item_genero_reg']);
        $Direccion = mainModel::limpiar_cadena($_POST['cliente_direccion_reg']);

        if ($Nombres == "" || $Apellidos == "" || $Fecha_de_nacimiento == "" || $Genero == "" || $Direccion == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*          VALIDACIONES DE DATOS aquí tocó luis */
        /*-----------------Verificando integridad de nombre -----------------*/

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ()1-9 ]{3,60}", $Nombres)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El nombre no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de nombre -----------------*/
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,60}", $Apellidos)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El apellido no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando Genero-----------------*/
        if ($Genero < 1 || $Genero > 3) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Seleccione un genero valido",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*------------------VALIDANDO EDAD------------------------- */
        if (mainModel::calculaedad($Fecha_de_nacimiento) <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La persona ingresada no es mayor de edad",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*-----------------Verificando integridad de Dirección -----------------*/
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}", $Direccion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La dirección no coinicide con el formato solicitado",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        /*De alguna manera aquí se tendrá que validar que el tutor existe y se le tendrá que asignar el chatel
                En caso de que se cumpla y exista el tutor y se le haya asignado se procede*/

        //Se cargan datos según caso

        /*Datos por enviar persona */
        $datos_persona_reg = [
            "Cedula" => $Cedula,
            "Nombres" => $Nombres,
            "Apellidos" => $Apellidos,
            "Fecha_de_nacimiento" => $Fecha_de_nacimiento,
            "Genero" => $Genero,
            "Estado_civil" => $Estado_civil,
            "Direccion" => $Direccion,
            "Telefono" => $Telefono,
            "Email" => $Email,
            "Estado" => 1

        ];

        $agregar_persona = cajaModelo::agregar_persona_modelo($datos_persona_reg);
        /*Datos por enviar paciente */

        $obtener_codigo_persona2 = cajaModelo::obtener_codigo2(0);

        $codigoPersona = cajaModelo::obtener_persona_modelo($datos_persona_reg);
        $row2 = $codigoPersona->fetch();/*ti*/
        $codigoPersona = $row2['Codigo'];

        $datos_cliente_reg = [
            "CodPersona" => $codigoPersona
        ];


        $agregar_cliente = cajaModelo::agregar_cliente_modelo($datos_cliente_reg);
        if ($agregar_persona->rowCount() == 1 && $agregar_cliente->rowCount() == 1) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cliente registrado",
                "Texto" => "Cliente registrado correctamente",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el cliente",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador}

    public function agregar_cobro_controlador()
    {
        session_start(['name' => 'SPM']);
        //Limpieza de variables
        $nombrePersona = mainModel::limpiar_cadena($_POST['nombrePersona']);
        $apellidoPersona = mainModel::limpiar_cadena($_POST['apellidoPersona']);
        $cedulaPersona = mainModel::limpiar_cadena($_POST['cedulaPersona']);
        //$cbPagaPaciente = mainModel::limpiar_cadena($_POST['cbPagaPaciente']);
        $cliente_pago_reg = mainModel::limpiar_cadena($_POST['cliente_pago_reg']);
        $metodoPago = mainModel::limpiar_cadena($_POST['metodoPago']);

        //echo json_encode('<script>console.log("simona "+'.count($_POST['cbServicio']).');</script>');
        //ya
        // $_POST['idServiciosBrindados'][] = $_POST['idServiciosBrindados'];
        // $_POST['rebajaServicio'][] = $_POST['rebajaServicio'];
        // $_POST['cobroServicio'][] = $_POST['cobroServicio'];
        // $_POST['cbServicio'][] = $_POST['cbServicio'];

        //Hacer validaciones de la integridad de los datos

        /*-----------------Verificando integridad de buscador de cliente -----------------*/
        if (!isset($_POST['cbPagaPaciente'])) {
            if (mainModel::verificar_datos("[a-zA-Z0-9.ñÑ\- ]+[_]{2}[a-zA-Z0-9.ñÑ\- ]+[_]{2}[a-zA-Z0-9.ñÑ\- ]+", $cliente_pago_reg)) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "El cliente a pagar no coinicide con el formato solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /*-----------------Verificando integridad del metodo de pago seleccionado -----------------*/
        $verificarMetodoPago = mainModel::ejecutar_consulta_simple("SELECT idMetodoPago FROM catmetodosdepago WHERE idMetodoPago = '" . $metodoPago . "'");
        if ($verificarMetodoPago->rowCount() < 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "El método de pago ingresado no existe en nuestros registros.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //SE DEBE VALIDAR TAMBIEN LA INTEGRIDAD DE LOS DATOS NUMERICOS

        //CREAR RECIBO

        //Buscar el codigo de empleado del empleado en sesion activa  

        $empleadoCaja = mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "'");
        $empleadoCaja = $empleadoCaja->fetch();
        $empleadoCaja = $empleadoCaja['Codigo'];

        //Buscar la ultima apertura de caja activa de ese empleado
        $AperturaCaja = mainModel::ejecutar_consulta_simple("SELECT MAX(idApertura) FROM tblaperturacaja as a
            INNER JOIN catcaja as b ON a.Caja = b.idCaja
            WHERE EmpleadoCaja='$empleadoCaja'
            AND EstadoCaja='1'");

        $AperturaCaja = $AperturaCaja->fetch(); //ti
        $AperturaCaja = $AperturaCaja['MAX(idApertura)'];

        //Operador ternario en base si paga el mismo paciente u otro cliente
        $datosCliente = (isset($_POST['cbPagaPaciente'])) ? ($nombrePersona . '__' . $apellidoPersona . '__' . $cedulaPersona) : ($cliente_pago_reg);

        //Obtencion del codigo de cliente
        $datosCliente = explode('__', $datosCliente); //Nombre_Apellido_Cedula

        $datosClienteArray = [
            "Nombres" => $datosCliente[0],
            "Apellidos" => $datosCliente[1],
            "Cedula" => $datosCliente[2]
        ];

        $CodPersona = cajaModelo::buscarCodPersonaCliente($datosClienteArray);
        if ($CodPersona->rowCount() < 1) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No hay una persona registrada con los datos de cliente enviados",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $CodPersona = $CodPersona->fetch(); //ti
        $CodPersona = $CodPersona['Codigo'];

        $Cliente = mainModel::ejecutar_consulta_simple("SELECT idCliente FROM tblclientes WHERE CodPersona = '" . $CodPersona . "'");
        if ($Cliente->rowCount() < 1) {
            //Si no hay un cliente registrado crearlo
            mainModel::ejecutar_consulta_simple("INSERT INTO tblclientes(CodPersona) VALUES(" . $CodPersona . ")");
            try {
                $Cliente = mainModel::ejecutar_consulta_simple("SELECT idCliente FROM tblclientes as a
                    INNER JOIN tblpersona as b ON a.CodPersona = b.Codigo 
                    WHERE Cedula = '" . $datosCliente[2] . "'");
            } catch (\Throwable $th) {
                //Por si falla
                // $Cliente=mainModel::ejecutar_consulta_simple("SELECT idCliente FROM tblclientes as a
                // INNER JOIN tblpersona as b ON a.CodPersona = b.Codigo
                // WHERE Cedula = '001-091001-1001k'");
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Alerta de seguridad",
                    "Texto" => "Su tiempo de espera al está fuera del límite establecido, por favor recargue la página por motivos de seguridad.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        $Cliente = $Cliente->fetch(); //ti
        $Cliente = $Cliente['idCliente'];

        $datosRecibo = [
            "Cliente" => $Cliente,
            "aperturaCaja" => $AperturaCaja
        ];

        $agregar_recibo = cajaModelo::agregar_recibo_modelo($datosRecibo);
        if ($agregar_recibo->rowCount() == 1) {
            //Si el recibo fue agregado satisfactoriamente
            $NumeroRecibo = mainModel::ejecutar_consulta_simple("SELECT MAX(idRecibo) FROM tblrecibosventa 
                WHERE ((Cliente = '" . $Cliente . "') AND (aperturaCaja = '" . $AperturaCaja . "'))");
            if ($NumeroRecibo->rowCount() < 1) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se encontró un recibo que coincidiera con los datos enviados.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $NumeroRecibo = $NumeroRecibo->fetch(); //ti
            $NumeroRecibo = $NumeroRecibo['MAX(idRecibo)'];

            $contadorServicios = 0;

            foreach ($_POST['cbServicio'] as $checkbox) {
                //Menos uno por que el contador en el listado comienza en 1
                // if ((((int)$checkbox) - 1) == $contadorServicios)
                // {
                $datosDetPagoServicio = [
                    "ServicioBrindado" => $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)],
                    "Monto" => $_POST['cobroServicio'][(((int)$checkbox) - 1)],
                    "RebajaPago" => $_POST['rebajaServicio'][(((int)$checkbox) - 1)],
                    "metodoDePago" => $metodoPago,
                    "NumeroRecibo" => $NumeroRecibo
                ];

                $AgregarDetPagoServicio = cajaModelo::AgregarDetPagoServicioModelo($datosDetPagoServicio);
                if ($AgregarDetPagoServicio->rowCount() != 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logró ingresar el detalle del pago del servicio " . $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)] . "",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                ///}//Termina if de si ell checkbox del servicio fue checkeado
                $contadorServicios++;
            } //termina foreach de ingreso de detalles de servicios brindados 1

            //AHORA VIENE EL FOREACH PARA DETERMINAR A QUE ESTADO PASA EL TBLSERVICIOBRINDADO
            $contadorServicios = 0;
            foreach ($_POST['cbServicio'] as $checkbox) {
                //Menos uno por que el contador en el listado comienza en 1
                // if ((((int)$checkbox) - 1) == $contadorServicios)
                // {

                $MontoTotalServicio = mainModel::ejecutar_consulta_simple("SELECT MontoServicio FROM tblserviciosbrindados 
                        WHERE (idServiciosBrindados = '" . $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)] . "')");
                if ($MontoTotalServicio->rowCount() < 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se encontró el monto del servicio especificado.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $MontoTotalServicio = $MontoTotalServicio->fetch(); //ti
                $MontoTotalServicio = $MontoTotalServicio['MontoServicio'];

                $DetallesDePagoDeServicio = mainModel::ejecutar_consulta_simple("SELECT a.idDetPago, a.Monto as MontoDetalle, a.RebajaPago as RebajaDetalle
                        FROM tbldetpagoservicios as a 
                        INNER JOIN tblserviciosbrindados as b ON a.ServicioBrindado = b.idServiciosBrindados
                        WHERE (b.idServiciosBrindados = '" . $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)] . "')");

                if ($DetallesDePagoDeServicio->rowCount() < 1) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se encontraron detalles de pago de servicio para el servicio especificado.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $DetallesDePagoDeServicio = $DetallesDePagoDeServicio->fetchAll(PDO::FETCH_ASSOC);

                //foreach para determinar el monto total pagado en este servicio
                $montoPagadoServicio = 0;
                $rebajaTotalServicio = 0;
                foreach ($DetallesDePagoDeServicio as $detallePago) {
                    $montoPagadoServicio += (float)$detallePago['MontoDetalle'];
                    $rebajaTotalServicio += (float)$detallePago['RebajaDetalle'];
                }

                if (($montoPagadoServicio + $rebajaTotalServicio) < ((float)$MontoTotalServicio)) {
                    $DetallesDePagoDeServicio = mainModel::ejecutar_consulta_simple("UPDATE tblserviciosbrindados
                            SET estadoServicio = '2', RebajaServicio ='" . $rebajaTotalServicio . "'
                            WHERE (idServiciosBrindados = '" . $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)] . "')");
                } else {
                    $DetallesDePagoDeServicio = mainModel::ejecutar_consulta_simple("UPDATE tblserviciosbrindados
                            SET estadoServicio ='3', RebajaServicio ='" . $rebajaTotalServicio . "'
                            WHERE (idServiciosBrindados = '" . $_POST['idServiciosBrindados'][(((int)$checkbox) - 1)] . "')");
                }

                ///}//Termina if de si ell checkbox del servicio fue checkeado

                $contadorServicios++;
            } //termina foreach de DETERMINAR A QUE ESTADO PASA EL TBLSERVICIOBRINDADO 2

            $alerta = [
                "Alerta" => "redireccion_violenta",
                "Titulo" => "PAGO REGISTRADO",
                "Texto" => "Pago registrado correctamente",
                "Tipo" => "success",
                "URL" => SERVERURL . "recibosCaja-list/"
            ];
            echo json_encode($alerta);
        } //Termina If de si se pudo ingresar el recibo
        else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se logró añadir el número de recibo",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    } //Termina controlador

    public function paginador_recibosVenta_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda, $condRadio, $condRadio2)
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

        if ((isset($busqueda) && $busqueda != "") || $condRadio != "" || $condRadio2 != "") {
            $EsConsulta = true;

            $consulta = "SELECT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento, b.Cedula as Cedula
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        WHERE ((CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                        OR (a.CodigoP LIKE '$busqueda') 
                        OR (INSS LIKE '$busqueda') 
                        OR (b.Cedula LIKE '$busqueda') 
                        OR (b.Telefono LIKE '$busqueda') 
                        OR (b.Email LIKE '$busqueda') 
                        OR (b.Nombres LIKE '%$busqueda%') 
                        OR (b.Apellidos LIKE '%$busqueda%')) ";


            if ($_SESSION['condRadio'] != "") {
                $consulta = $consulta . "AND (b.Genero = " . $_SESSION['condRadio'] . ") ";
            }

            if ($_SESSION['condRadio2'] != "") {
                $consulta = $consulta . "AND (b.Estado = " . $_SESSION['condRadio2'] . ") ";
            }

            $consulta = $consulta . "ORDER BY CodigoP DESC LIMIT $inicio,$registros";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.idRecibo as CodigoReciboVenta, c.Nombres as nombreCliente,
                c.Apellidos as apellidosCliente, c.Cedula as cedulaCliente, 
                f.Nombres as nombreCajero, f.Apellidos as apellidosCajero, a.FyHRegistro as fechaHoraRecibo
                FROM tblrecibosventa as a
                INNER JOIN tblclientes as b ON a.Cliente = b.idCliente 
                INNER JOIN tblpersona as c ON b.CodPersona = c.Codigo

                INNER JOIN tblaperturacaja as d ON a.aperturaCaja = d.idApertura
                INNER JOIN tblempleado as e ON d.EmpleadoCaja = e.Codigo
                INNER JOIN tblpersona as f ON e.CodPersona = f.Codigo
                ORDER BY a.FyHRegistro ASC LIMIT $inicio,$registros";
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
                    <tr class="text-center" >
                        <th>ID RECIBO</th>
                        <th>CLIENTE</th>
                        <th>CÉDULA CLIENTE</th>
                        <th>CAJERO</th>
                        <th>REBAJA TOTAL</th>
                        <th>MONTO TOTAL</th>
                        <th>FECHA Y HORA</th>
                        <th>EXPORTAR</th>
                    </tr>
                    </thead>
                    <tbody>
            ';
        if ($total >= 1 && $pagina <= $Npaginas) {
            foreach ($datos as $rows) {

                $DetallesDePagoDeServicio = mainModel::ejecutar_consulta_simple("SELECT a.idDetPago, a.Monto as MontoDetalle, a.RebajaPago as RebajaDetalle
                        FROM tbldetpagoservicios as a 
                        INNER JOIN tblrecibosventa as b ON a.NumeroRecibo = b.idRecibo
                        WHERE (b.idRecibo = '" . $rows['CodigoReciboVenta'] . "')");

                $montoPagadoServicio = 0;
                $rebajaTotalServicio = 0;
                $banderaDetalles = false;
                if ($DetallesDePagoDeServicio->rowCount() > 0) {
                    $banderaDetalles = true; //SI TIENE DETALLES ES TRUE
                    $DetallesDePagoDeServicio = $DetallesDePagoDeServicio->fetchAll(PDO::FETCH_ASSOC);

                    //foreach para determinar el monto total pagado en este servicio
                    $montoPagadoServicio = 0;
                    $rebajaTotalServicio = 0;
                    foreach ($DetallesDePagoDeServicio as $detallePago) {
                        $montoPagadoServicio += (float)$detallePago['MontoDetalle'];
                        $rebajaTotalServicio += (float)$detallePago['RebajaDetalle'];
                    }
                }

                $tabla .= '
                    <tr class="text-center" >
                        <th>' . $rows['CodigoReciboVenta'] . '</th>
                        <th>' . $rows['nombreCliente'] . " " . $rows['apellidosCliente'] . '</th>
                        <th>' . $rows['cedulaCliente'] . '</th>
                        <th>' . $rows['nombreCajero'] . " " . $rows['apellidosCajero'] . '</th>
                        <th>' . $rebajaTotalServicio . '</th>
                        <th>' . $montoPagadoServicio . '</th>
                        <th>' . $rows['fechaHoraRecibo'] . '</th>
                        '
                    . '
                        <td>
                        <a href="' . SERVERURL . 'Reportes/reporte-u-recibo-caja.php?idRecibo='
                    . $rows['CodigoReciboVenta'] . '" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>
                        '
                    . '    
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
    } //Termina controlador buscador paciente
    public function obtener_recibo_controlador($id)
    {
        return cajaModelo::obtener_recibo_modelo($id);
    } //Termina controlador
}
