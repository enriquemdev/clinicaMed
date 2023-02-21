<?php

include_once "RecetaMedica_creaVentaModelo.php";

$modelo = new BusquedasModelo();

if (isset($_GET['recetaMed_reg'])) {
    $texto = $_GET['recetaMed_reg'];
    $res = $modelo->buscar($texto);
    echo json_encode($res);
} else if (isset($_POST['receta'])) {
    $id = $_POST['receta'];
    $model = $modelo->obtenerDatos($id);
    $result = $model->fetchAll(PDO::FETCH_ASSOC);
    $model = $modelo->obtenerInventario($result[0]['Medicamento']);
    $result2 = $model->fetchAll(PDO::FETCH_ASSOC);
    $model = $modelo->obtenerPrecio($result[0]['Medicamento']);
    $result3 = $model->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(array_merge($result,$result2,$result3));
}
