<?php

include_once "nombreDoctor_especialidadModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['text'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
