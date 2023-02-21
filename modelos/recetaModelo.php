<?php
require_once "mainModel.php";
class recetaModelo extends mainModel
{

    /* Modelor para agregar usuario*/
    protected static function agregar_receta_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblrecetamedicamentos(CodigoConsulta) VALUES(:CodigoConsulta)");

        $sql->bindParam(":CodigoConsulta", $datos['CodigoConsulta']);
        $sql->execute();
        return $sql;
    }
    protected static function agregar_detallereceta_modelo($datos)
    {
        $sql2 = mainModel::conectar()->prepare("INSERT INTO tbldetallereceta(Medicamento,Dosis,Frecuencia,CodReceta) VALUES(:Medicamento,:Dosis,:Frecuencia,:CodReceta)");

        $sql2->bindParam(":Medicamento", $datos['Medicamento']);
        $sql2->bindParam(":Dosis", $datos['Dosis']);
        $sql2->bindParam(":Frecuencia", $datos['Frecuencia']);
        $sql2->bindParam(":CodReceta", $datos['CodReceta']);
        $sql2->execute();
        return $sql2;
    }
    /*
        Se tomaba el codigo de la  receta para el detalle por medio de la consulta, pero pueden tener varias recetas la misma consultas 26/03/2022
        protected static function obtener_codigoreceta_modelo($datos){
            $sql3 =mainModel::conectar()->prepare("SELECT * FROM tblrecetamedicamentos 
                WHERE CodigoConsulta=:CodigoConsulta");

            $sql3->bindParam(":CodigoConsulta",$datos['CodigoConsulta']);
            $sql3->execute();
            return $sql3;
            }*/

    protected static function obtener_codigoreceta_modelo()
    {
        $sql3 = mainModel::conectar()->prepare("SELECT * FROM tblrecetamedicamentos 
                WHERE Codigo = (SELECT MAX(Codigo) FROM tblrecetamedicamentos)");
        $sql3->execute();
        return $sql3;
    }

    protected static function agregar_receta_examen_modelo($datos)
    {
        $sql4 = mainModel::conectar()->prepare("INSERT INTO tblrecetaexamen(ConsultaPrevia,TipoExamen,Motivo) VALUES(:ConsultaPrevia,:TipoExamen,:Motivo)");

        $sql4->bindParam(":Motivo", $datos['Motivo']);
        $sql4->bindParam(":ConsultaPrevia", $datos['ConsultaPrevia']);
        $sql4->bindParam(":TipoExamen", $datos['TipoExamen']);
        $sql4->execute();
        return $sql4;
    }
    /*Obtener datos de item 1 */
    protected static function datos_item1_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catexamenesmedicos ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */

    protected static function buscarCodConsulta($datos2)
    { //Este se ocupa por el buscador
        $sql5 = mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta
            FROM tblconsulta INNER JOIN tblpaciente 
            ON tblconsulta.CodPaciente = tblpaciente.CodigoP INNER JOIN tblpersona 
            ON tblpaciente.CodPersona = tblpersona.Codigo 
            WHERE tblconsulta.FhInicio=:FhInicio /*AND tblconsulta.CodMedico=:CodigoMedico*/ AND tblpersona.Nombres=:NombrePaciente"); //tblconsulta.FhInicio=:FhInicio AND tblconsulta.CodMedico=:CodigoMedico AND
        $sql5->bindParam(":FhInicio", $datos2['FhInicio']); //El query de arriba contiene alias de sql que es lo de los as para prevenir el fallo de tener el mismo nombre de columnas en las dos tablas inlvolucradas en el inner join.
        //$sql5->bindParam(":CodigoMedico",$datos2['CodigoMedico']);
        $sql5->bindParam(":NombrePaciente", $datos2['NombrePaciente']);
        $sql5->execute();
        return $sql5;
    }

    /*Obtener datos de item 1 */
    protected static function datos_medicamentos_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catmedicamentos ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */
    /*Modelo de datos de consulta para receta PARA CUANDO SE HACE DESDE DIAGNÓSTICO */
    protected static function datos_consulta_modelo($id)
    {

        $sql4 = mainModel::conectar()->prepare("SELECT *, tblconsulta.Codigo as codconsulta FROM tblconsulta inner join tblpaciente on tblconsulta.CodPaciente = tblpaciente.CodigoP
            inner join tblpersona on tblpaciente.CodPersona=tblpersona.Codigo
             WHERE tblconsulta.Codigo= :Codigo");
        $sql4->bindParam(":Codigo", $id);

        $sql4->execute();
        return $sql4;
    }

    protected static function reporte_receta_medica_modelo($id)
    {
        $sql5 = mainModel::conectar()->prepare("SELECT a.CodigoConsulta, a.FechaEmision, b.Medicamento,b.Dosis,b.Frecuencia 
        ,f.Nombres as nombresMedico,f.Apellidos as apellidosMedico 
        ,h.Apellidos as nombresPaciente, h.Apellidos as apellidosPaciente 
        , c.NombreComercial as nombreMedicamento
        FROM tblrecetamedicamentos as a
        INNER JOIN tbldetallereceta as b on (b.CodReceta=a.Codigo)
        INNER JOIN catmedicamentos as c on (c.Codigo=b.Medicamento)
        INNER JOIN tblconsulta as d on (d.Codigo=a.CodigoConsulta)
        INNER JOIN tblempleado as e on(e.Codigo=d.CodMedico)
        INNER JOIN tblpersona as f on (f.Codigo=e.CodPersona)
        INNER JOIN tblpaciente as g on (g.CodigoP=d.CodPaciente)
        INNER JOIN tblpersona as h on (h.Codigo=g.CodPersona)
        WHERE a.Codigo=:id");
        $sql5->bindParam(":id", $id);
        $sql5->execute();
        return $sql5;
    }

    protected static function reporte_receta_examen_modelo($id)
    {
        $sql5 = mainModel::conectar()->prepare("SELECT a.ConsultaPrevia,a.TipoExamen,a.Motivo,b.Nombre as nombreTipoExamen 
        ,e.Nombres as nombresPaciente, e.Apellidos as apellidosPaciente, g.Nombres as nombresMedico
        ,g.Apellidos as apellidosMedico FROM tblrecetaexamen as a
        INNER JOIN catexamenesmedicos as b on (b.ID=a.TipoExamen)
        INNER JOIN tblconsulta as c on (c.Codigo=a.ConsultaPrevia)
        INNER JOIN tblpaciente as d on (d.CodigoP=c.CodPaciente)
        INNER JOIN tblpersona as e on (e.Codigo=d.CodPersona)
        INNER JOIN tblempleado as f on (f.Codigo=c.CodMedico)
        INNER JOIN tblpersona as g on(g.Codigo=f.CodPersona)
        WHERE a.Codigo=:id");
        $sql5->bindParam(":id", $id);
        $sql5->execute();
        return $sql5;
    }
} 
    /*
    SELECT *, tblpersona.Codigo as cod_persona, tblconsulta.Consulta as cod_empleado
    FROM tblconsulta INNER JOIN tblpaciente 
   ON tblconsulta.CodPaciente = tblpaciente.CodigoP INNER JOIN tblpersona 
   ON tblpaciente.CodPersona = tblpersona.Codigo WHERE tblpersona.Nombres LIKE :texto*/