<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        session_start(['name'=>'SPM']);
        $res = array();

        /* $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta
        FROM tblconsulta 
        INNER JOIN tbldiagnosticoconsulta ON tblconsulta.Codigo = tbldiagnosticoconsulta.CodConsulta
        INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
        INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
        WHERE tblpersona.Nombres LIKE :texto AND
        CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tblconsulta.FhInicio, ' ', 1), ' ', -1)) AND
        (tblconsulta.Estado = 2 OR tblconsulta.Estado = 3) AND
        tblconsulta.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '".$_SESSION['codPersona_spm']."')"); */

        $sql2 = $this->connect()->prepare("SELECT DISTINCT *, tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta
        FROM tblconsulta
        INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
        INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
        WHERE tblpersona.Nombres LIKE :texto AND
        (SELECT CodConsulta FROM tbldiagnosticoconsulta WHERE tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo LIMIT 1) AND
        CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tblconsulta.FhInicio, ' ', 1), ' ', -1)) AND
        (tblconsulta.Estado = 5) AND
        tblconsulta.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '".$_SESSION['codPersona_spm']."')");
        
        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                array_push($res, $r['Nombres']."_".$r['FhInicio']);//En este formato apareceran las opciones
                }
            }
        return $res;
    }

}

?>