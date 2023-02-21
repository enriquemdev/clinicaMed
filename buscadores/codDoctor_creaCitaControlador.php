<?php

include_once "codDoctor_creaCitaModelo.php";

$modelo = new BusquedasModelo();

$texto = $_GET['cita_doctor_reg'];

$res = $modelo->buscar($texto);

echo json_encode($res);

?>
