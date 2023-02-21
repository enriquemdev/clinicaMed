<?php

include_once 'Database.php';

class BusquedasModelo extends Database
{

    function buscar($texto)
    {
        $res = array();
        $sql2 = $this->connect()->prepare("SELECT a.Codigo,a.FechaEmision,e.Nombres,e.Apellidos FROM tblrecetamedicamentos as a
            INNER JOIN tbldetallereceta as b on (b.CodReceta=a.Codigo)
            INNER JOIN tblconsulta as c on (c.Codigo=a.CodigoConsulta)
            INNER JOIN tblpaciente as d on (d.CodigoP=c.CodPaciente)
            INNER JOIN tblpersona as e on (e.Codigo=d.CodPersona)
            WHERE Nombres LIKE :texto");

        $sql2->execute(['texto' => $texto . '%']);

        if ($sql2->rowCount()) {
            while ($r = $sql2->fetch()) {
                array_push($res, $r['Nombres'] . "-" . $r['Apellidos'] . "__" . $r['Codigo'] . "__" . $r['FechaEmision']); //Doble Guion Bajo
            }
        }
        return $res;
    }

    function obtenerDatos($receta)
    {
        $sql2 = $this->connect()->prepare("SELECT a.FechaEmision,b.Dosis,b.Frecuencia,b.Medicamento,c.nombreComercial FROM tblrecetamedicamentos as a
        INNER JOIN tbldetallereceta as b on (b.CodReceta=a.Codigo)
        inner join catmedicamentos as c on (c.Codigo=b.Medicamento)
        WHERE a.Codigo=$receta;");
        $sql2->execute();
        return $sql2;
    }
    function obtenerInventario($medicamento)
    {
        $sql2 = $this->connect()->prepare("SELECT SUM(a.cantidadEnlote) cantidad FROM tbllotemedicamento as a
        WHERE a.medicamento=$medicamento AND a.fechaVence > CURRENT_DATE();");
        $sql2->execute();
        return $sql2;
    }
    function obtenerPrecio($medicamento)
    {
        $sql2 = $this->connect()->prepare("SELECT a.precioVenta FROM tblmedicamentoprecio as a
        WHERE a.medicamento=$medicamento;");
        $sql2->execute();
        return $sql2;
    }
}
