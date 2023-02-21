<?php
require_once "mainModel.php";
class constanciaModelo extends mainModel
{

    /* Modelor para agregar usuario*/
    protected static function agregar_constancia_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblconstancia(CodDiagnostico,Razon,HoraEntrada,HoraSalida) VALUES(:CodDiagnostico ,:Razon,:HoraEntrada,:HoraSalida)");

        $sql->bindParam(":CodDiagnostico", $datos['CodDiagnostico']);
        $sql->bindParam(":Razon", $datos['Razon']);
        $sql->bindParam(":HoraEntrada", $datos['HoraEntrada']);
        $sql->bindParam(":HoraSalida", $datos['HoraSalida']);
        $sql->execute();
        return $sql;
    }
    /*Modelo de datos de consulta para receta PARA CUANDO SE HACE DESDE DIAGNÓSTICO */
    protected static function datos_diagnostico_modelo($id)
    {

        $sql4 = mainModel::conectar()->prepare("SELECT *, tblconsulta.Codigo as codconsulta, tbldiagnosticoconsulta.Codigo as CodDiagnostico FROM tbldiagnosticoconsulta
            inner join tblconsulta on tblconsulta.Codigo= tbldiagnosticoconsulta.CodConsulta 
            inner join tblpaciente on tblconsulta.CodPaciente = tblpaciente.CodigoP
            inner join tblpersona on tblpaciente.CodPersona=tblpersona.Codigo
             WHERE tbldiagnosticoconsulta.Codigo= :Codigo");
        $sql4->bindParam(":Codigo", $id);

        $sql4->execute();
        return $sql4;
    }

    protected static function buscarCodDiagnostico($datos2)
    { //Este se ocupa por el buscador
        $sql5 = mainModel::conectar()->prepare("SELECT *, catenfermedades.ID as cod_enfermedad, catenfermedades.NombreEnfermedad as nombre_enfermedad,
            tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta, tbldiagnosticoconsulta.Codigo as cod_diagnostico
            FROM catenfermedades 
            INNER JOIN tbldiagnosticoconsulta ON catenfermedades.ID = tbldiagnosticoconsulta.IdEnfermedad
            INNER JOIN tblconsulta ON tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo
            INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
            INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
            WHERE tblconsulta.FhInicio=:FhInicio AND catenfermedades.NombreEnfermedad=:NombreEnfermedad AND tblpersona.Nombres=:NombrePaciente"); //tblconsulta.FhInicio=:FhInicio AND tblconsulta.CodMedico=:CodigoMedico AND
        $sql5->bindParam(":FhInicio", $datos2['FhInicio']); //El query de arriba contiene alias de sql que es lo de los as para prevenir el fallo de tener el mismo nombre de columnas en las dos tablas inlvolucradas en el inner join.
        $sql5->bindParam(":NombreEnfermedad", $datos2['NombreEnfermedad']);
        $sql5->bindParam(":NombrePaciente", $datos2['NombrePaciente']);
        $sql5->execute();
        return $sql5;
    }
    protected static function reporte_constancia_modelo($id)
    {
        $sql5 = mainModel::conectar()->prepare("SELECT a.Razon,a.Fecha,a.HoraEntrada,a.HoraSalida,b.IdEnfermedad,h.NombreEnfermedad,c.MotivoConsulta 
        ,e.Nombres as nombresPaciente, e.Apellidos as apellidosPaciente, g.Nombres as nombresMedico 
        ,g.Apellidos as apellidosMedico, c.Codigo as consulta FROM tblconstancia as a
        INNER JOIN tbldiagnosticoconsulta as b on (b.Codigo=a.CodDiagnostico)
        INNER JOIN tblconsulta as c on (c.Codigo=b.CodConsulta)
        INNER JOIN catenfermedades as h on(h.ID=c.Codigo)
        INNER JOIN tblpaciente as d on (d.CodigoP=c.CodPaciente)
        INNER JOIN tblpersona as e on (e.Codigo=d.CodPersona)
        INNER JOIN tblempleado as f on (f.Codigo=c.CodMedico)
        INNER JOIN tblpersona as g on(g.Codigo=f.CodPersona)
        WHERE a.Codigo=:id;");
        $sql5->bindParam(":id", $id);
        $sql5->execute();
        return $sql5;
    }
}
