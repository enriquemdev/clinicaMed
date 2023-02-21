<?php

include_once "cliente_creaPagoCajaModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['cliente_pago_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
