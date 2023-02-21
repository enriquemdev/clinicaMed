<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblempleado.Codigo as cod_empleado 
        FROM tblempleado 
        INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
        /*INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
        INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID*/
        WHERE tblpersona.Nombres LIKE :texto /*AND tblhistorialcargos.ID = (SELECT MAX(ID) FROM tblhistorialcargos WHERE (CodEmpleado = tblempleado.Codigo AND tblhistorialcargos.Estado = 1))*/");
        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                    array_push($res, $r['Nombres']."-".$r['Apellidos']./*"-".$r['nom_cargo'].*/"-".$r['cod_empleado']);
                }
            }
        return $res;
    }

}

?>

