<?php
    require_once "mainModel.php";
    class especialidadModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_especialidad_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblespecialidad(CodDoctor,IDEspecialidad) 
            VALUES(:CodDoctor,:IDEspecialidad)");

            $sql->bindParam(":CodDoctor",$datos['CodDoctor']);
            $sql->bindParam(":IDEspecialidad",$datos['IDEspecialidad']);
            $sql->execute();
            return $sql;
            
        }

        /*Obtener datos de item 1 */
                protected static function datos_item1_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catespecialidades ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */

    } 