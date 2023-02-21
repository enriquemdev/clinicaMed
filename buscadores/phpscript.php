<?php
include_once 'Database.php';

class BusquedasModelo extends Database{

    function agregar_sesion_modelo($datos2){
        $sql6 = $this->connect()->prepare("INSERT INTO tblsesion(CodUsuarioSesion, EstadoSesion) VALUES(:CodUsuarioSesion, :EstadoSesion)");      
        $sql6->bindParam(":CodUsuarioSesion",$datos2['CodigoUsuario']);
        $sql6->bindParam(":EstadoSesion",$datos2['EstadoSesion']);
        $sql6->execute();
        return $sql6;
    }

     function obtener_sesion_modelo($datos3){
        $sql7 = $this->connect()->prepare("SELECT * FROM tblsesion WHERE idSesion = (SELECT MAX(idSesion) FROM tblsesion WHERE CodUsuarioSesion= :CodUsuarioSesion)");
        $sql7->bindParam(":CodUsuarioSesion",$datos3['CodigoUsuario']);//Usuario ya esta definida en el controlador en la variable datos cuenta cuando se inicia sesion
        $sql7->execute();
        return $sql7;
        }

}