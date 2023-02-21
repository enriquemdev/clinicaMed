<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
            $res = array();
            $sql2 = $this->connect()->prepare("SELECT a.Codigo,c.CodigoP,d.Nombres,d.Apellidos FROM tblrecetaexamen as a
            INNER JOIN tblconsulta as b on (b.Codigo=a.ConsultaPrevia)
            INNER JOIN tblpaciente as c on (c.CodigoP=b.CodPaciente)
            INNER JOIN tblpersona as d on (d.Codigo=c.CodPersona)
            WHERE d.Nombres LIKE :texto;");

            $sql2->execute(['texto' => $texto .'%']);

            if($sql2->rowCount()){
                    while ($r = $sql2->fetch()) {
                        array_push($res,$r['Nombres']." ".$r['Apellidos']."__".$r['Codigo']."__".$r['CodigoP']);//Doble Guion Bajo
                    }
                }
            return $res;
    }

}

?>