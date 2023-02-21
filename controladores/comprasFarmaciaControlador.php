<?php
if ($peticionAjax) {
    require_once "../modelos/comprasFarmaciaModelo.php";
} else {
    require_once "./modelos/comprasFarmaciaModelo.php";
}

class comprasFarmaciaControlador extends comprasFarmaciaModelo
{
    public function datos_proveedores_controlador()
    {
        return comprasFarmaciaModelo::datos_proveedores_modelo();
    }/*Fin de controlador */

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador_compras_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.idCompra, a.estadoCompra,a.nota,a.fechaRecibido,b.fechaRegistro,c.nombreEstado FROM tblcompra as a 
            INNER JOIN tblsolicitudcompra as b on(b.idSolicitudCompra=a.solicitudCompra)
            INNER JOIN catestadocompra as c on (c.idEstadoCompra=a.estadoCompra)
                WHERE (a.idCompra LIKE '$busqueda')
                OR (c.nombreEstado LIKE '$busqueda')
                ORDER BY a.idCompra DESC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.idCompra, a.estadoCompra,a.nota,a.fechaRecibido,b.fechaRegistro,c.nombreEstado FROM tblcompra as a 
            INNER JOIN tblsolicitudcompra as b on(b.idSolicitudCompra=a.solicitudCompra)
            INNER JOIN catestadocompra as c on (c.idEstadoCompra=a.estadoCompra)
                ORDER BY a.idCompra DESC LIMIT $inicio,$registros;";
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
								<th>Nota</th>
								<th>Estado</th>
								<th>Fecha compra</th>
                                <th>Fecha recibido</th>
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
                                <td>' . $rows['idCompra'] . '</td>
                                <td>' . $rows['nota'] . '</td>
                                <td>' . $rows['nombreEstado'] . '</td>
                                <td>' . $rows['fechaRegistro'] . '</td>
                                <td>' . $rows['fechaRecibido'] . '</td>
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
            if ($total == 1) {
            } else {
                //Aqui manoseo steven
                /* $tabla.='<div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <a href="../Reportes/reporte-g-compras-farmacia.php" 
                        target="_blank"   
                        class="btn btn btn-info">
                        <i class="fas fa-file-pdf"></i> &nbsp; Generar reporte general</a>
                    </p>
                    </div>'; */
                //Aqui termino
            }
            $tabla .= mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        return $tabla;
    }
    /* Paginador de las solicitudes de compra */
    public function paginador_compras_solicitud_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        /* session_start(['name' => 'SPM']); */
        if ($_SESSION['cargo_spm'] == 5 || $_SESSION['cargo_spm'] == 7 || $_SESSION['cargo_spm'] == 8) {
            if (isset($busqueda) && $busqueda != "") {
                $EsConsulta = true;
                /* Pendiente consulta de buisqueda */
                $consulta = "SELECT a.idSolicitudCompra,a.solicitante,c.Nombres,c.Apellidos,a.estadoSolicitud,d.NombreEstado,a.fechaRegistro,a.descripcionSolicitud 
                FROM tblsolicitudcompra as a 
                INNER JOIN tblempleado as b on (b.Codigo=a.solicitante)
                INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
                INNER JOIN catestadocompra as d on (d.idEstadoCompra=a.estadoSolicitud)
                WHERE a.estadoSolicitud=1 AND a.idSolicitudCompra='$busqueda'
                    ORDER BY a.idSolicitudCompra DESC LIMIT $inicio,$registros;";
            } else {
                $EsConsulta = false;
                $consulta = "SELECT a.idSolicitudCompra,a.solicitante,c.Nombres,c.Apellidos,a.estadoSolicitud,d.NombreEstado,a.fechaRegistro,a.descripcionSolicitud 
                FROM tblsolicitudcompra as a 
                INNER JOIN tblempleado as b on (b.Codigo=a.solicitante)
                INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
                INNER JOIN catestadocompra as d on (d.idEstadoCompra=a.estadoSolicitud)
                WHERE a.estadoSolicitud=1
                    ORDER BY a.idSolicitudCompra DESC LIMIT $inicio,$registros;";
            }
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
								<th>Solicitante</th>
								<th>Estado</th>
								<th>Fecha</th>
                                <th>Ver</th>';
        if ($_SESSION['cargo_spm'] == 5) {
            $tabla .= '<th>Aceptar</th>
                                    <th>Rechazar</th>';
        }

        $tabla .= '</tr>
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
                                <td>' . $rows['idSolicitudCompra'] . '</td>
                                <td>' . $rows['Nombres'] . ' ' . $rows['Apellidos'] . '</td>
                                <td>' . $rows['NombreEstado'] . '</td>
                                <td>' . explode(" ", $rows['fechaRegistro'])[0] . '</td>
                                <td>
                                <a href="' . SERVERURL . 'Reportes/reporte-u-solicitud-compras-farmacia.php?idSolicitud='
                    . $rows['idSolicitudCompra'] . '" 
                                target="_blank"  
                                class="btn btn-info">
                                <i class="fas fa-file-pdf"></i>
                                </a>
                                </td>';


                if ($_SESSION['cargo_spm'] == 5) {
                    $tabla .= '
                    <td><a class="autorizarSolicitud cursorPointer btn btn-info"><i class=" fas fa-check-circle" style="color: green;"></i></a></td>
                    <td><a class="denegarSolicitud cursorPointer btn btn-info"><i class="fas fa-times-circle" style="color: red;"></i></a></td>';
                }

                $tabla .= '
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
    public function paginador_compras_recibir_mercancia_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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

        /* session_start(['name' => 'SPM']); */
        if ($_SESSION['cargo_spm'] == 8 || $_SESSION['cargo_spm'] == 7) {
            if (isset($busqueda) && $busqueda != "") {
                $EsConsulta = true;
                /* Pendiente consulta de buisqueda */
                $consulta = "SELECT a.idSolicitudCompra,a.solicitante,c.Nombres,c.Apellidos,a.estadoSolicitud,d.NombreEstado,a.fechaRegistro,a.descripcionSolicitud 
                FROM tblsolicitudcompra as a 
                INNER JOIN tblempleado as b on (b.Codigo=a.solicitante)
                INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
                INNER JOIN catestadocompra as d on (d.idEstadoCompra=a.estadoSolicitud)
                WHERE a.estadoSolicitud=2 AND a.idSolicitudCompra='$busqueda'
                    ORDER BY a.idSolicitudCompra DESC LIMIT $inicio,$registros;";
            } else {
                $EsConsulta = false;
                $consulta = "SELECT a.idSolicitudCompra,a.solicitante,c.Nombres,c.Apellidos,a.estadoSolicitud,d.NombreEstado,a.fechaRegistro,a.descripcionSolicitud 
                FROM tblsolicitudcompra as a 
                INNER JOIN tblempleado as b on (b.Codigo=a.solicitante)
                INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
                INNER JOIN catestadocompra as d on (d.idEstadoCompra=a.estadoSolicitud)
                WHERE a.estadoSolicitud=2
                    ORDER BY a.idSolicitudCompra DESC LIMIT $inicio,$registros;";
            }
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
								<th>Solicitante</th>
								<th>Estado</th>
								<th>Fecha</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
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
                                <td>' . $rows['idSolicitudCompra'] . '</td>
                                <td>' . $rows['Nombres'] . $rows['Apellidos'] . '</td>
                                <td>' . $rows['NombreEstado'] . '</td>
                                <td>' . explode(" ", $rows['fechaRegistro'])[0] . '</td>
                                <td>' . $rows['descripcionSolicitud'] . '</td>
                                
                                <td>
                                <a href="' . SERVERURL . 'compras-recibir-mercancia-farmacia/' . $rows['idSolicitudCompra'] . '" class="btn btn-success">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                                    </a>
                                ';

                $tabla .= '
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
    public function obtener_laboratorios_controlador()
    {
        return comprasFarmaciaModelo::obtener_laboratorios_modelo();
    }
    public function agregar_solicitud_compra_controlador($descripcion, $matriz)
    {
        session_start(['name' => 'SPM']);
        $usuarioActual = $_SESSION['id_spm'];
        /*-----------------Obteniendo el codigo de empleado del usuario actual-----------------*/
        $empleado = mainModel::ejecutar_consulta_simple("SELECT b.Codigo FROM tblusuarios as a
        INNER JOIN tblempleado as b on (b.CodPersona=a.CodPersonaU)
        WHERE a.Codigo='$usuarioActual'");

        if ($empleado->rowCount() > 0) {
            $empleado = $empleado->fetch();
        } else {
            $repuesta['repuesta'] = [
                "estado" => "error"
            ];
            return $repuesta;
        }

        $data = [
            "Solicitante" => $empleado['Codigo'],
            "Estado" => 1,
            "Descripcion" => $descripcion
        ];
        $agregarSolicitud = comprasFarmaciaModelo::agregar_solicitud_compra_modelo($data);
        if ($agregarSolicitud->rowCount() > 0) {
            $solicitudCompra = comprasFarmaciaModelo::obtener_ultima_solicitud_compra_modelo();
            if ($solicitudCompra->rowCount() > 0) {
                $solicitudCompra = $solicitudCompra->fetch();
            } else {
                $repuesta['repuesta'] = [
                    "estado" => "error"
                ];
                return $repuesta;
            }
            foreach ($matriz as $detalle) {
                $info = [
                    "Medicamento" => $detalle['Medicamento'],
                    "Proveedor" => $detalle['Proveedor'],
                    "Laboratorio" => $detalle['Laboratorio']
                ];
                $precioMedicamento = comprasFarmaciaModelo::obtener_precio_medicamento_modelo($info);
                if ($precioMedicamento->rowCount() > 0) {
                    $precioMedicamento = $precioMedicamento->fetch();
                } else {
                    $repuesta['repuesta'] = [
                        "estado" => "error"
                    ];
                    return $repuesta;
                }
                $data2 = [
                    "SolicitudCompra" => $solicitudCompra['idSolicitudCompra'],
                    "Medicamento" => $detalle['Medicamento'],
                    "Proveedor" => $detalle['Proveedor'],
                    "Laboratorio" => $detalle['Laboratorio'],
                    "Cantidad" => $detalle['Cantidad'],
                    "Costo" => $precioMedicamento['precioMedicamento'],
                ];
                $agregarDetalleSolicitud = comprasFarmaciaModelo::agregar_detalle_solicitud_compra_modelo($data2);
                if ($agregarDetalleSolicitud->rowCount() > 0) {
                } else {
                    $repuesta['repuesta'] = [
                        "estado" => "error"
                    ];
                    return $repuesta;
                }
            }
            $repuesta['repuesta'] = [
                "estado" => "exito"
            ];
            return $repuesta;
        } else {
            $repuesta['repuesta'] = [
                "estado" => "error"
            ];
            return $repuesta;
        }
    }
    public function efectuar_compra_controlador($data)
    {
        $estado = 0;
        switch ($data['tipoRecibido']) {
            case 1:
                $estado = 6;
                break;
            case 2:
                $estado = 7;
                break;
            case 3:
                $estado = 8;
                break;
        }
        $modelo = comprasFarmaciaModelo::cambiar_estado_solicitud_compra_modelo($data['solicitudCompra'], $estado);
        if ($modelo->rowCount() > 0) {
            $modelo = comprasFarmaciaModelo::efectuar_compra_modelo($data, $estado);
            if ($modelo->rowCount() > 0) {
                if ($estado == 6) {
                    $alerta = [
                        "Alerta" => "redireccion_violenta",
                        "Titulo" => "Compra realizada",
                        "Texto" => "Se ha recibido la mercancia de los proveedores",
                        "Tipo" => "success",
                        "URL" => SERVERURL . "compras-recibir-mercancia-farmacia-list/"
                    ];
                    echo json_encode($alerta);
                } else if ($estado == 7) {
                    $alerta = [
                        "Alerta" => "redireccion_violenta",
                        "Titulo" => "Compra en espera",
                        "Texto" => "Se ha recibido parte de la mercancia de los proveedores. En espera de entrega completa",
                        "Tipo" => "success",
                        "URL" => SERVERURL . "compras-recibir-mercancia-farmacia-list/"
                    ];
                    echo json_encode($alerta);
                } else if ($estado == 8) {
                    $alerta = [
                        "Alerta" => "redireccion_violenta",
                        "Titulo" => "La compra no se efectuo",
                        "Texto" => "La mercancia sera devuelta a los proveedores",
                        "Tipo" => "success",
                        "URL" => SERVERURL . "compras-recibir-mercancia-farmacia-list/"
                    ];
                    echo json_encode($alerta);
                }
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
    public function autorizar_solicitud_compra_controlador($id)
    {
        $autorizacion = comprasFarmaciaModelo::autorizar_solicitud_compra_modelo($id);
        if ($autorizacion->rowCount() > 0) {
            $repuesta['repuesta'] = [
                "estado" => "exito"
            ];
            return $repuesta;
        } else {
            $repuesta['repuesta'] = [
                "estado" => "error"
            ];
            return $repuesta;
        }
    }
    public function denegar_solicitud_compra_controlador($id)
    {
        $denegacion = comprasFarmaciaModelo::denegar_solicitud_compra_modelo($id);
        if ($denegacion->rowCount() > 0) {
            $repuesta['repuesta'] = [
                "estado" => "exito"
            ];
            return $repuesta;
        } else {
            $repuesta['repuesta'] = [
                "estado" => "error"
            ];
            return $repuesta;
        }
    }
    public function obtener_solicitud_compra_controlador($id)
    {
        return comprasFarmaciaModelo::obtener_solicitud_compra_modelo($id);
    }
}
