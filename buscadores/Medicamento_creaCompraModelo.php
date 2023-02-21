
<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT * FROM catmedicamentos 
        WHERE catmedicamentos.nombreComercial LIKE :texto ");
    /*Este left join es para que solo salgan las personas que no tienen usuario crrado*/
        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                    array_push($res, $r['nombreComercial']."__".$r['Codigo']);
                }
            }
        return $res;
    }

}

?>


