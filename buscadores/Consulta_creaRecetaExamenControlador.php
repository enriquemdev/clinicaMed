<?php

include_once "Consulta_creaRecetaExamenModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['codigo_consulta_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
