<?php
require_once "mainModel.php";
class cajaModelo extends mainModel
{
    /*Función para agregar persona*/
    protected static function agregar_persona_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblpersona(Cedula,Nombres,Apellidos,Fecha_de_nacimiento,Genero,Estado_civil,Direccion,Telefono
            ,Email,Estado) VALUES(:Cedula,:Nombres,:Apellidos,:Fecha_de_nacimiento,:Genero,:Estado_civil,:Direccion,:Telefono,:Email,:Estado)");

        $sql->bindParam(":Cedula", $datos['Cedula']);
        $sql->bindParam(":Nombres", $datos['Nombres']);
        $sql->bindParam(":Apellidos", $datos['Apellidos']);
        $sql->bindParam(":Fecha_de_nacimiento", $datos['Fecha_de_nacimiento']);
        $sql->bindParam(":Genero", $datos['Genero']);
        $sql->bindParam(":Estado_civil", $datos['Estado_civil']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->execute();
        return $sql;
    }

    protected static function obtener_codigo2($datos)
    {
        $sql5 = mainModel::conectar()->prepare("call increment_persona(:increm)");
        $sql5->bindParam(":increm", $datos);
        $sql5->execute();
        return $sql5;
    }

    protected static function obtener_persona_modelo($datos)
    {
        $sql3 = mainModel::conectar()->prepare("SELECT * FROM tblpersona 
                    WHERE Cedula=:Cedula");

        $sql3->bindParam(":Cedula", $datos['Cedula']);
        $sql3->execute();
        return $sql3;
    }

    protected static function agregar_cliente_modelo($datos)
    {
        $sql2 = mainModel::conectar()->prepare("INSERT INTO tblclientes(CodPersona) 
            VALUES(:CodPersona)");
        $sql2->bindParam(":CodPersona", $datos['CodPersona']);
        $sql2->execute();
        return $sql2;
    }

    protected static function buscarCodPersonaCliente($datos)
    {
        $sql2 = mainModel::conectar()->prepare("SELECT Codigo FROM tblpersona
            WHERE (Nombres = :Nombres AND Apellidos = :Apellidos AND Cedula = :Cedula)
            ");
        $sql2->bindParam(":Nombres", $datos['Nombres']);
        $sql2->bindParam(":Apellidos", $datos['Apellidos']);
        $sql2->bindParam(":Cedula", $datos['Cedula']);
        $sql2->execute();
        return $sql2;
    }

    protected static function agregar_recibo_modelo($datos)
    {
        $sql2 = mainModel::conectar()->prepare("INSERT INTO tblrecibosventa(Cliente, aperturaCaja, FyHRegistro) 
            VALUES(:Cliente, :aperturaCaja, CURRENT_TIMESTAMP)");
        $sql2->bindParam(":aperturaCaja", $datos['aperturaCaja']);
        $sql2->bindParam(":Cliente", $datos['Cliente']);
        $sql2->execute();
        return $sql2;
    }

    protected static function AgregarDetPagoServicioModelo($datos)
    {
        $sql2 = mainModel::conectar()->prepare("INSERT INTO tbldetpagoservicios(ServicioBrindado, Monto, RebajaPago, metodoDePago, NumeroRecibo) 
            VALUES(:ServicioBrindado, :Monto, :RebajaPago, :metodoDePago, :NumeroRecibo)");
        $sql2->bindParam(":ServicioBrindado", $datos['ServicioBrindado']);
        $sql2->bindParam(":Monto", $datos['Monto']);
        $sql2->bindParam(":RebajaPago", $datos['RebajaPago']);
        $sql2->bindParam(":metodoDePago", $datos['metodoDePago']);
        $sql2->bindParam(":NumeroRecibo", $datos['NumeroRecibo']);
        $sql2->execute();
        return $sql2;
    }

    protected static function obtener_recibo_modelo($id)
    {
        $sql2 = mainModel::conectar()->prepare("SELECT a.idRecibo,a.Cliente,f.Nombres,f.Apellidos,f.Cedula,a.aperturaCaja,a.FyHRegistro,g.MontoServicio as montoServicio,g.fechaYHora,b.Monto,b.ServicioBrindado,b.RebajaPago,b.metodoDePago,i.NombreMetodoPago,c.Caja,j.nombreCaja,c.EmpleadoCaja,l.Nombres as NombreEmpleado,l.Apellidos as ApellidosEmpleado,h.nombreServicio,l.Cedula as CedulaEmpleado FROM tblrecibosventa as a
            INNER JOIN tbldetpagoservicios as b on (b.NumeroRecibo=a.idRecibo)
            INNER JOIN tblaperturacaja as c on (c.idApertura=a.aperturaCaja)
            INNER JOIN catcaja as d on (d.idCaja=c.Caja)
            INNER JOIN tblclientes as e on (e.idCliente=a.Cliente)
            INNER JOIN tblpersona as f on (f.Codigo=e.CodPersona)
            INNER JOIN tblserviciosbrindados as g on(g.idServiciosBrindados=b.ServicioBrindado)
            INNER JOIN catservicios as h on (h.idServicio=g.tipoServicio)
            INNER JOIN catmetodosdepago as i on (i.idMetodoPago=b.metodoDePago)
            INNER JOIN catcaja as j on (j.idCaja=c.Caja)
            INNER JOIN tblempleado as k on (k.Codigo=c.EmpleadoCaja)
            INNER JOIN tblpersona as l on (l.Codigo=k.CodPersona)
            WHERE a.idRecibo=$id;");
        $sql2->execute();
        return $sql2;
    }
    //Aqui termino
}
