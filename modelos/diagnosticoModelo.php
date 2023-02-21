<?php
require_once "mainModel.php";
class diagnosticoModelo extends mainModel
{
    /*Función para catalogos cargos*/
    protected static function diagnostico_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tbldiagnosticoconsulta(Descripcion,IdEnfermedad,CodConsulta,Nota)VALUES(:Descripcion,:IdEnfermedad,:CodConsulta,:Nota)");

        //$sql->bindParam(":Sintoma",$datos['Sintoma']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":IdEnfermedad", $datos['IdEnfermedad']);
        $sql->bindParam(":CodConsulta", $datos['CodConsulta']);
        $sql->bindParam(":Nota", $datos['Nota']);
        $sql->execute();
        return $sql;
    }

    /*Modelo para insertar los diversos sintomas de cada diagnostico*/
    protected static function sintomas_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblsintomasdiagnostico(sintoma, diagnostico) 
            VALUES (:sintoma,:diagnostico)");

        //$sql->bindParam(":Sintoma",$datos['Sintoma']);
        $sql->bindParam(":sintoma", $datos['sintoma']);
        $sql->bindParam(":diagnostico", $datos['diagnostico']);
        $sql->execute();
        return $sql;
    }

    protected static function catSintomas_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catsintomas(nombreSintoma, estadoSintoma) 
            VALUES (:nombreSintoma,:estadoSintoma)");

        //$sql->bindParam(":Sintoma",$datos['Sintoma']);
        $sql->bindParam(":nombreSintoma", $datos['nombreSintoma']);
        $sql->bindParam(":estadoSintoma", $datos['estadoSintoma']);
        $sql->execute();
        return $sql;
    }

    /* Modelor para actualizar */
    protected static function actues($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblconsulta SET  FhFinal = CURRENT_TIMESTAMP
            WHERE Codigo=:Codigo");

        $sql->bindParam(":Codigo", $datos['ID']);
        $sql->execute();
        return $sql;
    }
    /*Obtener datos de item 1 */
    protected static function datos_item1_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catenfermedades ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */
    /* Modelo de busqueda de datos de consulta para update*/
    protected static function datos_consulta_modelo($id)
    {
        $sql4 = mainModel::conectar()->prepare("SELECT * FROM tblconsulta INNER JOIN tblpaciente ON tblconsulta.CodPaciente 
            = tblpaciente.CodigoP INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo WHERE tblconsulta.Codigo= :Codigo");
        $sql4->bindParam(":Codigo", $id);
        $sql4->execute();
        return $sql4;
    } //termina modelo

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

    protected static function reporte_diagnostico_modelo($id)
    {
        $sql5 = mainModel::conectar()->prepare("SELECT a.Descripcion,a.IdEnfermedad,a.CodConsulta,a.Nota, b.NombreEnfermedad 
        ,b.TipoEnfermedad FROM tbldiagnosticoconsulta as a
        INNER JOIN catenfermedades as b on(b.ID=a.IdEnfermedad)
        WHERE a.Codigo=:id;");
        $sql5->bindParam(":id", $id); 
        $sql5->execute();
        return $sql5;
    }
}
