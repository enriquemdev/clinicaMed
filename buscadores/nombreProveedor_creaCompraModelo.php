<?php

include_once 'Database.php';

class BusquedasModelo extends Database{

    function buscar($texto,$med,$lab){
            $res = array();
            $sql2 = $this->connect()->prepare("SELECT a.precioMedicamento,a.proveedor,b.nombreProveedor
            ,b.tiempoEntrega FROM tblmedicamentoproveedor as a
            INNER JOIN tblproveedores as b on (b.idProveedor=a.proveedor)
            WHERE nombreProveedor LIKE :texto AND a.medicamento=$med AND a.laboratorio= $lab
            ORDER BY b.tiempoEntrega ASC LIMIT 1");

            $sql2->execute(['texto' => $texto .'%']);

            if($sql2->rowCount()){
                    while ($r = $sql2->fetch()) {
                        array_push($res, $r['nombreProveedor']."__".$r['proveedor']."__".$r['tiempoEntrega']);//Doble Guion Bajo
                    }
                }
            return $res;
    }

}

?>