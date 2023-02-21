<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblpaciente.CodigoP as cod_paciente 
        FROM tblpaciente INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
        WHERE tblpersona.Nombres LIKE :texto");
        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                    array_push($res, $r['Nombres']."-".$r['Apellidos']."-".$r['cod_paciente']);
                }
            }
        return $res;
    }

}

?>
