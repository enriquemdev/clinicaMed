<?php
    require_once "mainModel.php";
    class AperturaCajaModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_apertura_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblaperturacaja(Caja,MontoInicial,EmpleadoCaja,FyHInicio,direccionMAC)
            VALUES(:Caja,:MontoInicial,:EmpleadoCaja, CURRENT_TIMESTAMP,:direccionMAC)");
            //$sql->bindParam(":Sintoma",$datos['Sintoma']);
            $sql->bindParam(":Caja",$datos['Caja']);
            $sql->bindParam(":MontoInicial",$datos['MontoInicial']);
            $sql->bindParam(":EmpleadoCaja",$datos['EmpleadoCaja']);
            $sql->bindParam(":direccionMAC",$datos['direccionMAC']);
            $sql->execute();
            return $sql;
            
        }

        /*            -- SELECT MAX(a.idApertura), a.EstadoApertura, b.idCaja,  b.nombreCaja
            -- FROM tblaperturacaja as a
            --             INNER JOIN catcaja as b ON a.Caja = b.idCaja
            -- GROUP BY a.Caja ;

            */

        /*Obtener datos de cajas disponibles mediante si tienen estado activa en alguna apertura */
        protected static function datos_item1_modelo(){
                    
            $sql =mainModel::conectar()->prepare("SELECT * FROM catcaja
            WHERE EstadoCaja = '2'
             ");
            $sql->execute();
            return $sql;
        }/*Termina modelo */


        /* Modelo de busqueda de datos de consulta para update*/
        protected static function datos_consulta_modelo($id){
            $sql4 =mainModel::conectar()->prepare("SELECT * FROM tblconsulta INNER JOIN tblpaciente ON tblconsulta.CodPaciente 
            = tblpaciente.CodigoP INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo WHERE tblconsulta.Codigo= :Codigo");
            $sql4->bindParam(":Codigo",$id);
            $sql4->execute();
            return $sql4;
        }//termina modelo

    
    }