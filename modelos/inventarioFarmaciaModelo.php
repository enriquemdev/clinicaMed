<?php
require_once "mainModel.php";
class inventarioFarmaciaModelo extends mainModel
{
    //Antes
    /* protected static function obtener_ventas_productos_modelo(){
            $sql =mainModel::conectar()->prepare("SELECT b.idMedicamento,b.cantidadVendida
            FROM tblventafarmacia as a 
            INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.idVentaFarmacia)
            INNER JOIN catmedicamentos as c on (b.idMedicamento=c.Codigo)
            WHERE YEAR (a.fechaVenta)=YEAR(CURRENT_DATE() - INTERVAL 1 YEAR);");

            $sql->execute();

            return $sql;
        } */
    //Antes-Inventario
    /* protected static function obtener_suma_ventas_producto_modelo($medicamento){
            $sql =mainModel::conectar()->prepare("SELECT sum(b.cantidadVendida) as resultado
            FROM tblventafarmacia as a 
            INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.idVentaFarmacia)
            INNER JOIN catmedicamentos as c on (b.idMedicamento=c.Codigo)
            WHERE YEAR (a.fechaVenta)=YEAR(CURRENT_DATE() - INTERVAL 1 YEAR) AND c.Codigo=:medicamento");

            $sql->bindParam(":medicamento",$medicamento);
            $sql->execute();

            return $sql;
        } */
    //Ahora-Inventario
    protected static function obtener_suma_ventas_producto_modelo($medicamento)
    {
        $sql = mainModel::conectar()->prepare("SELECT sum(b.cantidadVendida) as resultado
        FROM tblventafarmacia as a 
        INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.ventaFarmacia)
        INNER JOIN tblrecetamedicamentos as c on (c.Codigo=a.recetaMedica)
        INNER JOIN tbldetallereceta as d on (d.CodReceta=a.recetaMedica)
        INNER JOIN catmedicamentos as e on (e.Codigo=d.Medicamento)
        WHERE a.fechaVenta BETWEEN DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND CURDATE() AND e.Codigo=:medicamento;");

        $sql->bindParam(":medicamento", $medicamento);
        $sql->execute();

        return $sql;
    }

    //Para obtener productos vendidos el año anterior
    //Antes-Inventario
    /*  protected static function obtener_productos_vendidos_modelo(){
            $sql =mainModel::conectar()->prepare("SELECT count(DISTINCT   (c.NombreComercial )) as ProductosVendidosAño
            FROM tblventafarmacia as a 
            INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.idVentaFarmacia)
            INNER JOIN catmedicamentos as c on (b.idMedicamento=c.Codigo)
            WHERE YEAR (a.fechaVenta)=YEAR(CURRENT_DATE() - INTERVAL 1 YEAR);");

            $sql->execute();

            return $sql;
        } */
    //Ahora-Inventario
    protected static function obtener_productos_vendidos_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT count(DISTINCT   (e.nombreComercial )) as ProductosVendidosAño
            FROM tblventafarmacia as a 
            INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.ventaFarmacia)
            INNER JOIN tblrecetamedicamentos as c on (c.Codigo=a.recetaMedica)
            INNER JOIN tbldetallereceta as d on (d.CodReceta=a.recetaMedica)
            INNER JOIN catmedicamentos as e on (e.Codigo=d.Medicamento)
            WHERE a.fechaVenta BETWEEN DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND CURDATE();");

        $sql->execute();

        return $sql;
    }
    //Antes-Inventario
    /* protected static function obtener_codigo_productos_vendidos_modelo(){
            $sql =mainModel::conectar()->prepare("SELECT DISTINCT   (c.Codigo ) as idProductosCumple
            FROM tblventafarmacia as a 
            INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.idVentaFarmacia)
            INNER JOIN catmedicamentos as c on (b.idMedicamento=c.Codigo)
            WHERE YEAR (a.fechaVenta)=YEAR(CURRENT_DATE() - INTERVAL 1 YEAR);");

            $sql->execute();

            return $sql;
        } */
    //Ahora-Inventario
    protected static function obtener_codigo_productos_vendidos_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT DISTINCT   (e.Codigo ) as idProductosCumple
        FROM tblventafarmacia as a 
        INNER JOIN tbldetalleventafarmacia as b on (a.idVentaFarmacia=b.ventaFarmacia)
        INNER JOIN tblrecetamedicamentos as c on (c.Codigo=a.recetaMedica)
        INNER JOIN tbldetallereceta as d on (d.CodReceta=a.recetaMedica)
        INNER JOIN catmedicamentos as e on (e.Codigo=d.Medicamento)
        WHERE a.fechaVenta BETWEEN DATE_SUB(CURDATE(), INTERVAL 29 DAY) AND CURDATE();");

        $sql->execute();

        return $sql;
    }
    //Aqui termino
    protected static function datos_proveedores_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT a.idProveedor, a.nombreProveedor,a.tiempoEntrega FROM tblproveedores as a
        ORDER BY a.tiempoEntrega ASC;");
        $sql->execute();
        return $sql;
    }
    protected static function agregar_lote_modelo($data)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tbllotemedicamento (medicamento,fechaVence,cantidadEnlote)
            VALUES (:medicamento,:fechaVence,:cantidadEnlote);");
        $sql->bindParam(":medicamento", $data['medicamento']);
        $sql->bindParam(":fechaVence", $data['fechaVence']);
        $sql->bindParam(":cantidadEnlote", $data['cantidad']);
        $sql->execute();
        return $sql;
    }
    protected static function actualizar_lote_modelo($lote, $cantidad)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tbllotemedicamento SET cantidadEnlote=((SELECT cantidadEnlote FROM tbllotemedicamento WHERE idLote=$lote)+$cantidad)
            WHERE idLote=$lote;");
        $sql->execute();
        return $sql;
    }
    protected static function agregar_asignacion_lote_modelo($data)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblasignacionlote (detSoliCompra,lote,asgindadoYa)
            VALUES (:detCompra,:lote,:cantidadAsignada);");
        $sql->bindParam(":detCompra", $data['detCompra']);
        $sql->bindParam(":lote", $data['lote']);
        $sql->bindParam(":cantidadAsignada", $data['cantidad']);
        $sql->execute();
        return $sql;
    }
}
