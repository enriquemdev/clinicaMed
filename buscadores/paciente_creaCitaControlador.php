<?php

include_once "paciente_creaCitaModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['cita_paciente_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
