<?php

include_once "BusquedasModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['consulta_paciente_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
