<?php

include_once "nombreEmpleado_creaUsuarioModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['usuario_empleado_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
