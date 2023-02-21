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
        (tblconsulta.Estado = 1 OR tblconsulta.Estado = 3) AND
        CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(tblconsulta.FechaYHora, ' ', 1), ' ', -1))");

        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                array_push($res, $r['Nombres']."_".$r['Apellidos']."_".$r['FechaYHora']);//En este formato apareceran las opciones
                }
            }
        return $res;
    }

}

?>