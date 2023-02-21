<?php

include_once "nombreEmpleado_estudioAcademicoModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['text'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
