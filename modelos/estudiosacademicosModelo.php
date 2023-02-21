<?php
    require_once "mainModel.php";
    class estudiosacademicosModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_estudio_academico_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblestudioacademico (CodEmpleado,NombreEstudio,TipoEstudio,Institucion,InicioEstudio,FinEstudio) 
            VALUES(:CodEmpleado,:NombreEstudio,:TipoEstudio,:Institucion,:InicioEstudio,:FinEstudio)");

            $sql->bindParam(":CodEmpleado",$datos['CodEmpleado']);
            $sql->bindParam(":NombreEstudio",$datos['NombreEstudio']);
            $sql->bindParam(":TipoEstudio",$datos['TipoEstudio']);
            $sql->bindParam(":Institucion",$datos['Institucion']);
            $sql->bindParam(":InicioEstudio",$datos['InicioEstudio']);
            $sql->bindParam(":FinEstudio",$datos['FinEstudio']);
            $sql->execute();
            return $sql;
            
        }

        /*Obtener datos de item 1 */
                protected static function datos_item1_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catnivelacademico ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */

    } 