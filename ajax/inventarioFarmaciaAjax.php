<?php
$peticionAjax = true;
require_once "../config/APP.php";
 if (isset($_POST['idDetSolicitud_reg']) && isset($_POST['fechaVence_reg']) && isset($_POST['cantidadLote_reg'])) {
    $data = [
        "detalleSolicitud" => $_POST['idDetSolicitud_reg'],
        "fechaVence" => $_POST['fechaVence_reg'],
        "cantidadLote" => $_POST['cantidadLote_reg'],
        "cantidadEspera"=>$_POST['cantidad_reg']
    ];
    
    require_once "../controladores/inventarioFarmaciaControlador.php";
    $ins = new inventarioFarmaciaControlador();

    echo $ins->agregar_lote_controlador($data);
} else {
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
