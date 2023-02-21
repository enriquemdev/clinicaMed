<?php
require_once "mainModel.php";
class comprasFarmaciaModelo extends mainModel
{
    /*Función para catalogos cargos*/
    protected static function agregar_compra_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblcomprasfarmacia(Proveedor,Descripcion,fechaCompra) 
            VALUES(:proveedor,:descripcion,:fechaCompra)");

        $sql->bindParam(":proveedor", $datos['proveedor']);
        $sql->bindParam(":descripcion", $datos['descripcion']);
        $sql->bindParam(":fechaCompra", $datos['fechaCompra']);
        $sql->execute();
        return $sql;
    }
    /*Obtener datos de item 1 */
    protected static function datos_proveedores_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT a.idProveedor, a.nombreProvedor FROM tblproveedores as a");
        $sql->execute();
        return $sql;
    }/*Termina modelo */
    /*Obtener datos de item 1 */
    protected static function datos_item2_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catsalaexamen ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */

    //Aqui manoseo steven
    protected static function datos_examen_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("SELECT a.Codigo,a.FechaYHora,c.Nombres,
            c.Apellidos,c.Fecha_de_nacimiento,e.Nombres as NombresMedico,
            e.Apellidos as ApellidosMedico, f.Nombre as SalaExamen, g.Motivo as MotivoExamen, 
            h.Nombre as TipoExamen, i.Nombre as GrupoSanguineo
            FROM tblexamen as a  
            INNER JOIN tblpaciente as b ON (a.CodPaciente=b.CodigoP)
            INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
            INNER JOIN tblempleado as d ON (a.EmpleadoRealizacion=d.Codigo)
            INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
            INNER JOIN catsalaexamen as f ON (f.ID=a.SalaMedica)
            INNER JOIN tblrecetaexamen as g ON (a.RecetaPrevia=g.Codigo)
            INNER JOIN catexamenesmedicos as h ON (h.ID=g.TipoExamen)
            INNER JOIN catgruposanguineo as i ON (b.GrupoSanguineo=i.ID)
            WHERE a.Codigo = $id;");

        $sql->execute();

        return $sql;
    }
    protected static function datos_examenes_modelo()
    {
        $sql = mainModel::conectar()->prepare("call sp_ObtenerDatosExamenes();");

        $sql->execute();

        return $sql;
    }
    //Aqui termino

    protected static function buscarCodProveedor($datos2)
    {
        $sql5 = mainModel::conectar()->prepare("SELECT *
            FROM tblproveedores
            WHERE nombreProveedor=:nombreProveedor 
            AND leadtime=:leadTime");
        $sql5->bindParam(":nombreProveedor", $datos2['nombreProveedor']);
        $sql5->bindParam(":leadTime", $datos2['leadTime']);
        $sql5->execute();
        return $sql5;
    }

    /*Funciones del detalle de la compra*/

    protected static function agregar_detalle_compra_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tbldetcomprasfarmacia(Medicamento,Cantidad,CostoUnidad,CodigoCompra,Notas) 
            VALUES(:medicamento,:cantidad,:costoUnidad,:codigoCompra,:notas)");

        $sql->bindParam(":medicamento", $datos['medicamento']);
        $sql->bindParam(":cantidad", $datos['cantidad']);
        $sql->bindParam(":costoUnidad", $datos['precioUnidad']);
        $sql->bindParam(":codigoCompra", $datos['idCompra']);
        $sql->bindParam(":notas", $datos['observacion']);
        $sql->execute();
        return $sql;
    }

    protected static function incrementar_inventario_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE catmedicamentos as a set a.cantidadDisponible=
            (a.cantidadDisponible+:cantidad) where a.Codigo=:idMedicamento");

        $sql->bindParam(":cantidad", $datos['cantidad']);
        $sql->bindParam(":idMedicamento", $datos['idMedicamento']);
        $sql->execute();
        return $sql;
    }

    protected static function agregar_lote_farmacia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO loteproductos (DetalleDeCompra,FechaVencimiento,CantidadRestante,Nota) VALUES
            (:DetalleDeCompra,:FechaVencimiento,:CantidadRestante,:Nota)");

        $sql->bindParam(":DetalleDeCompra", $datos['DetalleDeCompra']);
        $sql->bindParam(":FechaVencimiento", $datos['FechaVencimiento']);
        $sql->bindParam(":CantidadRestante", $datos['CantidadRestante']);
        $sql->bindParam(":Nota", $datos['Nota']);
        $sql->execute();
        return $sql;
    }
    protected static function obtener_laboratorios_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT * FROM catlaboratorio");
        $sql->execute();
        return $sql;
    }
    protected static function agregar_solicitud_compra_modelo($data)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblsolicitudcompra(solicitante,estadoSolicitud,descripcionSolicitud) VALUES
        (:solicitante,:estadoSolicitud,:descripcionSolicitud);");
        $sql->bindParam(":solicitante", $data['Solicitante']);
        $sql->bindParam(":estadoSolicitud", $data['Estado']);
        $sql->bindParam(":descripcionSolicitud", $data['Descripcion']);
        $sql->execute();
        return $sql;
    }
    protected static function agregar_detalle_solicitud_compra_modelo($data)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tbldetsolicitudcompra (solicitudCompra,medicamento,proveedor,laboratorio,cantidad,costo)
        VALUES(:solicitudCompra,:medicamento,:proveedor,:laboratorio,:cantidad,:costo);");
        $sql->bindParam(":solicitudCompra", $data['SolicitudCompra']);
        $sql->bindParam(":medicamento", $data['Medicamento']);
        $sql->bindParam(":proveedor", $data['Proveedor']);
        $sql->bindParam(":laboratorio", $data['Laboratorio']);
        $sql->bindParam(":cantidad", $data['Cantidad']);
        $sql->bindParam(":costo", $data['Costo']);
        $sql->execute();
        return $sql;
    }
    protected static function obtener_ultima_solicitud_compra_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT MAX(a.idSolicitudCompra) idSolicitudCompra  FROM tblsolicitudcompra as a");
        $sql->execute();
        return $sql;
    }
    protected static function obtener_precio_medicamento_modelo($data)
    {
        $sql = mainModel::conectar()->prepare("SELECT MIN(a.precioMedicamento) precioMedicamento FROM tblmedicamentoproveedor as a
        WHERE a.medicamento=:medicamento AND a.proveedor=:proveedor AND a.laboratorio=:laboratorio");
        $sql->bindParam(":medicamento", $data['Medicamento']);
        $sql->bindParam(":proveedor", $data['Proveedor']);
        $sql->bindParam(":laboratorio", $data['Laboratorio']);
        $sql->execute();
        return $sql;
    }
    protected static function autorizar_solicitud_compra_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblsolicitudcompra SET estadoSolicitud = 2
        WHERE idSolicitudCompra=:id;");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }
    protected static function denegar_solicitud_compra_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblsolicitudcompra SET estadoSolicitud = 4
        WHERE idSolicitudCompra=:id;");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }
    protected static function obtener_solicitud_compra_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("SELECT
        a.idSolicitudCompra,
        a.solicitante,a.estadoSolicitud,a.fechaRegistro,a.descripcionSolicitud,b.medicamento,b.proveedor,b.laboratorio,b.cantidad,b.costo 
        ,d.Nombres,d.Apellidos,e.nombreEstado,f.nombreComercial,g.nombreProveedor,h.nombreLaboratorio
        FROM tblsolicitudcompra as a 
        INNER JOIN tbldetsolicitudcompra as b on (b.solicitudCompra=a.idSolicitudCompra)
        INNER JOIN tblempleado as c on (c.Codigo=a.solicitante)
        INNER JOIN tblpersona as d on (d.Codigo=c.CodPersona)
        INNER JOIN catestadocompra as e on (e.idEstadoCompra=a.estadoSolicitud)
        INNER JOIN catmedicamentos as f on (f.Codigo=b.medicamento)
        INNER JOIN tblproveedores as g on (g.idProveedor=b.proveedor)
        INNER JOIN catlaboratorio as h on(h.idLaboratorio=b.laboratorio)
        WHERE a.idSolicitudCompra=:id;");
        $sql->bindParam(":id", $id);
        $sql->execute();
        return $sql;
    }
    protected static function efectuar_compra_modelo($data,$estado)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblcompra (solicitudCompra, estadoCompra,nota,fechaRecibido)
        VALUES (:solicitudCompra,:estadoCompra,:nota,:fechaRecibido);");
        $sql->bindParam(":solicitudCompra", $data['solicitudCompra']);
        $sql->bindParam(":estadoCompra", $estado);
        $sql->bindParam(":nota", $data['nota']);
        $sql->bindParam(":fechaRecibido", $data['fechaRecibido']);
        $sql->execute();
        return $sql;
    }
    protected static function cambiar_estado_solicitud_compra_modelo($solicitud,$estado)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblsolicitudcompra SET estadoSolicitud =:estado WHERE idSolicitudCompra=:solicitud;");
        $sql->bindParam(":estado", $estado);
        $sql->bindParam(":solicitud", $solicitud);
        $sql->execute();
        return $sql;
    }
}
