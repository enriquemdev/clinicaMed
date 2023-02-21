<?php
if ($peticionAjax) {
    require_once "../modelos/inventarioFarmaciaModelo.php";
} else {
    require_once "./modelos/inventarioFarmaciaModelo.php";
}
class inventarioFarmaciaControlador extends inventarioFarmaciaModelo
{

    /*-----------------Controlador para paginar inventario-------------------*/
    public function paginador_inventario_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.Codigo,a.nombreComercial,b.precioVenta FROM catmedicamentos as a
            INNER JOIN tblmedicamentoprecio as b on (b.medicamento=a.Codigo)
                WHERE (a.nombreComercial LIKE '%$busqueda%')
                ORDER BY a.Codigo ASC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.Codigo,a.nombreComercial,b.precioVenta FROM catmedicamentos as a
            INNER JOIN tblmedicamentoprecio as b on (b.medicamento=a.Codigo)
                ORDER BY a.Codigo ASC LIMIT $inicio,$registros;";
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
                                <th>ID</th>
								<th>Nombre</th>
                                <th>Stock</th>
                                <th>Stock minimo</th>
								<th>Precio/Unidad</th>
                                <th>ESTADO</th>
                                <th>REABASTECER</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        //Aqui termino
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $medicamento2 = $rows['Codigo'];
                $stockMinimo = 30;/* $rows['stockMinimo']; */
                /*-----------------Obtenemos el stock del medicamento-----------------*/
                $cantidadTotal = mainModel::ejecutar_consulta_simple("SELECT SUM(a.cantidadEnlote) cantidadEnlote FROM tbllotemedicamento as a 
                WHERE a.medicamento=$medicamento2 AND a.fechaVence > CURRENT_DATE();");
                if ($cantidadTotal->rowCount() == 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logro obtener el stock del medicamento",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $cantidadTotal = $cantidadTotal->fetch();
                $cantidadTotal = $cantidadTotal['cantidadEnlote'];
                if ($cantidadTotal == null) {
                    $cantidadTotal = 0;
                }

                if ($cantidadTotal <= $stockMinimo) {
                    $Estado = "Acabado";
                } else if ($cantidadTotal <= ($stockMinimo + ($stockMinimo * 0.1)) && $cantidadTotal > $stockMinimo) {
                    $Estado = "Acabandose";
                } else {
                    $Estado = "Utilizable";
                }

                $tabla .= '
                        <tr class="text-center" >
                            <td>' . $rows['Codigo'] . '</td>
                            <td>' . $rows['nombreComercial'] . '</td>
                            <td>' . $cantidadTotal . '</td>
                            <td>' . $stockMinimo . '</td>
                            <td>' . $rows['precioVenta'] . '</td>';
                if ($Estado == "Acabado") {
                    $tabla .= '<td><span class="badge badge-danger">' . $Estado . '</span></td>';
                    $tabla .= '<td>
                                <a class="animated flash" href="' . SERVERURL . 'compras-solicitud-farmacia-new/' . mainModel::encryption($rows['Codigo']) //Generar diagnostico de consulta
                        . '"data-toggle="tooltip" title="Reabastecer!"">
                                <i class="fa-solid fa-truck" style="font-size:30px;color:#da0909;"></i>
                                </a></td>';
                } else if ($Estado == "Acabandose") {
                    $tabla .= '<td><span class="badge badge-warning">' . $Estado . '</span></td>';
                    $tabla .= '<td>
                                <a class="animated flash" href="' . SERVERURL . 'compras-solicitud-farmacia-new/' . mainModel::encryption($rows['Codigo']) //Generar diagnostico de consulta
                        . '"data-toggle="tooltip" title="Reabastecer!"">
                                <i class="fa-solid fa-truck"  style="font-size:30px;color:#e73f15;"></i>
                                </a></td>';
                } else if ($Estado == "Utilizable") {
                    $tabla .= '<td><span class="badge badge-success">' . $Estado . '</span></td>';
                    $tabla .= '<td><span class="badge badge-success">No necesario</span></td>';
                } else {
                    $tabla .= '<td><span class="badge badge-dark"> WTF </span></td>';
                    $tabla .= '<td><span class="badge badge-dark">WTF</span></td>';
                }
                '
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
            if ($total == 1) {
            } else {
                /* //Aqui manoseo steven
                    $tabla.='<div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <a href="../Reportes/reporte-g-inventario-farmacia.php" 
                        target="_blank"   
                        class="btn btn btn-info">
                        <i class="fas fa-file-pdf"></i> &nbsp; Generar reporte general</a>
                    </p>
                    </div>';
                    //Aqui termino */
            }
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        return $tabla;
    }
    /*-----------------Controlador para paginar lotes de inventario-------------------*/
    public function paginador_inventario_lotes_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.idLote,a.medicamento,a.fechaVence,a.cantidadEnlote,b.nombreComercial FROM tbllotemedicamento as a
            INNER JOIN catmedicamentos as b on (b.Codigo=a.medicamento)
                WHERE (a.idLote LIKE '%$busqueda%')
                OR (b.nombreComercial LIKE '%$busqueda%')
                ORDER BY a.fechaVence ASC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.idLote,a.medicamento,a.fechaVence,a.cantidadEnlote,b.nombreComercial FROM tbllotemedicamento as a
            INNER JOIN catmedicamentos as b on (b.Codigo=a.medicamento)
                ORDER BY a.fechaVence ASC LIMIT $inicio,$registros;";
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
                                <th>LOTE</th>
								<th>PRODUCTO</th>
								<th>CANTIDAD</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>VENCEN EN (DIAS)</th>
								<th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        //Aqui termino
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $idLote = $rows['idLote'];
                /*-----------------Dias para vencerce-----------------*/
                $diasVence = mainModel::ejecutar_consulta_simple("SELECT TIMESTAMPDIFF(DAY, CURRENT_DATE(), 
                a.fechaVence) AS dias_transcurridos from tbllotemedicamento as a where a.idLote='$idLote';");
                if ($diasVence->rowCount() == 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logro obtener el stock del medicamento",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $diasVence = $diasVence->fetch();
                $diasVence = $diasVence['dias_transcurridos'];

                $ESTADOLOTE = 4;
                if ($diasVence < 1) {
                    $ESTADOLOTE = "VENCIDO";
                    $estadoIndex = 0;
                } else if ($diasVence > 0 && $diasVence <= 7) {
                    $ESTADOLOTE = "CASI VENCE";
                    $estadoIndex = 1;
                } else if ($diasVence > 7 && $diasVence <= 15) {
                    $ESTADOLOTE = "VENCE PRONTO";
                    $estadoIndex = 2;
                } else if ($diasVence > 15) {
                    $ESTADOLOTE = "UTILIZABLE";
                    $estadoIndex = 3;
                } else {
                    $ESTADOLOTE = "WTF";
                    $estadoIndex = 4;
                }

                //Aqui manoseo steven
                $tabla .= '
                        <tr class="text-center" >
                            <td>' . $rows['idLote'] . '</td>
                            <td>' . $rows['nombreComercial'] . '</td>
                            <td>' . $rows['cantidadEnlote'] . '</td>
                            <td>' . $rows['fechaVence'] . '</td>
                            <td>' . $diasVence . '</td>';
                if ($estadoIndex == 0) {
                    $tabla .= '<td><span class="badge badge-dark">' . $ESTADOLOTE . '</span></td>';
                } else if ($estadoIndex == 1) {
                    $tabla .= '<td><span class="badge badge-danger">' . $ESTADOLOTE . '</span></td>';
                } else if ($estadoIndex == 2) {
                    $tabla .= '<td><span class="badge  badge-warning">' . $ESTADOLOTE . '</span></td>';
                } else if ($estadoIndex == 3) {
                    $tabla .= '<td><span class="badge badge-success">' . $ESTADOLOTE . '</span></td>';
                } else {
                    $tabla .= '<td><span class="badge badge-dark">' . $ESTADOLOTE . '</span></td>';
                }
                ';
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
            if ($total == 1) {
            } else {
                /* //Aqui manoseo steven
                    $tabla.='<div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <a href="../Reportes/reporte-g-inventario-farmacia.php" 
                        target="_blank"   
                        class="btn btn btn-info">
                        <i class="fas fa-file-pdf"></i> &nbsp; Generar reporte general</a>
                    </p>
                    </div>';
                    //Aqui termino */
            }
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        return $tabla;
    }
    public function paginador_agregar_lote_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.idDetSolicitudCompra,a.medicamento,a.cantidad,b.nombreComercial,c.fechaRecibido FROM tbldetsolicitudcompra as a
            INNER JOIN catmedicamentos as b on (b.Codigo=a.medicamento)
            INNER JOIN tblcompra as c on(c.idCompra=a.solicitudCompra)
            WHERE c.estadoCompra=6
                AND (a.idDetSolicitudCompra LIKE '%$busqueda%')
                OR (B.nombreComercial LIKE '%$busqueda%')
                ORDER BY c.fechaRecibido DESC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.idDetSolicitudCompra,a.medicamento,a.cantidad,b.nombreComercial,c.fechaRecibido FROM tbldetsolicitudcompra as a
            INNER JOIN catmedicamentos as b on (b.Codigo=a.medicamento)
            INNER JOIN tblcompra as c on(c.idCompra=a.solicitudCompra)
            WHERE c.estadoCompra=6
                ORDER BY c.fechaRecibido ASC LIMIT $inicio,$registros;";
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
                                <th>DET. VENTA</th>
                                <th>Medicamento</th>
								<th>Cantidad</th>
								<th>Fecha recibido</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        //Aqui termino
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $totalMostrar = 0;
            foreach ($datos as $rows) {
                /*-----------------Obteniendo el medicamento-----------------*/
                $detCompra = $rows['idDetSolicitudCompra'];
                $modelo = mainModel::ejecutar_consulta_simple("SELECT SUM(a.asgindadoYa) asgindadoYa FROM tblasignacionlote as a
                WHERE a.detSoliCompra=$detCompra;");
                $mostrar = true;
                if ($modelo->rowCount() > 0) {
                    $modelo = $modelo->fetch();
                    if ($modelo['asgindadoYa'] >= $rows['cantidad']) {
                        $mostrar = false;
                    }
                    $rows['cantidad'] = $rows['cantidad'] - $modelo['asgindadoYa'];
                }
                //Aqui manoseo steven
                if ($mostrar) {
                    $tabla .= '
                    <tr class="text-center" >
                        <td>' . $rows['idDetSolicitudCompra'] . '</td>
                        <td>' . $rows['nombreComercial'] . '</td>
                        <td>' . $rows['cantidad'] . '</td>
                        <td>' . $rows['fechaRecibido'] . '</td>
                        <td>
                            <a href="' . SERVERURL . 'inventario-agregar-lote-farmacia/' . $rows['idDetSolicitudCompra'] . '/' . $rows['cantidad'] . '"data-toggle="tooltip" title="Agregar!"">
                                <i class="fa-solid fa-cart-flatbed" style="font-size:30px;"></i>
                            </a>
                        </td>
                    </tr>
                ';
                    $contador++;
                    $totalMostrar++;
                }
            }
            if ($totalMostrar == 0) {
                $tabla .= '<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
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
    /*-----------------Controlador para paginar inventario-------------------*/
    public function paginador_inventario_rop_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.Codigo,a.nombreComercial,b.precioVenta FROM catmedicamentos as a
            INNER JOIN tblmedicamentoprecio as b on (b.medicamento=a.Codigo)
                ORDER BY a.Codigo DESC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.Codigo,a.nombreComercial,b.precioVenta FROM catmedicamentos as a
            INNER JOIN tblmedicamentoprecio as b on (b.medicamento=a.Codigo)
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
								<th>Nombre</th>
                                <th>Stock</th>
                                <th>PROVEEDOR</th>
                                <th>TIEMPO ESPERA</th>
								<th>Pedido</th>
                                <th>ROP</th>
                            </tr>
                        </thead>
                        <tbody>
            ';
        //Aqui termino
        if ($total >= 1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $rows) {
                $medicamento2 = $rows['Codigo'];
                /*-----------------Obtenemos las ventas de los ultimos 30 dias de los lotes-----------------*/
                $ventasTotales = mainModel::ejecutar_consulta_simple("SELECT sum(b.cantidadVendida) as VentasTotales
                FROM tblventafarmacia as a 
                INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.ventaFarmacia)
                INNER JOIN tblrecetamedicamentos as c on (c.Codigo=a.recetaMedica)
                INNER JOIN tbldetallereceta as d on (d.CodReceta=c.Codigo)
                INNER JOIN catmedicamentos as e on (e.Codigo=d.Medicamento)
                WHERE e.Codigo='$medicamento2' AND a.fechaVenta BETWEEN DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND CURDATE();");
                if ($ventasTotales->rowCount() == 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logro obtener las ventas del medicamento",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $ventasTotales = $ventasTotales->fetch();
                /*-----------------Obtenemos el stock de los lotes-----------------*/
                $cantidadTotal = mainModel::ejecutar_consulta_simple("SELECT sum(a.cantidadEnlote) as CantidadTotal  FROM tbllotemedicamento as a 
                where a.medicamento='$medicamento2' AND a.fechaVence > CURRENT_DATE();");
                if ($cantidadTotal->rowCount() == 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logro obtener el stock del medicamento",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $cantidadTotal = $cantidadTotal->fetch();
                $cantidadTotal = $cantidadTotal['CantidadTotal'];
                if ($cantidadTotal == null) {
                    $cantidadTotal = 0;
                }
                if ($EsConsulta) {
                    /*-----------------Obtenemos el menor tiempo de espera de los proveedores-----------------*/
                    $menorTiempoEspera = mainModel::ejecutar_consulta_simple("SELECT a.tiempoEntrega leadtime FROM tblproveedores as a where a.idProveedor='$busqueda';");
                    if ($menorTiempoEspera->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro obtener el stock del medicamento 2",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $menorTiempoEspera = $menorTiempoEspera->fetch();
                    $leadTime = $menorTiempoEspera['leadtime']; //Lead Time mas corto

                    /*-----------------Obtenemos el menor tiempo de espera de los proveedores-----------------*/
                    $menorTiempoEspera2 = mainModel::ejecutar_consulta_simple("SELECT a.nombreProveedor FROM tblproveedores as a where a.idProveedor='$busqueda';");
                    if ($menorTiempoEspera2->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro obtener el stock del medicamento 2",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $menorTiempoEspera2 = $menorTiempoEspera2->fetch();
                    $proveedor = $menorTiempoEspera2['nombreProveedor']; //Lead Time mas corto
                } else {
                    /*-----------------Obtenemos el menor tiempo de espera de los proveedores-----------------*/
                    $menorTiempoEspera = mainModel::ejecutar_consulta_simple("SELECT MIN(a.tiempoEntrega) as L,a.nombreProveedor FROM tblproveedores as a;");
                    if ($menorTiempoEspera->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro obtener el stock del medicamento 1",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $menorTiempoEspera = $menorTiempoEspera->fetchAll();
                    $leadTime = $menorTiempoEspera[0]['L']; //Lead Time mas corto
                    $proveedor = $menorTiempoEspera[0]['nombreProveedor']; //Lead Time mas corto
                }

                if ($ventasTotales['VentasTotales'] > 0) {
                    /*-----------------Obtenemos el stock minimo del producto-----------------*/
                    /* Parte ignorada momentaneamente hasta que metamos en alguna tabla el stock minimo */
                    /* $stockMinimo = mainModel::ejecutar_consulta_simple("SELECT a.stockMinimo from catmedicamentos as a 
                    where a.Codigo='$medicamento2';");
                    if ($stockMinimo->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logro obtener el stock minimo del medicamento",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $stockMinimo = $stockMinimo->fetch(); */
                    $stockMinimo = 30;
                    //Datos
                    $demandaMes = $ventasTotales['VentasTotales']; //Demanda neta de los ultimos 30 dias (Incluye dia actual)
                    $demandaPromedioDiaria = $ventasTotales['VentasTotales'] / 30; //Promedio de la demanda (Diario)
                    /* echo("Demanda diaria: ".$demandaPromedioDiaria); */
                    $demandaLeadTime = $demandaPromedioDiaria * $leadTime; //Demanda durante el tiempo de espera
                    $pedido = $ventasTotales['VentasTotales']; //Asignamos la cantidad optimna de pedido, como la demanda mesnual
                    /* $puntoReorden = ceil($demandaLeadTime + $stockMinimo['stockMinimo']); *//* Omitido igual */
                    $puntoReorden = ceil($demandaLeadTime + $stockMinimo);

                    $tabla .= '
                        <tr class="text-center" >
                            <td>' . $rows['Codigo'] . '</td>
                            <td>' . $rows['nombreComercial'] . '</td>
                            <td>' . $cantidadTotal . '</td>
                            <td>' . $proveedor . '</td>
                            <td>' . $leadTime . '</td>
                            <td>' . $pedido . '</td>
                            <td>' . $puntoReorden . '</td>';
                    '
                        </tr>';
                    $contador++;
                } else {
                    $tabla .= '
                        <tr class="text-center" >
                            <td>' . $rows['Codigo'] . '</td>
                            <td>' . $rows['nombreComercial'] . '</td>
                            <td>' . $cantidadTotal . '</td>
                            <td>' . $proveedor . '</td>
                            <td>' . $leadTime . '</td>
                            <td>No aplica</td>
                            <td>No aplica</td>';
                    '
                        </tr>';
                    $contador++;
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

        $tabla .= '</tbody></table></div>';
        if ($total >= 1 && $pagina <= $Npaginas) {
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        return $tabla;
    }
    public function agregar_lote_controlador($data)
    {
        /*-----------------Obteniendo el medicamento-----------------*/
        $detCompra = $data['detalleSolicitud'];
        $modelo = mainModel::ejecutar_consulta_simple("SELECT a.medicamento,a.laboratorio,a.cantidad FROM tbldetsolicitudcompra as a
        WHERE a.idDetSolicitudCompra=$detCompra");

        if ($modelo->rowCount() > 0) {
            $modelo = $modelo->fetch();
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "Error de prueba",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        if ($data['cantidadLote'] > $data['cantidadEspera'] || $data['cantidadLote'] <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La cantidad ingresada no es valida",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $medicamento = $modelo['medicamento'];
        $laboratorio = $modelo['laboratorio'];

        /* Verificacion de datos del medicamento. Proveedor, Laboratorio */
        $fechaVence = $data['fechaVence'];
        $modelo = mainModel::ejecutar_consulta_simple("SELECT a.detSoliCompra,b.medicamento,b.proveedor,b.laboratorio
        ,c.fechaVence,a.lote
        FROM tblasignacionlote as a
        INNER JOIN tbldetsolicitudcompra as b on (b.idDetSolicitudCompra=a.detSoliCompra)
        INNER JOIN tbllotemedicamento as c on (c.idLote=a.lote)
        WHERE b.medicamento=$medicamento AND b.laboratorio=$laboratorio");

        $similitud = false;
        $lote = null;
        if ($modelo->rowCount() > 0) {
            $modelo = $modelo->fetch();
            if ($fechaVence == $modelo['fechaVence']) {
                $lote = $modelo['lote'];
                $similitud = true;
            }
        }

        /* Validamos datos */
        if ($similitud) {
            /* Mandamos */
            $modelo = inventarioFarmaciaModelo::actualizar_lote_modelo($lote, $data['cantidadLote']);
            if ($modelo->rowCount() > 0) {
                $info = [
                    "detCompra" => $detCompra,
                    "lote" => $lote,
                    "cantidad" => $data['cantidadLote']
                ];
                $modelo = inventarioFarmaciaModelo::agregar_asignacion_lote_modelo($info);
                if ($modelo->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "redireccion_violenta",
                        "Titulo" => "Proceso realizado",
                        "Texto" => "Se ha agregado el lote",
                        "Tipo" => "success",
                        "URL" => SERVERURL . "inventario-agregar-lote-farmacia-list/"
                    ];
                    echo json_encode($alerta);
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logró efectuar la compra",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró efectuar la compra. 1",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } else {
            /* Mandamos */
            $info = [
                "medicamento" => $medicamento,
                "fechaVence" => $data['fechaVence'],
                "cantidad" => $data['cantidadLote']
            ];
            $modelo = inventarioFarmaciaModelo::agregar_lote_modelo($info);
            if ($modelo->rowCount() > 0) {
                /* Obtenemos ultimo lote */
                $modelo = mainModel::ejecutar_consulta_simple("SELECT MAX(a.idLote) idLote FROM tbllotemedicamento as a;");
                if ($modelo->rowCount() > 0) {
                    $modelo = $modelo->fetch();
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "Error",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $info = [
                    "detCompra" => $detCompra,
                    "lote" => $modelo['idLote'],
                    "cantidad" => $data['cantidadLote']
                ];
                $modelo = inventarioFarmaciaModelo::agregar_asignacion_lote_modelo($info);
                if ($modelo->rowCount() > 0) {
                    $alerta = [
                        "Alerta" => "redireccion_violenta",
                        "Titulo" => "Proceso realizado",
                        "Texto" => "Se ha agregado el lote",
                        "Tipo" => "success",
                        "URL" => SERVERURL . "inventario-agregar-lote-farmacia-list/"
                    ];
                    echo json_encode($alerta);
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logró efectuar la compra",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró efectuar la compra. 1",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    }
    //Aqui manoseo steven
    public function datos_proveedores_controlador()
    {
        return inventarioFarmaciaModelo::datos_proveedores_modelo();
    }
}
