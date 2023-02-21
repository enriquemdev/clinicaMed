<?php

include_once "nombreLaboratorio_creaCompraModelo.php";

$modelo = new BusquedasModelo();

if (isset($_GET['medicamento']) && isset($_GET['laboratorio_reg'])) {
    $texto = $_GET['laboratorio_reg'];
    $med = $_GET['medicamento'];
    $res = $modelo->buscar($texto, $med);
    echo json_encode($res);
} else if (isset($_POST['medicamento']) && isset($_POST['laboratorio'])) {
    $med = $_POST['medicamento'];
    $lab = $_POST['laboratorio'];
    $model = $modelo->obtenerDatos($med, $lab);
    $result = $model->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
} else if (isset($_GET['medicamento'])) {
    $med = $_GET['medicamento'];
    $res = $modelo->buscar2($med);
    echo json_encode($res);
}
