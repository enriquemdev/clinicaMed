<?php
    require_once "mainModel.php";
    class usuarioModelo extends mainModel{

        /* Modelor para agregar usuario*/        
        protected static function agregar_usuario_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblusuarios(NombreUsuario,Pass,CodPersonaU,Estado,imgUsuario) VALUES(:Usuario,:Clave,:CodigoPersona,:Estado,:Imagen)");

            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Clave",$datos['Clave']);
            $sql->bindParam(":CodigoPersona",$datos['CodigoPersona']);
            $sql->bindParam(":Estado",$datos['EstadoUsuario']);
            $sql->bindParam(":Imagen",$datos['ImagenUsuario']);
            $sql->execute();
            return $sql;

        }

        /* Modelor para agregar privilegio*/        
        protected static function agregar_privilegio_modelo($datos){
            $sql2 =mainModel::conectar()->prepare("INSERT INTO tblprivilegiosusuario(CodUsuario,CodPrivilegio,CodigoSubModulo) VALUES(:CodUsuario,:CodPrivilegio,:CodigoSubModulo)");

            $sql2->bindParam(":CodUsuario",$datos['CodUsuario']);
            $sql2->bindParam(":CodPrivilegio",$datos['CodPrivilegio']);
            $sql2->bindParam(":CodigoSubModulo",$datos['CodigoSubModulo']);
            $sql2->execute();
            return $sql2;
        }


        protected static function obtener_usuario_modelo($datos){
            $sql3 =mainModel::conectar()->prepare("SELECT * FROM tblusuarios 
                WHERE NombreUsuario= :Usuario AND Pass= :Clave");

            $sql3->bindParam(":Usuario",$datos['Usuario']);
            $sql3->bindParam(":Clave",$datos['Clave']);
            $sql3->execute();
            return $sql3;
            }

            /*Modelo de datos de usuario */
        protected static function datos_usuario_modelo($tipo,$id){
                if($tipo=="Unico"){
                $sql4 =mainModel::conectar()->prepare("SELECT * FROM tblusuarios WHERE Codigo= :Codigo");
                $sql4->bindParam(":Codigo",$id);
                }else if($tipo=="Conteo"){
                    $sql4 =mainModel::conectar()->prepare("SELECT Codigo FROM tblusuarios WHERE Codigo!='1'");
                }
                $sql4->execute();
                return $sql4;
        }
        /*Modelo actualizar usuario */
        protected static function actualizar_usuario_modelo($datos){
            if(empty($datos['ImagenUsuario'])){
                $sql =mainModel::conectar()->prepare("UPDATE tblusuarios SET NombreUsuario=:NombreUsuario, Pass=:Pass,Estado=:Estado 
                WHERE Codigo=:Cod");
                $sql->bindParam(":NombreUsuario",$datos['Nombre']);
                $sql->bindParam(":Pass",$datos['Contra']);
                $sql->bindParam(":Estado",$datos['Estado']);
                $sql->bindParam(":Cod",$datos['Cod']);
                $sql->execute();
                return $sql;
            }else{
                $sql =mainModel::conectar()->prepare("UPDATE tblusuarios SET NombreUsuario=:NombreUsuario, Pass=:Pass,Estado=:Estado, imgUsuario=:ImagenUsuario 
                WHERE Codigo=:Cod");
                $sql->bindParam(":NombreUsuario",$datos['Nombre']);
                $sql->bindParam(":Pass",$datos['Contra']);
                $sql->bindParam(":Estado",$datos['Estado']);
                $sql->bindParam(":Cod",$datos['Cod']);
                $sql->bindParam(":ImagenUsuario",$datos['ImagenUsuario']);
                $sql->execute();
                return $sql;
            }
        }
        
        protected static function buscarCodPersona($datos2){//Este se ocupa por el buscador
            $sql2 =mainModel::conectar()->prepare("SELECT *,  tblpersona.Codigo as cod_persona FROM tblpersona 
            WHERE tblpersona.Nombres=:NombrePersona AND tblpersona.Apellidos=:ApellidoPersona AND tblpersona.Cedula=:CedulaPersona");
            $sql2->bindParam(":NombrePersona",$datos2['NombrePersona']);//El query de arriba contiene alias de sql que es lo de los as para prevenir el fallo de tener el mismo nombre de columnas en las dos tablas inlvolucradas en el inner join.
            $sql2->bindParam(":ApellidoPersona",$datos2['ApellidoPersona']);
            $sql2->bindParam(":CedulaPersona",$datos2['CedulaPersona']);
            $sql2->execute();
            return $sql2;
            }
    }
