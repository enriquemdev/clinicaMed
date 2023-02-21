<?php

include_once "Medicamento_creaCompraModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['medicamento_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
