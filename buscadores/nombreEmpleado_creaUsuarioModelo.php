
<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto){
        $res = array();
        /*
        $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblusuarios.Codigo as cod_usuario FROM tblpersona 
        LEFT JOIN tblusuarios ON tblpersona.Codigo = tblusuarios.CodPersonaU 
        WHERE tblpersona.Nombres LIKE :texto AND tblusuarios.CodPersonaU IS NULL");*/
        $sql2 = $this->connect()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblusuarios.Codigo as cod_usuario FROM tblpersona 
        LEFT JOIN tblusuarios ON tblpersona.Codigo = tblusuarios.CodPersonaU
        LEFT JOIN tblempleado ON tblpersona.Codigo = tblempleado.CodPersona
        INNER JOIN tblhistorialcargos as his on (his.CodEmpleado=tblempleado.Codigo)
        WHERE tblpersona.Nombres LIKE :texto  AND tblusuarios.CodPersonaU IS NULL AND tblempleado.CodPersona IS NOT NULL
        AND his.Estado=1;");
        
    /*Este left join es para que solo salgan las personas que no tienen usuario crrado */
    //Y los que son empleados
        $sql2->execute(['texto' => $texto .'%']);

        if($sql2->rowCount()){
                while ($r = $sql2->fetch()) {
                    array_push($res, $r['Nombres']."_".$r['Apellidos']."_".$r['Cedula']);
                }
            }
        return $res;
    }

}

?>


