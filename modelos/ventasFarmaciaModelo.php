<?php
require_once "mainModel.php";
class ventasFarmaciaModelo extends mainModel
{
    /*Función para catalogos cargos*/
    protected static function agregar_venta_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblventafarmacia (recetaMedica,servicio,descripcion,fechaVenta)
            VALUES (:recetaMedica,:servicio,:descripcion,:fechaVenta);");
        $sql->bindParam(":recetaMedica", $datos['recetaMedica']);
        $sql->bindParam(":servicio", $datos['servicio']);
        $sql->bindParam(":descripcion", $datos['descripcion']);
        $sql->bindParam(":fechaVenta", $datos['fechaVenta']);
        $sql->execute();
        return $sql;
    }
    protected static function agregar_detalle_venta_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tbldetalleventafarmacia (ventaFarmacia,detalleRecetaMedica,cantidadVendida)
        VALUES (:ventaFarmacia,:detalleRecetaMedica,:cantidadVendida);");
        $sql->bindParam(":ventaFarmacia", $datos['venta']);
        $sql->bindParam(":detalleRecetaMedica", $datos['detalleReceta']);
        $sql->bindParam(":cantidadVendida", $datos['cantidadVendida']);
        $sql->execute();
        return $sql;
    }
    protected static function agregar_servicio_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblserviciosbrindados (tipoServicio,estadoServicio,MontoServicio,RebajaServicio)
            VALUES(:tipoServicio,:estadoServicio,:MontoServicio,:RebajaServicio);");

        $sql->bindParam(":tipoServicio", $datos['tipoServicio']);
        $sql->bindParam(":estadoServicio", $datos['estadoServicio']);
        $sql->bindParam(":MontoServicio", $datos['montoServicio']);
        $sql->bindParam(":RebajaServicio", $datos['rebajaServicio']);
        $sql->execute();
        return $sql;
    }
    protected static function disminuir_inventario_lote_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tbllotemedicamento as a set a.cantidadEnlote=
        (a.cantidadEnlote-:cantidad) where a.idLote=:idLote;");

        $sql->bindParam(":cantidad", $datos['cantidad']);
        $sql->bindParam(":idLote", $datos['idLote']);
        $sql->execute();
        return $sql;
    }
    protected static function obtener_venta_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("");
        $sql->execute();
        return $sql;
    }
}
