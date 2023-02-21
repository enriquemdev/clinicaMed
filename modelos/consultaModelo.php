<?php
require_once "mainModel.php";
class consultaModelo extends mainModel
{

    /* Modelor para agregar servicio*/
    protected static function agregar_servicio_modelo()
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblserviciosbrindados(tipoServicio,estadoServicio,MontoServicio,RebajaServicio) 
            VALUES(2,1,0,0)");

        $sql->execute();
        return $sql;
    } //Termina modelo
    /*--------------Funcion para conseguir ultimo servici -------------- */
    protected static function lastservice()
    {
        $sql = self::conectar()->prepare("SELECT max(id) from tblserviciosbrindados");
        $sql->execute();
        return $sql;
    }

    /* Modelor para agregar usuario*/
    protected static function agregar_consulta_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblconsulta(CodMedico,IdCita,CodPaciente,CodSignosVitales,CodConsultorio,Estado,idServicio) VALUES(:CodMedico,:IdCita,:CodPaciente,:CodSignosVitales,:CodConsultorio,:Estado,:servicio)");
        $sql->bindParam(":servicio", $datos['Servicio']);
        $sql->bindParam(":CodMedico", $datos['CodMedico']);
        $sql->bindParam(":IdCita", $datos['IdCita']);
        $sql->bindParam(":CodPaciente", $datos['CodPaciente']);
        $sql->bindParam(":CodSignosVitales", $datos['CodSignosVitales']);
        $sql->bindParam(":CodConsultorio", $datos['CodConsultorio']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->execute();
        return $sql;
    }

    /* Modelor para agregar solconsulta*/
    protected static function agregar_solConsulta_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblconsulta(CodMedico,IdCita,CodPaciente,CodConsultorio,Estado,FechaYHora,MotivoConsulta,RegistradoPor,idServicio) 
            VALUES(:CodMedico,:IdCita,:CodPaciente,:CodConsultorio,:Estado,CURRENT_TIMESTAMP,:MotivoConsulta,:RegistradoPor,:servicio)");
        $sql->bindParam(":servicio", $datos['Servicio']);
        $sql->bindParam(":CodMedico", $datos['CodMedico']);
        $sql->bindParam(":IdCita", $datos['IdCita']);
        $sql->bindParam(":CodPaciente", $datos['CodPaciente']);
        $sql->bindParam(":CodConsultorio", $datos['CodConsultorio']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":MotivoConsulta", $datos['MotivoConsulta']);
        $sql->bindParam(":RegistradoPor", $datos['RegistradoPor']);
        $sql->execute();
        return $sql;
    }
    protected static function buscarCodPaciente($datos2)
    { //Este se ocupa por el buscador
        $sql2 = mainModel::conectar()->prepare("SELECT * FROM tblpaciente INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
            WHERE tblpersona.Nombres=:NombrePaciente AND tblpersona.Apellidos=:ApellidoPaciente AND tblpaciente.CodigoP=:CodigoPaciente");
        $sql2->bindParam(":NombrePaciente", $datos2['NombrePaciente']);
        $sql2->bindParam(":ApellidoPaciente", $datos2['ApellidoPaciente']);
        $sql2->bindParam(":CodigoPaciente", $datos2['CodigoPaciente']);
        $sql2->execute();
        return $sql2;
    }
    /*Obtener datos de item 1 */
    protected static function datos_item1_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catconsultorio ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */
    /*Función para conseguir datos de consulta*/
    protected static function datos_consulta_modelo($id)
    {
        $sql4 = mainModel::conectar()->prepare("SELECT * FROM tblconsulta INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
            INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo
            WHERE tblconsulta.Codigo= :Codigo");
        $sql4->bindParam(":Codigo", $id);
        $sql4->execute();
        return $sql4;
    }

    /* Modelor para actualizar estado*/
    protected static function actues($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblconsulta SET Estado = 6, FhInicio = CURRENT_TIMESTAMP
            WHERE Codigo=:Codigo");

        $sql->bindParam(":Codigo", $datos['ID']);
        $sql->execute();
        return $sql;
    }

    /* Modelor para agregar nota de consulta*/
    protected static function agregarnotas($datos)
    {

        $sql = mainModel::conectar()->prepare("UPDATE tblconsulta SET NotasConsulta = :Notas, Estado=5,FhInicio = CURRENT_TIMESTAMP
            WHERE Codigo=:Codigo");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Notas", $datos['Notas']);
        $sql->execute();
        return $sql;
    }
    /* Modelor para agregar motivo de rechazo de consulta*/
    protected static function agregaranular($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblconsulta SET MotivoRevertida = :Notas, Estado=4
            WHERE Codigo=:Codigo");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Notas", $datos['Notas']);
        $sql->execute();
        return $sql;
    }
    protected static function actualizarEstadoRechazada($datos) //AHORA SE LLAMA ANULADA
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblconsulta SET Estado=4 WHERE Codigo=:Codigo");

        $sql->bindParam(":Codigo", $datos['ID']);
        $sql->execute();
        return $sql;
    }
    /* Modelo de busqueda de datos de consulta para update*/
    protected static function datos_solicitud_consulta_modelo($id)
    {
        $sql4 = mainModel::conectar()->prepare("SELECT *,a.Nombres as NombrePaciente,a.Apellidos as ApellidoPaciente, b.Nombres as NombreM,b.Apellidos as ApellidoDoc FROM tblconsulta INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
            INNER JOIN tblpersona as a ON tblpaciente.CodPersona = a.Codigo
            INNER JOIN tblempleado ON tblconsulta.CodMedico = tblempleado.Codigo
            INNER JOIN tblpersona as b ON tblempleado.CodPersona = b.Codigo 
            WHERE tblconsulta.Codigo= :Codigo");
        $sql4->bindParam(":Codigo", $id);
        $sql4->execute();
        return $sql4;
    }
    protected static function reporte_consulta_modelo($id)
    {
        $sql4 = mainModel::conectar()->prepare("SELECT a.Codigo,a.IdCita,a.CodPaciente,a.CodMedico,a.CodConsultorio,a.Estado,a.FechaYHora,a.FhInicio 
        ,a.FhFinal,a.MotivoConsulta,a.NotasConsulta,c.Nombres as nombresPaciente 
        ,c.Apellidos as apellidosPaciente, e.Nombres as nombresMedico, e.Apellidos as apellidosMedico 
        ,f.Nombre as nombreConsultorio, g.Nombre as nombreEstado 
        FROM tblconsulta as a
        INNER JOIN tblpaciente as b on(b.CodigoP=a.CodPaciente)
        INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
        INNER JOIN tblempleado as d on(d.Codigo=a.CodMedico)
        INNER JOIN tblpersona as e on (e.Codigo=d.CodPersona)
        INNER JOIN catconsultorio as f on (f.ID=a.CodConsultorio)
        INNER JOIN catestadoconsulta as g on(g.ID=a.Estado)
        where a.Codigo= :id");
        $sql4->bindParam(":id", $id);
        $sql4->execute();
        return $sql4;
    }
}
