<?php
$peticionAjax = true;
require_once "../config/APP.php";
if (isset($_POST['descripcion']) && isset($_POST['matrizDetalleSolicitud'])) {
    /* Insttancia al controlador */
    require_once "../controladores/comprasFarmaciaControlador.php";
    $ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();
    /* obtencion datos */
    $descripcion = $_POST['descripcion'];
    $matriz = $_POST['matrizDetalleSolicitud'];
    echo json_encode($ins_comprasFarmaciaControlador->agregar_solicitud_compra_controlador($descripcion, $matriz));
} else if (isset($_POST['idSolicitudCompra'])) {
    /* Insttancia al controlador */
    require_once "../controladores/comprasFarmaciaControlador.php";
    $ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();
    /* obtencion datos */
    $id = $_POST['idSolicitudCompra'];
    echo json_encode($ins_comprasFarmaciaControlador->autorizar_solicitud_compra_controlador($id));
} else if (isset($_POST['idSolicitudCompra2'])) {
    /* Insttancia al controlador */
    require_once "../controladores/comprasFarmaciaControlador.php";
    $ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();
    /* obtencion datos */
    $id = $_POST['idSolicitudCompra2'];
    echo json_encode($ins_comprasFarmaciaControlador->denegar_solicitud_compra_controlador($id));
} else if (isset($_POST['tipoRecibido_reg']) && isset($_POST['fechaRecibido_reg']) && isset($_POST['idSolicitud_reg'])) {
    $data = [
        "solicitudCompra" => $_POST['idSolicitud_reg'],
        "tipoRecibido" => $_POST['tipoRecibido_reg'],
        "fechaRecibido" => $_POST['fechaRecibido_reg'],
        "nota" => $_POST['nota_reg']
    ];
    /* Insttancia al controlador */
    require_once "../controladores/comprasFarmaciaControlador.php";
    $ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();
    /* agregar un empleado */
    echo $ins_comprasFarmaciaControlador->efectuar_compra_controlador($data);
} /* else if (isset($_POST['idDetSolicitud_reg']) && isset($_POST['fechaVence_reg']) && isset($_POST['cantidadLote_reg'])) {
    $data = [
        "detalleSolicitud" => $_POST['idDetSolicitud_reg'],
        "fechaVence" => $_POST['fechaVence_reg'],
        "cantidadLote" => $_POST['cantidadLote_reg']
    ];
    
    require_once "../controladores/comprasFarmaciaControlador.php";
    $ins_comprasFarmaciaControlador = new comprasFarmaciaControlador();

    echo $ins_comprasFarmaciaControlador->efectuar_compra_controlador($data);
}  */else {
    /* session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location:" . SERVERURL . "login/");
    exit(); */
    $repuesta['repuesta'] = [
        "estado" => "error"
    ];
    echo $repuesta;
}
