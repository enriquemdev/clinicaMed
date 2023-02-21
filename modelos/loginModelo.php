<?php
    require_once "mainModel.php";
    class loginModelo extends mainModel{
        /*Modelo para inicio de sesión */
        protected static function iniciar_sesion_modelo($datos){
            $sql=mainModel::conectar()->prepare("SELECT * FROM tblusuarios WHERE NombreUsuario=
             :Usuario AND Pass= :Clave");
            $sql->bindParam(":Usuario",$datos['Usuario']);
            $sql->bindParam(":Clave",$datos['Clave']);
            $sql->execute();
            return $sql;
        }

        //Esta es para obtener los privilegios a la hora de iniciar sesion
        protected static function iniciar_sesion_modelo2($datos){
            $sql2=mainModel::conectar()->prepare("SELECT * FROM tblprivilegiosusuario WHERE CodUsuario=
             (SELECT Codigo FROM tblusuarios WHERE NombreUsuario= :Usuario AND Pass= :Clave)" );
            $sql2->bindParam(":Usuario",$datos['Usuario']);
            $sql2->bindParam(":Clave",$datos['Clave']);
            $sql2->execute();
            return $sql2;
        }
/*shii*/
            /*Esta funcion es para hacer el recorrido del nav lateral*/
                protected static function privilegiosUsuario_modelo(){
            $sql4=mainModel::conectar()->prepare("SELECT * FROM tblprivilegiosusuario WHERE CodUsuario=
             (SELECT Codigo FROM tblusuarios WHERE NombreUsuario= :Usuario AND Pass= :Clave)" );
            $sql4->bindParam(":Usuario",$_SESSION['usuario_spm']);
            $sql4->bindParam(":Clave",$_SESSION['clave_spm']);
            $sql4->execute(); 
            return $sql4;
        }

        protected static function obtener_privilegios_modelo(){
            $sql5=mainModel::conectar()->prepare("SELECT NombreModulo FROM catmodulos INNER JOIN catsubmodulos ON catmodulos.CodModulo = catsubmodulos.CodigoModulo INNER JOIN tblprivilegiosusuario ON catsubmodulos.CodSubModulo = tblprivilegiosusuario.CodigoSubModulo WHERE CodUsuario= (SELECT Codigo FROM tblusuarios WHERE NombreUsuario= :Usuario AND Pass= :Clave)" );
            $sql5->bindParam(":Usuario",$_SESSION['usuario_spm']);
            $sql5->bindParam(":Clave",$_SESSION['clave_spm']);
            $sql5->execute(); 
            return $sql5;
        }

        /* Modelo para agregar la Sesion de un usuario*/        
        protected static function agregar_sesion_modelo($datos2){
            $sql6 =mainModel::conectar()->prepare("INSERT INTO tblsesion(CodUsuarioSesion, EstadoSesion) VALUES(:CodUsuarioSesion, :EstadoSesion)");      
            $sql6->bindParam(":CodUsuarioSesion",$datos2['CodigoUsuario']);
            $sql6->bindParam(":EstadoSesion",$datos2['EstadoSesion']);
            $sql6->execute();
            return $sql6;

        }

        /* Modelo para obtener la Sesion de un usuario si esta activa o no*/        
        protected static function obtener_sesion_modelo($datos3){
            $sql7 =mainModel::conectar()->prepare("SELECT * FROM tblsesion WHERE idSesion = (SELECT MAX(idSesion) FROM tblsesion WHERE CodUsuarioSesion= :CodUsuarioSesion)");
            $sql7->bindParam(":CodUsuarioSesion",$datos3['CodigoUsuario']);//Usuario ya esta definida en el controlador en la variable datos cuenta cuando se inicia sesion
            $sql7->execute();
            return $sql7;
            }

            //Obtener el ultimo cargo activo del usuario logueado//
            protected static function usuario_cargo_modelo($datos4)
            {
                $sql7 =mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, catcargos.Nombre as nom_cargo, tblempleado.Codigo as cod_empleado, catcargos.ID as cod_cargo, catcargos.Nombre as name_cargo
                    FROM tblempleado INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                    INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
                    INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                    WHERE tblpersona.Codigo = :CodigoPersona AND 
                        tblhistorialcargos.ID = (SELECT MAX(ID) FROM tblhistorialcargos WHERE (CodEmpleado = tblempleado.Codigo AND tblhistorialcargos.Estado = 1))");
                $sql7->bindParam(":CodigoPersona",$datos4['CodigoPersona']);//Usuario ya esta definida en el controlador en la variable datos cuenta cuando se inicia sesion
                $sql7->execute();
                return $sql7;
            }





        /*Captura los privilegios de lo submodulos
                protected static function obtener_privilegios_modelo(){
            $sql5=mainModel::conectar()->prepare("SELECT CodigoSubModulo FROM tblprivilegiosusuario WHERE CodUsuario=
             (SELECT Codigo FROM tblusuarios WHERE NombreUsuario= :Usuario AND Pass= :Clave)" );
            $sql5->bindParam(":Usuario",$_SESSION['usuario_spm']);
            $sql5->bindParam(":Clave",$_SESSION['clave_spm']);
            $sql5->execute(); 
            return $sql5;
        }

        */

/*SIRVE CUANDO SOLO ES UN PRIVILEGIO
                protected static function obtener_privilegios_modelo(){
            $sql5=mainModel::conectar()->prepare("SELECT NombreModulo FROM catmodulos WHERE CodModulo=
                (SELECT CodigoModulo FROM catsubmodulos WHERE CodSubModulo=
                (SELECT CodigoSubModulo FROM tblprivilegiosusuario WHERE CodUsuario=
             (SELECT Codigo FROM tblusuarios WHERE NombreUsuario= :Usuario AND Pass= :Clave)))" );
            $sql5->bindParam(":Usuario",$_SESSION['usuario_spm']);
            $sql5->bindParam(":Clave",$_SESSION['clave_spm']);
            $sql5->execute(); 
            return $sql5;
        }*/


    }/*Aquí termina main model */
