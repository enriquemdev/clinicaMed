<?php

include_once "examen_examenModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['text'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
