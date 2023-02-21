<?php
if ($peticionAjax) {
    require_once "../modelos/ventasFarmaciaModelo.php";
} else {
    require_once "./modelos/ventasFarmaciaModelo.php";
}
class ventasFarmaciaControlador extends ventasFarmaciaModelo
{
    /*-----------------Controlador para agregar receta-----------------*/
    public function agregar_venta_farmacia_controlador()
    {

        $receta = mainModel::limpiar_cadena($_POST['receta_medica_reg']);
        $fechaVenta = mainModel::limpiar_cadena($_POST['fechaVenta_reg']);
        $cantidadReceta = mainModel::limpiar_cadena($_POST['cantidadReceta_reg']);
        $disponibilidad = mainModel::limpiar_cadena($_POST['disponibilidad_reg']);
        $descripcion = mainModel::limpiar_cadena($_POST['descripcion_reg']);
        $precio = mainModel::limpiar_cadena($_POST['precio_reg']);
        $select = mainModel::limpiar_cadena($_POST['select_reg']);

        /*----------------Comprobar campos vacíos -----------------*/
        if ($receta == "" || $fechaVenta == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "No se han llenado los campos obligatorios",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /*-----------------Comprobando que la receta exista-----------------*/
        $receta = explode("__", $receta);
        $verificarRecetaMedica = mainModel::ejecutar_consulta_simple("SELECT a.Codigo from tblrecetamedicamentos as a where a.Codigo='$receta[1]'");
        if ($verificarRecetaMedica->rowCount() == 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error inesperado",
                "Texto" => "La receta medica no esta registrada en el sistema",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        $verificarRecetaMedica = $verificarRecetaMedica->fetch();

        /* Validanos cantidad con disponibilidad */
        $seguir = false;
        if ($disponibilidad <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Inventario en quiebre",
                "Texto" => "No se tiene alguna unidad de este medicamento!",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else if ($cantidadReceta > $disponibilidad) {
            if ($select == "Si") {
                $seguir = true;
                $cantidadReceta = $disponibilidad;
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Inventario en quiebre",
                    "Texto" => "Se tiene menos inventario que el solicitado",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        } else if ($disponibilidad >= $cantidadReceta) {
            $seguir = true;
        }
        if ($seguir) {
            /* Primeramente guardamos el servicio brindado */
            $dataServicio = [
                "tipoServicio" => 3,
                "estadoServicio" => 1,
                "montoServicio" => $precio * $cantidadReceta,
                "rebajaServicio" => 0
            ];
            $agregarServicio = ventasFarmaciaModelo::agregar_servicio_farmacia_modelo($dataServicio);
            if ($agregarServicio->rowCount() == 1) {
                /* Obtenemos el servicio (Ultimo) */
                $ultimoServicio = mainModel::ejecutar_consulta_simple("SELECT MAX(a.idServiciosBrindados) idServiciosBrindados FROM tblserviciosbrindados as a");
                if ($ultimoServicio->rowCount() == 0) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "La receta medica no esta registrada en el sistema",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $ultimoServicio = $ultimoServicio->fetch();
                $dataVenta = [
                    "recetaMedica" => $receta[1],
                    "servicio" => $ultimoServicio['idServiciosBrindados'],
                    "descripcion" => $descripcion,
                    "fechaVenta" => $fechaVenta
                ];
                $agregarVenta = ventasFarmaciaModelo::agregar_venta_farmacia_modelo($dataVenta);
                if ($agregarVenta->rowCount() == 1) {
                    /* Obtenemos la venta (Ultimo) */
                    $ultimaVenta = mainModel::ejecutar_consulta_simple("SELECT max(a.idVentaFarmacia) idVentaFarmacia FROM tblventafarmacia as a");
                    if ($ultimaVenta->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se han registrado ventas",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $ultimaVenta = $ultimaVenta->fetch();
                    /* Obtenemos el detalle de la receta, segun receta */
                    $detalleReceta = mainModel::ejecutar_consulta_simple("SELECT a.Codigo FROM tbldetallereceta as a
                    where a.CodReceta=$receta[1]");
                    if ($detalleReceta->rowCount() == 0) {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se encontro detalle para la receta dada",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                    $detalleReceta = $detalleReceta->fetch();
                    $dataDetalleVenta = [
                        "venta" => $ultimaVenta['idVentaFarmacia'],
                        "detalleReceta" => $detalleReceta['Codigo'],
                        "cantidadVendida" => $cantidadReceta
                    ];
                    $agregarDetalleVenta = ventasFarmaciaModelo::agregar_detalle_venta_farmacia_modelo($dataDetalleVenta);
                    if ($agregarDetalleVenta->rowCount() == 1) {
                        /* Obtenemos el medicamento */
                        $medicamento = mainModel::ejecutar_consulta_simple("SELECT b.Medicamento  FROM tblrecetamedicamentos as a
                        INNER JOIN tbldetallereceta as b on (b.CodReceta=a.Codigo)
                        WHERE a.Codigo=$receta[1];");

                        if ($medicamento->rowCount() == 0) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "No se logro obtener el inventario",
                                "Tipo" => "error"
                            ];
                            echo json_encode($alerta);
                            exit();
                        }
                        $medicamento = $medicamento->fetch();
                        $medicamento = $medicamento['Medicamento'];
                        /*-----------------Verificamos si hay suficiente inventario(En lotes)-----------------*/
                        $verificarInventario = mainModel::ejecutar_consulta_simple("SELECT a.idLote,a.cantidadEnlote FROM tbllotemedicamento as a
                        where a.medicamento=$medicamento AND a.cantidadEnlote > 0 AND a.fechaVence > CURRENT_DATE() 
                        ORDER BY a.fechaVence ASC ");
                        //Si no devuelve ningun resultado es porque no hay ningun lote de este producto, por ende dice que no
                        //hay inventario
                        if ($verificarInventario->rowCount() == 0) {
                            $alerta = [
                                "Alerta" => "simple",
                                "Titulo" => "Ocurrió un error inesperado",
                                "Texto" => "No hay inventario suficiente",
                                "Tipo" => "error"
                            ];
                            echo json_encode($alerta);
                            exit();
                        } else {
                            //Si devuelve mas de 0 fila/s, verificamos cuantas devuelve
                            $verificarInventario = $verificarInventario->fetchAll();
                            $resultado = count($verificarInventario);
                            $proceder1 = false;
                            $proceder2 = false;
                            //Si solo devuelve una fila, verificamos que ese lote cumpla la demanda de productos, sino la cumple
                            //dice que no hay inventario suficiente, en caso que si halla inventario suficiente volvemos true la 
                            //variable $proceder1 para indicar que flujo se realizara posteriormente para la transaccion 
                            if ($resultado == 1) {
                                if ($verificarInventario[0]['cantidadEnlote'] < $cantidadReceta) {
                                    $alerta = [
                                        "Alerta" => "simple",
                                        "Titulo" => "Ocurrió un error inesperado",
                                        "Texto" => "No hay inventario suficiente 2",
                                        "Tipo" => "error"
                                    ];
                                    echo json_encode($alerta);
                                    exit();
                                } else {
                                    $proceder1 = true;
                                    $lotesNecesarios = [];
                                    $lotesNecesarios[0] = $verificarInventario[0]['idLote'];
                                }
                            } else {
                                //Si devuelve mas 1 fila, verificamos que la suma de productos existentes en los lotes cumpla la 
                                //demanda
                                $suma = 0;
                                foreach ($verificarInventario as $lote) {
                                    $suma += $lote['cantidadEnlote'];
                                }
                                //Si la suma de productos en los lotes no cumple la demanda, entonces decimos que no hay inventario
                                //suficiente
                                if ($suma < $cantidadReceta) {
                                    $alerta = [
                                        "Alerta" => "simple",
                                        "Titulo" => "Ocurrió un error inesperado",
                                        "Texto" => "No hay inventario suficiente 3",
                                        "Tipo" => "error"
                                    ];
                                    echo json_encode($alerta);
                                    exit();
                                } else {
                                    //En caso que si halla inventario suficiente, verificamos con cuantos lotes de los devueltos se cumple
                                    //dicha demanda, ademas volvemos true la variable $proceder 2 para indicar el flujo que se llevara
                                    //acabo posteriormente para la transacción
                                    $proceder2 = true;
                                    $suma2 = 0;
                                    $contador = 0;
                                    $lotesNecesarios = [];
                                    foreach ($verificarInventario as $lote) {
                                        $suma2 += $lote['cantidadEnlote'];
                                        if ($suma2 >= $cantidadReceta) {
                                            $contador++;
                                            $lotesNecesarios[$contador - 1] = $lote['idLote'];
                                            break;
                                        } else {
                                            $contador++;
                                            $lotesNecesarios[$contador - 1] = $lote['idLote'];
                                        }
                                    }
                                }
                            }
                            //Verificamos el valor de la variable $proceder1 y $proceder 2 previamente asignados, con el fin de saber
                            //la manera en como proceder con el control del inventario
                            if ($proceder1) {
                                //Si la variable $proceder1 es verdadera entonces se realiza el siguiente flujo que realizara
                                //las modificaciones al inventario teniendo en cuenta que solo se necesito un lote para cumplir la demanda
                                /*Datos por enviar para disminuir el lote*/
                                $datos_disminuir_lote_inventario_reg = [
                                    "idLote" => $lotesNecesarios[0],
                                    "cantidad" => $cantidadReceta
                                ];
                                $disminuirLoteInventario = ventasFarmaciaModelo::disminuir_inventario_lote_farmacia_modelo($datos_disminuir_lote_inventario_reg);
                                //Si la disminucion del lote presenta algun error, entonces mandamos un mensaje
                                if ($disminuirLoteInventario->rowCount() == 0) {
                                    $alerta = [
                                        "Alerta" => "simple",
                                        "Titulo" => "Ocurrió un error inesperado",
                                        "Texto" => "No se logró disminuir el lote",
                                        "Tipo" => "error"
                                    ];
                                    echo json_encode($alerta);
                                    exit();
                                }
                            } else if ($proceder2) {
                                //En caso que la varuable $proceder1 no sea verdadera y la variable $proceder2 si lo sea, realizamos
                                //el flujo para modificar los inventarios teniendo en cuenta que se afecta mas de 1 lote, para lograr
                                //sastifacer la demanda
                                $totalDisminuido = 0;
                                //Re realiza las modificaciones a los lotes necesarios para cumplir la demanda, este numero de lotes
                                //necesario esta grabado en la variable $contador
                                for ($a = 1; $a <= $contador; $a++) {
                                    if ($a != $contador) {
                                        $disminuir = $verificarInventario[$a - 1]['cantidadEnlote'];
                                        $totalDisminuido += $disminuir;
                                    } else {
                                        $disminuir = $cantidadReceta - $totalDisminuido;
                                    }

                                    $datos_disminuir_lote_inventario_reg = [
                                        "idLote" => $lotesNecesarios[$a - 1],
                                        "cantidad" => $disminuir
                                    ];
                                    $disminuirLoteInventario = ventasFarmaciaModelo::disminuir_inventario_lote_farmacia_modelo($datos_disminuir_lote_inventario_reg);
                                    //Si la disminucion de lote presento un error, entonces, mostramos un mensaje
                                    if ($disminuirLoteInventario->rowCount() == 0) {
                                        $alerta = [
                                            "Alerta" => "simple",
                                            "Titulo" => "Ocurrió un error inesperado",
                                            "Texto" => "No se logró disminuir el lote 2",
                                            "Tipo" => "error"
                                        ];
                                        echo json_encode($alerta);
                                        exit();
                                    }
                                }
                            }
                            $alerta = [
                                "Alerta" => "recargar",
                                "Titulo" => "Venta registrada",
                                "Texto" => "Venta registrada correctamente",
                                "Tipo" => "success"
                            ];
                            echo json_encode($alerta);
                        }
                    } else {
                        $alerta = [
                            "Alerta" => "simple",
                            "Titulo" => "Ocurrió un error inesperado",
                            "Texto" => "No se logró añadir el detalle de venta",
                            "Tipo" => "error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                } else {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Ocurrió un error inesperado",
                        "Texto" => "No se logró añadir la venta",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Ocurrió un error inesperado",
                    "Texto" => "No se logró añadir el servicio",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
    } //termina controlador

    /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/
    public function paginador_ventas_farmacia_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda)
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
            $consulta = "SELECT a.idVentaFarmacia,a.descripcion,a.fechaVenta,b.cantidadVendida,d.nombreComercial FROM tblventafarmacia as a
            INNER JOIN tbldetalleventafarmacia as b on (b.ventaFarmacia=a.idVentaFarmacia)
            INNER JOIN tbldetallereceta as c on (c.Codigo=b.detalleRecetaMedica)
            INNER JOIN catmedicamentos as d on (d.Codigo=c.Medicamento)
                WHERE (a.idVentaFarmacia LIKE '$busqueda')
                ORDER BY a.idVentaFarmacia DESC LIMIT $inicio,$registros;";
        } else {
            $EsConsulta = false;
            $consulta = "SELECT a.idVentaFarmacia,a.descripcion,a.fechaVenta,b.cantidadVendida,d.nombreComercial FROM tblventafarmacia as a
            INNER JOIN tbldetalleventafarmacia as b on (b.ventaFarmacia=a.idVentaFarmacia)
            INNER JOIN tbldetallereceta as c on (c.Codigo=b.detalleRecetaMedica)
            INNER JOIN catmedicamentos as d on (d.Codigo=c.Medicamento)
                ORDER BY a.idVentaFarmacia DESC LIMIT $inicio,$registros;";
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
                                <th>VENTA</th>
								<th>Descripción</th>
								<th>Fecha venta</th>
                                <th>Medicamento</th>
                                <th>Cantidad</th>
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
                                <td>' . $rows['idVentaFarmacia'] . '</td>
                                <td>' . $rows['descripcion'] . '</td>
                                <td>' . $rows['fechaVenta'] . '</td>
                                <td>' . $rows['nombreComercial'] . '</td>
                                <td>' . $rows['cantidadVendida'] . '</td>
                                
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
    public function obtener_venta_controlador($id)
    {
        return ventasFarmaciaModelo::obtener_venta_modelo($id);
    } //Termina controlador
}
