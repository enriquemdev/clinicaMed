<?php

include_once 'Database.php';

class BusquedasModelo extends Database
{

    function buscar($texto, $med)
    {
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT DISTINCT(a.laboratorio), b.nombreLaboratorio FROM tblmedicamentoproveedor as a 
        INNER JOIN catlaboratorio as b on (b.idLaboratorio=a.laboratorio)
        WHERE a.medicamento=$med AND nombreLaboratorio LIKE :texto");

        $sql2->execute(['texto' => $texto . '%']);

        if ($sql2->rowCount()) {
            while ($r = $sql2->fetch()) {
                array_push($res, $r['nombreLaboratorio'] . "__" . $r['laboratorio']); //Doble Guion Bajo
            }
        }
        return $res;
    }

    function buscar2($med)
    {
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT DISTINCT(a.laboratorio), b.nombreLaboratorio FROM tblmedicamentoproveedor as a 
        INNER JOIN catlaboratorio as b on (b.idLaboratorio=a.laboratorio)
        WHERE a.medicamento=$med LIMIT 5");

        $sql2->execute();

        if ($sql2->rowCount()) {
            while ($r = $sql2->fetch()) {
                array_push($res, $r['nombreLaboratorio'] . "__" . $r['laboratorio']); //Doble Guion Bajo
            }
        }
        return $res;
    }

    function obtenerDatos($med, $lab)
    {
        $sql2 = $this->connect()->prepare("SELECT a.proveedor,a.precioMedicamento,b.nombreProveedor, b.tiempoEntrega FROM tblmedicamentoproveedor as a
        INNER JOIN tblproveedores as b on (b.idProveedor=a.proveedor)
        WHERE a.medicamento=$med AND a.laboratorio=$lab 
        ORDER BY b.tiempoEntrega ASC LIMIT 1");
        $sql2->execute();
        return $sql2;
    }
}
