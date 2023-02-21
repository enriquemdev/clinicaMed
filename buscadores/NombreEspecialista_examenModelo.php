<?php

include_once 'Database.php';

class BusquedasModelo extends Database
{

    function buscar($texto)
    {
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT a.Codigo, c.Nombres,c.Apellidos,d.Nombre FROM tblempleado as a
            INNER JOIN tblespecialidad as b on (b.CodDoctor=a.Codigo)
            INNER JOIN tblpersona as c on (c.Codigo=a.CodPersona)
            INNER JOIN catespecialidades as d on (d.ID=b.IDEspecialidad)
            WHERE c.Nombres LIKE :texto;");

        $sql2->execute(['texto' => $texto . '%']);

        if ($sql2->rowCount()) {
            while ($r = $sql2->fetch()) {
                array_push($res, $r['Nombres'] . " " . $r['Apellidos'] . "__" . $r['Codigo'] . "__" . $r['Nombre']); //Doble Guion Bajo
            }
        }
        return $res;
    }
}
