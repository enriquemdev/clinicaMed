<?php

include_once "familiarDe_pacienteModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['text'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
