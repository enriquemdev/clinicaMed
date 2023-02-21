<?php

include_once "Consulta_creaConstanciaModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['codigo_diagnostico_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
