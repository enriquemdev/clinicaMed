<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        session_start(['name'=>'SPM']);
        $res = array();

        $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta
        FROM tblconsulta INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
        INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
        WHERE tblpersona.Nombres LIKE :texto AND
        (tblconsulta.Estado = 6 OR tblconsulta.Estado = 5) AND /*En proceso o Terminada*/
        CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tblconsulta.FhInicio, ' ', 1), ' ', -1)) AND
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