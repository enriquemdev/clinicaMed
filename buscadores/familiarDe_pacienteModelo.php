<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
            $res = array();
            $sql2 = $this->connect()->prepare("SELECT a.CodigoP,b.Nombres,b.Apellidos,b.Cedula FROM tblpaciente as a
            INNER JOIN tblpersona as b on (b.Codigo=a.CodPersona)
            WHERE b.Nombres LIKE :texto;");

            $sql2->execute(['texto' => $texto .'%']);

            if($sql2->rowCount()){
                    while ($r = $sql2->fetch()) {
                        array_push($res, $r['Nombres']." ".$r['Apellidos']."__".$r['Cedula']);//Doble Guion Bajo
                    }
                }
            return $res;
    }

}

?>