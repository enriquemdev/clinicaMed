<?php
    require_once "mainModel.php";
    class historialdecargoModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_cargo_modelo($datos){

            //Identificador: 000
            if($datos['Estado']==1){
                $sql =mainModel::conectar()->prepare("UPDATE tblhistorialcargos 
                set Estado = 2
                where CodEmpleado=:codEmpleado");
                $sql->bindParam(":codEmpleado",$datos['CodEmpleado']);
                $sql->execute();
            }
            //Termina identificador: 000

            $sql =mainModel::conectar()->prepare("INSERT INTO tblhistorialcargos(CodEmpleado,IdCargo,FechaAsignacion,Salario,Estado,RegistradoPor,AprobadoPor) 
            VALUES(:CodEmpleado,:IdCargo,:FechaAsignacion,:Salario,:Estado,:RegistradoPor,:AprobadoPor)");

            $sql->bindParam(":CodEmpleado",$datos['CodEmpleado']);
            $sql->bindParam(":IdCargo",$datos['IdCargo']);
            $sql->bindParam(":FechaAsignacion",$datos['FechaAsignacion']);
            $sql->bindParam(":Salario",$datos['Salario']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":RegistradoPor",$datos['RegistradoPor']);
            $sql->bindParam(":AprobadoPor",$datos['AprobadoPor']);
            $sql->execute();
            return $sql;
            
        }

        /*Obtener datos de item 1 */
        protected static function datos_item1_modelo(){
            
            $sql =mainModel::conectar()->prepare("SELECT * FROM catcargos ");
            $sql->execute();
            return $sql;
        }/*Termina modelo */
        /*Obtener datos de item 2 */
        protected static function datos_item2_modelo(){
                    
            $sql =mainModel::conectar()->prepare("SELECT * FROM catestado ");
            $sql->execute();
            return $sql;
        }/*Termina modelo */

        protected static function actues($datos){

            //Identificador: 000 Cambiar en controlador para usar el estado
            if($datos['Estado']==1){
                $sql =mainModel::conectar()->prepare("UPDATE tblhistorialcargos 
                set Estado = 2
                where CodEmpleado=:codEmpleado");
                $sql->bindParam(":codEmpleado",$datos['CodEmpleado']);
                $sql->execute();
            }
            //Termina identificador: 000

            $sql =mainModel::conectar()->prepare("UPDATE tblhistorialcargos SET Estado = 1, FechaAsignacion = CURRENT_TIMESTAMP, 
            AprobadoPor= :aceptado
            WHERE ID=:id");

            $sql->bindParam(":id",$datos['id']);
            $sql->bindParam(":aceptado",$datos['aceptado']);
            $sql->execute();
            return $sql;
        }

        protected static function actualizarEstadoRechazada($datos)
        {
            $sql =mainModel::conectar()->prepare("UPDATE tblhistorialcargos SET Estado = 4, FechaAsignacion = CURRENT_TIMESTAMP, 
            AprobadoPor= :negado
            WHERE ID=:id");
            $sql->bindParam(":id",$datos['id']);
            $sql->bindParam(":negado",$datos['negado']);
            $sql->execute();
            return $sql;
        }

        protected static function buscarCodEmpleado($datos2){//Este se ocupa por el buscador
            $sql2 =mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblempleado.Codigo as cod_empleado 
            FROM tblempleado 
            INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
            /*INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
            INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID*/
            WHERE tblpersona.Nombres=:NombreEmpleado AND tblpersona.Apellidos=:ApellidoEmpleado/*AND catcargos.Nombre=:UltimoCargo*/ AND tblempleado.Codigo=:CodigoEmpleado");
            $sql2->bindParam(":NombreEmpleado",$datos2['NombreEmpleado']);
            $sql2->bindParam(":ApellidoEmpleado",$datos2['ApellidoEmpleado']);
            /*$sql2->bindParam(":UltimoCargo",$datos2['UltimoCargo']);*/
            $sql2->bindParam(":CodigoEmpleado",$datos2['CodigoEmpleado']);
            $sql2->execute();
            return $sql2;
        }
        
    } 