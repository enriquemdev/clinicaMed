<?php

include_once "NombreEspecialista_examenModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['text'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
