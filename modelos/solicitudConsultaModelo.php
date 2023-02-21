<?php
    require_once "mainModel.php";
    class solicitudConsultaModelo extends mainModel{
        /* Modelor para agregar servicio*/        
        /*ENRIQUE 26/09/2022*/   
        protected static function agregar_servicio_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblserviciosbrindados(tipoServicio,estadoServicio,MontoServicio,RebajaServicio) 
            VALUES(2,1,:MontoServicio,0)");
            $sql->bindParam(":MontoServicio",$datos['MontoServicio']);
            $sql->execute();
            return $sql;
        }//Termina modelo
        /*--------------Funcion para conseguir ultimo servici -------------- */
        protected static function lastservice(){
            $sql = self::conectar()->prepare("SELECT max(idServiciosBrindados) as id from tblserviciosbrindados");
            $sql -> execute();
            return $sql;

        }     
        /* Modelor para agregar solconsulta*/        
        protected static function agregar_solConsulta_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblconsulta(CodMedico,IdCita,CodPaciente,CodConsultorio,Estado,FechaYHora,MotivoConsulta,RegistradoPor,idServicio) 
            VALUES(:CodMedico,:IdCita,:CodPaciente,:CodConsultorio,:Estado,CURRENT_TIMESTAMP,:MotivoConsulta,:RegistradoPor,:servicio)");
            $sql->bindParam(":CodMedico",$datos['CodMedico']);
            $sql->bindParam(":IdCita",$datos['IdCita']);
            $sql->bindParam(":CodPaciente",$datos['CodPaciente']);
            $sql->bindParam(":CodConsultorio",$datos['CodConsultorio']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":MotivoConsulta",$datos['MotivoConsulta']);
            $sql->bindParam(":RegistradoPor",$datos['RegistradoPor']);
            $sql->bindParam(":servicio",$datos['idServicio']);
            $sql->execute();
            return $sql;
        }

        protected static function buscarCodPaciente($datos2){//Este se ocupa por el buscador
            $sql2 =mainModel::conectar()->prepare("SELECT * FROM tblpaciente INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
            WHERE tblpersona.Nombres=:NombrePaciente AND tblpersona.Apellidos=:ApellidoPaciente AND tblpaciente.CodigoP=:CodigoPaciente");
            $sql2->bindParam(":NombrePaciente",$datos2['NombrePaciente']);
            $sql2->bindParam(":ApellidoPaciente",$datos2['ApellidoPaciente']);
            $sql2->bindParam(":CodigoPaciente",$datos2['CodigoPaciente']);
            $sql2->execute();
            return $sql2;
            }
        /*Obtener datos de item 1 */
        protected static function datos_item1_modelo(){
                    
            $sql =mainModel::conectar()->prepare("SELECT * FROM catconsultorio ");
            $sql->execute();
            return $sql;
        }/*Termina modelo */

        //26/03/2022
        protected static function buscarCodEmpleado($datos2){//Este se ocupa por el buscador
            $sql3 =mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, catcargos.Nombre as nom_cargo, tblempleado.Codigo as cod_empleado FROM tblempleado INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
            INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
                    INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
            WHERE tblpersona.Nombres=:NombreEmpleado AND tblpersona.Apellidos=:ApellidoEmpleado AND catcargos.Nombre=:UltimoCargo AND tblempleado.Codigo=:CodigoEmpleado");
            $sql3->bindParam(":NombreEmpleado",$datos2['NombreEmpleado']);//El query de arriba contiene alias de sql que es lo de los as para prevenir el fallo de tener el mismo nombre de columnas en las dos tablas inlvolucradas en el inner join.
            $sql3->bindParam(":ApellidoEmpleado",$datos2['ApellidoEmpleado']);
            $sql3->bindParam(":UltimoCargo",$datos2['UltimoCargo']);
            $sql3->bindParam(":CodigoEmpleado",$datos2['CodigoEmpleado']);
            $sql3->execute();
            return $sql3;
            }
            
            protected static function actualizar_solicitud_consulta_modelo($datos){
                $sql =mainModel::conectar()->prepare("UPDATE tblconsulta SET CodMedico=:CodMedico,CodConsultorio=:CodConsultorio,
                MotivoConsulta=:MotivoConsulta, RegistradoPor=:RegistradoPor, Estado=:Estado
                WHERE Codigo=:Codigo"); //Se eliminó paciente ya que de quererse cambiar se anula
                $sql->bindParam(":CodMedico",$datos['CodMedico']);
                $sql->bindParam(":CodConsultorio",$datos['CodConsultorio']);
                $sql->bindParam(":MotivoConsulta",$datos['MotivoConsulta']);
                $sql->bindParam(":Estado",$datos['Estado']);
                $sql->bindParam(":RegistradoPor",$datos['RegistradoPor']);
                $sql->bindParam(":Codigo",$datos['Codigo']);
                $sql->execute();
                return $sql;
            }
            protected static function actualizar_cita_modelo($datos){
                $sql =mainModel::conectar()->prepare("UPDATE tbldetallesdecita SET Estado=2
                WHERE IdCita=:ID"); 
                $sql->bindParam(":ID",$datos['ID']);
                $sql->execute();
                return $sql;
            }
            /* Modelor para agregar usuario*/        
        protected static function agregar_consulta_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblconsulta(CodMedico,IdCita,CodPaciente,CodSignosVitales,CodConsultorio,Estado,idServicio) VALUES(:CodMedico,:IdCita,:CodPaciente,:CodSignosVitales,:CodConsultorio,:Estado,:servicio)");
            $sql->bindParam(":servicio",$datos['Servicio']);
            $sql->bindParam(":CodMedico",$datos['CodMedico']);
            $sql->bindParam(":IdCita",$datos['IdCita']);
            $sql->bindParam(":CodPaciente",$datos['CodPaciente']);
            $sql->bindParam(":CodSignosVitales",$datos['CodSignosVitales']);
            $sql->bindParam(":CodConsultorio",$datos['CodConsultorio']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->execute();
            return $sql;
        }
        
    }