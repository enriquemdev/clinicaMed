<?php

include_once "nombreProveedor_creaCompraModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['proveedor_reg'];
$med = $_GET['med'];
$lab = $_GET['lab'];

$res = $modelo->buscar($texto,$med,$lab);

echo json_encode($res);

?>
