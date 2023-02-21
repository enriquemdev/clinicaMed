<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
            $res = array();
            $sql2 = $this->connect()->prepare("SELECT a.Codigo,c.Nombres,c.Apellidos FROM tblexamen as a 
            INNER JOIN tblpaciente as b on (b.CodigoP=a.CodPaciente)
            INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)            
            WHERE c.Nombres LIKE :texto;");

            $sql2->execute(['texto' => $texto .'%']);

            if($sql2->rowCount()){
                    while ($r = $sql2->fetch()) {
                        array_push($res,$r['Nombres']." ".$r['Apellidos']."__".$r['Codigo']);//Doble Guion Bajo
                    }
                }
            return $res;
    }

}
