<?php

include_once "codEmpleado_solicitaCargoModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['cargo_empleado_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
