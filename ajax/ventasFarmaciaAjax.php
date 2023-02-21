<?php
$peticionAjax = true;
require_once "../config/APP.php";
if (isset($_POST['receta_medica_reg']) && isset($_POST['fechaVenta_reg'])) {
    /* Insttancia al controlador */
    require_once "../controladores/ventasFarmaciaControlador.php";
    $ins= new ventasFarmaciaControlador();
    /* agregar un empleado */
    echo $ins->agregar_venta_farmacia_controlador();
} else {
    session_start(['name' => 'SPM']);
    session_unset();
    session_destroy();
    header("Location:" . SERVERURL . "login/");
    exit();
}
